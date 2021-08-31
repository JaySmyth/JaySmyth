<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\DomesticZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ImportDomesticZones implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $path;
    protected $model;
    protected $user;
    protected $fields;
    protected $row;
    protected $results;
    protected $domesticZone;
    public $timeout = 1200;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $model, User $user)
    {
        $this->path = storage_path('app/'.$path);
        $this->model = $model;
        $this->user = $user;
        $this->fields = ['postcode', 'zone', 'model', 'sla'];
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
        $this->domesticZone = new DomesticZone();

        // Process the CSV
        $this->processCsv();

        // Delete the uploaded file
        unlink($this->path);

        // Inform user that the import has been completed
        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\DomesticZoneImportResults($this->model, $this->results));
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
                'postcode' => 'required|string:min:2|max:10',
                'zone' => 'required|string|min:1|max:3',
                'model' => 'required|string|min:1|max:12',
                'sla' => 'required|integer|min:24',
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
        $this->domesticZone->firstOrCreate(
            [
            'postcode' => $this->row['postcode'],
            'model' => $this->row['model'],
        ],
            [
            'zone' => $this->row['zone'],
            'sla' => $this->row['sla'],
            ]
        );
    }

    public function deleteExistingZones()
    {
        // If zones exist - delete them.
        $this->domesticZone->where('model', $this->model)->delete();
    }
}
