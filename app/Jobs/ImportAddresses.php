<?php

namespace App\Jobs;

use App\Address;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ImportAddresses implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $path;
    protected $companyId;
    protected $user;
    protected $fields;
    protected $row;
    protected $results;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $companyId, User $user)
    {
        $this->path = storage_path('app/'.$path);
        $this->companyId = $companyId;
        $this->user = $user;
        $this->fields = ['name', 'company_name', 'address1', 'address2', 'address3', 'city', 'state', 'postcode', 'country_code', 'telephone', 'email', 'type'];
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
        // Process the CSV
        $this->processCsv();

        // Delete the uploaded file
        unlink($this->path);

        // Inform user that the import has been completed
        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\AddressImportResults($this->results));
    }

    /**
     * Read through the CSV and insert a record for each valid row.
     */
    private function processCsv()
    {
        $rowNumber = 1;

        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($this->validateRow($rowNumber, $data)) {
                    $this->insertAddress($rowNumber);
                }
                $rowNumber++;
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
        $validator = Validator::make($this->row, [
                    'name' => 'required|min:2|max:35',
                    'company_name' => 'min:2|max:35',
                    'address1' => 'required|min:2|max:35',
                    'address2' => 'max:35',
                    'address3' => 'max:35',
                    'city' => 'required|regex:/^[a-zA-Z \'-]+$/|min:2|max:30',
                    'state' => 'regex:/^[a-zA-Z ]+$/|min:2|max:30',
                    'postcode' => 'min:2|max:10',
                    'country_code' => 'required|alpha|size:2',
                    'telephone' => 'max:20',
                    'email' => 'email|max:60',
                    'type' => 'required|alpha|size:1',
        ]);

        // Check for errors
        $errors = $validator->errors();

        // Found errors, return false and add to error array
        if (count($errors) > 0) {
            $this->results['rows'][$rowNumber]['data'] = $this->row;
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
    private function insertAddress($rowNumber)
    {
        // Add additional field values not present in upload
        $this->row['definition'] = 'recipient';
        $this->row['company_id'] = $this->companyId;

        // Add to our results array
        $this->results['rows'][$rowNumber]['data'] = $this->row;
        $this->results['summary']['inserted']++;

        // Save the record to the database
        Address::firstOrCreate($this->row);
    }
}
