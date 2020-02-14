<?php

namespace App\Jobs;

use App\Carrier;
use App\FuelSurcharge;
use App\Service;
use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Validator;

class ImportFuelSurcharges implements ShouldQueue
{
    use Queueable;

    protected $path;
    protected $user;
    protected $fields;
    protected $row;
    protected $startRow = 2;
    protected $delimiter = ',';
    protected $carrier;
    protected $results;
    protected $source;
    protected $errors;

    /**
     * Create a new job instance.
     *
     * @param string    $path
     * @param int   $importConfigId
     * @param User      $user
     *
     * @return void
     */
    public function __construct($path, User $user)
    {
        $this->path = storage_path('app/'.$path);
        $this->user = $user;
        $this->source = substr($path, 14, 12);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setFields();

        $this->setResultsArray();

        $this->processFile();

        $this->exportResultsToCsvFiles();

        $this->results['files'][] = $this->path;

        $this->setSubject();

        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\FuelSurchargeUploadResults($this->results));
    }

    /**
     * Define Fields to import.
     *
     * @return void
     */
    private function setFields()
    {
        $this->fields = ['carrier_code', 'service_code', 'fuel_percent', 'from_date'];
    }

    /**
     * Read through the file and insert a record for each valid row.
     *
     * @return void
     */
    private function processFile()
    {
        $rowNumber = 1;
        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
                if ($rowNumber >= $this->startRow) {
                    $this->processRow($rowNumber, $data);
                }
                $rowNumber++;
            }
            fclose($handle);
        }
    }

    /**
     * Process a row read from the uploaded file.
     *
     * @param type $rowNumber
     * @param type $data to build row
     *
     * @return void
     */
    private function processRow($rowNumber, $data)
    {
        $this->row = [];

        // Assign field names to the data array
        $this->buildRow($data);

        // Ignore blank lines (or lines with no fuel %)
        if ($this->row['fuel_percent'] == '') {
            return;
        }

        // Pass the data back to the results summary
        $this->results['rows'][$rowNumber]['data'] = $this->row;

        // Invalid number of fields, return false
        if (count($data) != count($this->fields)) {
            $this->setRowFailed($rowNumber, [0 => 'Invalid number of fields detected. Detected '.count($data).' fields. '.count($this->fields).' required']);

            return false;
        }

        // Attempt to supply values for any information not provided
        $this->completeEmptyFields();

        // Validate row
        if ($this->validate($rowNumber) && $this->row['fuel_percent'] > '') {
            $this->updateTables($rowNumber);
        }
    }

    /**
     * Validate row.
     *
     * @param type $rowNumber
     * @return bool
     */
    private function validate($rowNumber)
    {
        $this->doGenericValidation();

        if (! $this->errors) {
            $this->doSpecialValidation();
        }

        /*
         * **********************************
         * Return false if errors
         * **********************************
         */
        if ($this->errors) {
            $this->setRowFailed($rowNumber, $this->errors);
            $this->errors = null;

            return false;
        }

        return true;
    }

    /**
     * Do some general validation on data format and values.
     */
    private function doGenericValidation()
    {
        $maxDate = date('Y-m-d', strtotime('+2 weeks'));

        // Add general rules
        $rules = [
            'carrier_code' => 'required|exists:carriers,code',
            'fuel_percent' => 'required|min:0|max:30.00',
            'from_date' => 'required|date|date_format:Y-m-d|after:yesterday|before:'.$maxDate,
        ];

        $validator = Validator::make($this->row, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $this->errors[] = $message;
            }
        }
    }

    /**
     * Do additional validation against existing data in tables.
     */
    private function doSpecialValidation()
    {
        // Check no rates loaded for a date after this one
        if ($this->hasFutureRate()) {
            $this->errors[] = 'Rate already exists after start date';
        }

        // Ignore Duplicates
        if ($this->isDuplicate()) {
            $this->errors[] = 'Duplicate - Ignored';
        }

        // Ignore Duplicates
        if ($this->percentageOutOfBounds()) {
            $this->errors[] = 'Percentage change is too large';
        }

        // Unless UPS/IPU check the service exists for the carrier
        if (strtoupper($this->row['carrier_code']) == 'UPS' && strtoupper($this->row['service_code']) == 'IPU') {
            return;
        }

        // Check Service exists for carrier
        $this->carrier = Carrier::where('code', $this->row['carrier_code'])->first();

        if ($this->carrier) {
            $service = Service::where('code', $this->row['service_code'])->where('carrier_id', $this->carrier->id)->first();

            if (! $service) {
                $this->errors[] = 'Service not valid for Carrier';
            }
        }
    }

    private function percentageOutOfBounds()
    {

        // Check to see if an existing rate overlaps
        /*
          $fuelSurcharges = \App\FuelSurcharge::where('carrier_id', $this->row['carrier_id'])
          ->where('service_code', strtolower($this->row['service_code']))
          ->where('to_date', '>=', $this->row['from_date'])
          ->first();

          if ($fuelSurcharges) {

          $diff = abs($this->row['fuel_percent'] - $fuelSurcharges->fuel_percent);

          $increase = ($diff * 100) / $fuelSurcharges->fuel_percent;

          if ($diff > 3 && $increase > 40)
          return true;
          }
         *
         */

        return false;
    }

    /**
     * @param type $rowNumber
     * @return bool
     */
    private function updateTables($rowNumber)
    {
        // Found errors, return false and add to error array
        if ($this->errors) {
            $this->setRowFailed($rowNumber, $this->errors);

            return false;
        }

        $this->closePreviousRates($this->row);

        // Create Fuel Surcharge
        $result = FuelSurcharge::create($this->row);

        // Found errors, return false and add to error array
        if (isset($result['errors']) && is_array($result['errors'])) {
            $this->setRowFailed($rowNumber, $result['errors']);

            return false;
        }

        $this->setRowSucceeded($rowNumber, $result);
    }

    /**
     * Declare the results array.
     *
     * @return void
     */
    private function setResultsArray()
    {
        $this->results = [];
        $this->results['user']['id'] = $this->user->id;
        $this->results['user']['name'] = $this->user->name;
        $this->results['source'] = $this->source;
        $this->results['success'] = [];
        $this->results['failed'] = [];
        $this->results['rows'] = [];
    }

    /**
     * Add a row to failed results.
     *
     * @param type $rowNumber
     * @param type $errors
     *
     * @return void
     */
    private function setRowFailed($rowNumber, $errors)
    {
        $this->results['failed'][$rowNumber] = $this->row;
        $this->results['failed'][$rowNumber]['errors'] = $errors;
    }

    /**
     * Add a row to the successful results.
     *
     * @param type $rowNumber
     * @param type $result
     *
     * @return void
     */
    private function setRowSucceeded($rowNumber, $result)
    {
        $this->results['success'][$rowNumber] = $this->row;
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param type $data
     *
     * @return void
     */
    private function buildRow($data)
    {
        $i = 0;
        foreach ($this->fields as $field) {
            $this->row[$field] = (isset($data[$i])) ? strtolower(trim(convertToUTF8($data[$i]))) : null;
            $i++;
        }

        $this->row['to_date'] = '2099-12-31';
    }

    /**
     * Check to see if a record exists for any period after my start date.
     *
     * @return bool
     */
    private function hasFutureRate()
    {
        $fuelSurcharges = \App\FuelSurcharge::where('carrier_id', $this->row['carrier_id'])
                ->where('service_code', strtolower($this->row['service_code']))
                ->where('from_date', '>', $this->row['from_date'])
                ->get();

        if ($fuelSurcharges->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * Check to see if a record is a duplicate.
     *
     * @return bool
     */
    private function isDuplicate()
    {
        $fuelSurcharges = \App\FuelSurcharge::where('carrier_id', $this->row['carrier_id'])
                ->where('service_code', strtolower($this->row['service_code']))
                ->where('from_date', '=', $this->row['from_date'])
                ->where('to_date', '=', $this->row['to_date'])
                ->where('fuel_percent', '=', number_format($this->row['fuel_percent'], 2))
                ->get();

        if ($fuelSurcharges->isEmpty()) {
            return false;
        }

        return true;
    }

    private function closePreviousRates()
    {
        DB::enableQueryLog();

        // Check to see if an existing rate overlaps
        $fuelSurcharges = \App\FuelSurcharge::where('carrier_id', $this->row['carrier_id'])
                ->where('service_code', strtolower($this->row['service_code']))
                ->where('from_date', '<=', $this->row['from_date'])
                ->where('to_date', '>=', $this->row['to_date'])
                ->get();

        if (! $fuelSurcharges->isEmpty()) {
            foreach ($fuelSurcharges as $fuelSurcharge) {
                if ($fuelSurcharge->from_date->format('Y-m-d') == $this->row['from_date']) {

                    // Looks like we are trying to re-load an existing record so delete existing one first
                    $fuelSurcharge->delete();
                } else {

                    // If Existing record starts prior, then close
                    $surcharge = \App\FuelSurcharge::where('id', $fuelSurcharge['id'])
                            ->update(['to_date' => date('Y-m-d', strtotime($this->row['from_date'].' -1 day'))]);
                }
            }
        }
    }

    /**
     * Export the results array into csv files. Rows that were successfully imported are inserted
     * to success.csv and failed rows into failed.csv.
     *
     * @return void
     */
    private function exportResultsToCsvFiles()
    {
        if (count($this->results['success']) > 0) {
            $this->exportRowsToCsv($this->results['success'], 'success_'.$this->source);
        }
        if (count($this->results['failed']) > 0) {
            $this->exportRowsToCsv($this->results['failed'], 'failed_'.$this->source);
        }
    }

    /**
     * Create a csv file from an array of rows.
     *
     * @param array $rows
     * @param string $fileName
     *
     * @return void
     */
    private function exportRowsToCsv($rows, $fileName)
    {
        // Build an array that will be written to csv
        foreach ($rows as $key => $row) {
            foreach ($this->fields as $field) {
                $data[$key][$field] = (isset($row[$field])) ? $row[$field] : null;
            }

            if (isset($row['errors'])) {
                unset($data[$key]['errors']);
            }
        }

        writeCsv(storage_path().'/app/temp/'.$fileName.'.csv', $data);

        // Add the filename to the results array
        $this->results['files'][] = storage_path().'/app/temp/'.$fileName.'.csv';
    }

    /**
     * Get the subject of mail sent to user.
     *
     * @return string
     */
    private function setSubject()
    {
        $this->results['subject'] = 'Fuel Surcharge Upload ('.count($this->results['success']).' processed / '.count($this->results['failed']).' failed';
    }

    /**
     * Attempt to supply values for any information not provided.
     */
    private function completeEmptyFields()
    {
        // Add Misc details

        $carrier = Carrier::where('code', strtolower($this->row['carrier_code']))->first();

        if ($carrier) {
            $this->row['carrier_id'] = $carrier->id;
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed("Fuel Surcharge Upload ($this->source)", $exception, $this->path));
    }
}
