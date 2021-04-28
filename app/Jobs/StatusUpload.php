<?php

namespace App\Jobs;

use App\Models\Shipment;
use App\Models\Status;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Validator;

class StatusUpload implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    protected $status;
    protected $user;
    protected $fields;
    protected $row;
    protected $results;
    protected $source;
    protected $errors;
    protected $maxRows = 250;
    protected $failedMaxRows = false;
    protected $shipment;

    /**
     * Create a new job instance.
     *
     * @param string $path
     * @param int $statusCode
     * @param User $user
     *
     * @return void
     */
    public function __construct($path, $statusCode, User $user)
    {
        $this->path = $path;
        $this->user = $user;
        $this->status = Status::where('code', $statusCode)->first();
        $this->errors = [];

        // Extract string identifier from the filename
        $source = pathinfo($path);
        $this->source = substr($source['filename'], 9, 12);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkNumberOfRows();

        $this->setFields();

        $this->setResultsArray();

        $this->processFile();

        $this->exportResultsToCsvFiles();

        $this->results['files'][] = $this->path;

        $this->setSubject();

        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\ShipmentStatusUploadResults($this->results));
    }


    /**
     * Check if max no. of rows has been exceeded and set flag.
     */
    private function checkNumberOfRows()
    {
        $rowCount = count(file($this->path, FILE_SKIP_EMPTY_LINES));

        if ($rowCount > $this->maxRows) {
            $this->failedMaxRows = true;
        }
    }

    /**
     * Set fields as defined in the import config table.
     *
     * @return void
     */
    private function setFields()
    {
        $this->fields[] = 'consignment_number';
    }

    /**
     * Declare the results array.
     *
     * @return void
     */
    private function setResultsArray()
    {
        $this->results['user']['id'] = $this->user->id;
        $this->results['user']['name'] = $this->user->name;
        $this->results['source'] = $this->source;
        $this->results['success'] = [];
        $this->results['failed'] = [];
        $this->results['rows'] = [];
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
            while (($data = fgetcsv($handle, 1000, $this->getDelimiter())) !== false) {
                if (!is_null($data['0'])) {
                    $this->processRow($rowNumber, $data);
                    $rowNumber++;
                }
            }
            fclose($handle);
        }
    }

    private function getDelimiter()
    {
        return ',';
    }

    /**
     * Process a row read from the uploaded file.
     *
     * @param type $rowNumber
     * @param type $data
     *
     * @return void
     */
    private function processRow($rowNumber, $data)
    {
        $this->row = [];

        // Assign field names to the data array
        $this->assignFieldNames($data);

        // Pass the data back to the results summary
        $this->results['rows'][$rowNumber]['data'] = $this->row;

        // Too many lines in the CSV file
        if ($this->failedMaxRows) {
            $this->setRowFailed($rowNumber, [0 => 'Too many lines in CSV file. Max permitted: ' . $this->maxRows . ' lines']);
            return false;
        }

        // Invalid number of fields, return false
        if (count($data) != count($this->fields)) {
            $this->setRowFailed($rowNumber, [0 => 'Invalid number of fields detected. Detected '.count($data).' fields. '.count($this->fields).' required']);
            return false;
        }

        // Attempt to supply values for any information not provided
        $this->completeEmptyFields();

        // Some validation
        $this->shipment = Shipment::where('consignment_number', $this->row['consignment_number'])
                                    ->orWhere('carrier_consignment_number', $this->row['consignment_number'])
                                    ->first();
        if (empty($this->shipment)) {
            $this->errors[] = 'Shipment not found';
        }
        if (!isset($this->shipment->status_id)) {
            $this->errors[] = 'Shipment current status undefined';
        }
        if (isset($this->shipment->status_id)) {
            if ($this->shipment->status_id == $this->status->id) {
                $this->errors[] = 'Status already set';
            }
            if (in_array($this->shipment->status_id, [6, 7, 19])) {
                $this->errors[] = 'Shipment closed';
            }
        }

        if ($this->errors != []) {
            $this->setRowFailed($rowNumber, $this->errors);
            $this->errors = [];

            return false;
        }

        // Update Status
        if ($this->status->code == 'cancelled') {
            $this->shipment->setCancelled($this->user->id);
        } else {
            $this->shipment->setStatus($this->status->code, $this->user->id);
        }

        // If no errors, return true
        $this->setRowSucceeded($rowNumber, [
            'consignment_number' => $this->row['consignment_number'],
            'status_code' => $this->status->description,
        ]);

        return true;
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param type $data
     *
     * @return void
     */
    private function assignFieldNames($data)
    {
        $i = 0;
        foreach ($this->fields as $field) {
            $this->row[$field] = (isset($data[$i])) ? trim(convertToUTF8($data[$i])) : null;
            $i++;
        }
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
     * Attempt to supply values for any information not provided.
     */
    private function completeEmptyFields()
    {
        return;
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
        $this->results['success'][$rowNumber]['consignment_number'] = $result['consignment_number'];
        $this->results['success'][$rowNumber]['status_code'] = $result['status_code'];
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

            if (isset($row['consignment_number'])) {
                $data[$key]['consignment_number'] = $row['consignment_number'];
            }

            if (isset($row['status_code'])) {
                $data[$key]['status_code'] = $row['status_code'];
            }

            if (isset($row['errors'])) {
                unset($data[$key]['errors']);
            }
        }

        $result = writeCsv(storage_path().'/app/temp/'.$fileName.'.csv', $data);

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
        $this->results['subject'] = 'Shipment Status Upload - '.count($this->results['success']).' updated / '.count($this->results['failed']).' failed';
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed($exception)
    {
        // Mail exception to IT
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed("Shipment Status Import ($this->source)", $exception, $this->path));

        // Mail end user to notify thenm of an issue
        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Shipment Status - Upload Failed', 'There was a problem with the file uploaded. Please check the values in the CSV file and try again.', $this->path));
    }
}
