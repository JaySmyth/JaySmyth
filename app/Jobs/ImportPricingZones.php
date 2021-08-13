<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\PricingZones;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ImportPricingZones implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $path;
    protected $model;
    protected $serviceCode;
    protected $user;
    protected $fields;
    protected $row;
    protected $results;
    protected $pricingZone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $model, $serviceCode, User $user)
    {
        $this->path = storage_path('app/'.$path);
        $this->model = $model;
        $this->serviceCode = $serviceCode;
        $this->user = $user;
        $this->fields = ['company_id', 'model_id', 'sender_country_code', 'from_sender_postcode', 'to_sender_postcode', 'recipient_country_code', 'recipient_name', 'from_recipient_postcode', 'to_recipient_postcode', 'service_code', 'cost_zone', 'sale_zone', 'from_date', 'to_date'];
        $this->results['summary']['inserted'] = 0;
        $this->results['summary']['failed'] = 0;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->pricingZone = new PricingZones();

        // Process the CSV
        $this->processCsv();

        // Delete the uploaded file
        unlink($this->path);

        // Inform user that the import has been completed
        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\PricingZonesImportResults($this->model, $this->results));
    }

    /**
     * Read through the CSV and insert a record for each valid row.
     */
    private function processCsv()
    {
        $rowNumber = 1;
        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($rowNumber>1) {
                    $this->validateRow($rowNumber, $data);
                }
                $rowNumber++;
            }

            // If no errors then insert into table
            if ($this->results['summary']['failed'] == 0) {
                rewind($handle);
                $rowNumber = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    switch ($rowNumber) {
                        case '1':
                            // Headers - do nothing
                            break;
                        case '2':
                            // Delete any existing data then fall through to Default
                            $this->row = $this->assignFieldNames($data);
                            $this->deleteExistingZones();
                            // no break
                        default:
                            $this->row = $this->assignFieldNames($data);
                            $this->insertRow($rowNumber);
                        break;
                    }
                    $rowNumber++;
                }
            }

            fclose($handle);
        }

        return $rowNumber;
    }

    /**
     * Validate a csv row.
     *
     * @param type $row
     * @param type $data
     */
    private function validateRow($rowNumber, $data)
    {
        $numberOfFields = count($data);

        // Invalid number of fields, return false
        if ($numberOfFields != count($this->fields)) {
            $this->results['rows'][$rowNumber]['errors'][] = "Invalid number of fields detected. Detected $numberOfFields fields. ".count($this->fields).'required';

            return false;
        }

        // Assign field names to the data array
        $this->row = $this->assignFieldNames($data);

        // Validate the array using laravel validator
        $validator = $this->buildValidator();

        // Check for errors
        $errors = $validator->errors();

        // Found errors, return false and add to error array
        $this->results['rows'][$rowNumber]['data'] = $this->row;
        if (count($errors) > 0) {
            $this->results['rows'][$rowNumber]['errors'] = $errors->all();
            $this->results['summary']['failed']++;

            foreach ($this->fields as $field) {
                if ($errors->has($field)) {
                    $this->results['rows'][$rowNumber]['fields_in_error'][$field] = true;
                }
            }

            return false;
        }

        return true;
    }

    public function buildValidator()
    {
        $validator = Validator::make($this->row, [
            'company_id' => 'required|integer|min:0|max:9999',
            'model_id' => 'required|string|min:0|max:7|in:'.$this->model,
            'sender_country_code' => 'required|string|exists:countries,country_code',
            'from_sender_postcode' => 'nullable|string|min:1|max:10',
            'to_sender_postcode' => 'nullable|string|min:1|max:10',
            'recipient_country_code' => 'required|string|exists:countries,country_code',
            'recipient_name' => 'nullable|string|min:1|max:30',
            'from_recipient_postcode' => 'nullable|string|min:1|max:10',
            'to_recipient_postcode' => 'nullable|string|min:1|max:10',
            'service_code' => 'required|string|in:'.$this->serviceCode,
            'cost_zone' => 'required|string|min:1|max:5',
            'sale_zone' => 'required|string|min:1|max:5',
            'from_date' => 'required|date_format:Y-m-d|after:yesterday',
            'to_date' => 'required|date_format:Y-m-d|after:from_date',
            ]);

        return $validator;
    }

    /**
     * Create a new array with field names.
     *
     * @param type $data
     * @return type
     */
    private function assignFieldNames($data)
    {
        $row = [];
        $i = 0;
        foreach ($this->fields as $field) {
            $row[$field] = trim($data[$i]);
            $i++;
        }

        return $row;
    }

    /**
     * Insert a record to the database.
     *
     * @param type $data
     */
    private function insertRow($rowNumber)
    {
        // Add additional field values not present in upload

        // Add to our results array
        $this->results['rows'][$rowNumber]['data'] = $this->row;
        $this->results['summary']['inserted']++;

        // Save the record to the database
        $this->pricingZone->firstOrCreate(
            [
                'company_id' => $this->row['company_id'],
                'model_id' => $this->row['model_id'],
                'sender_country_code' => $this->row['sender_country_code'],
                'from_sender_postcode' => $this->row['from_sender_postcode'],
                'to_sender_postcode' => $this->row['to_sender_postcode'],
                'recipient_country_code' => $this->row['recipient_country_code'],
                'recipient_name' => $this->row['recipient_name'],
                'from_recipient_postcode' => $this->row['from_recipient_postcode'],
                'to_recipient_postcode' => $this->row['to_recipient_postcode'],
                'service_code' => $this->row['service_code'],
                'from_date' => $this->row['from_date'],
                'to_date' => $this->row['to_date'],
        ],
            [
                'cost_zone' => $this->row['cost_zone'],
                'sale_zone' => $this->row['sale_zone'],
            ]
        );
    }

    public function deleteExistingZones()
    {
        $closeDate = date('Y-m-d', strtotime('-1 day', strtotime($this->row['from_date'])));
        $oneYearAgo = date('Y-m-d', strtotime('1 year ago'));

        // Delete any existing records that have expired more than 12 months.
        $this->pricingZone->where('to_date', '<=', $oneYearAgo)->delete();

        // If Future zones exist - delete them.
        $this->pricingZone
        ->where('company_id', $this->row['company_id'])
        ->where('model_id', $this->row['model_id'])
        ->where('service_code', $this->row['service_code'])
        ->where('from_date', '>=', $this->row['from_date'])
        ->delete();

        // If existing zones - close them off
        $this->pricingZone
        ->where('company_id', $this->row['company_id'])
        ->where('model_id', $this->row['model_id'])
        ->where('service_code', $this->row['service_code'])
        ->where('to_date', '>=', $closeDate)
        ->update(['to_date' => $closeDate]);
    }
}
