<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Rate;
use App\Models\RateDetail;
use App\Models\DomesticRate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ImportMasterRate implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $path;
    protected $rate;
    protected $user;
    protected $fields;
    protected $row;
    protected $results;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, Rate $rate, User $user)
    {
        $this->path = storage_path('app/'.$path);
        $this->rate = $rate;
        $this->user = $user;
        if ($rate->model == 'domestic') {
            $this->fields = ['rate_id', 'service', 'packaging_code', 'first', 'others', 'notional_weight', 'notional', 'area', 'from_date', 'to_date'];
        } else {
            $this->fields = ['rate_id', 'residential', 'piece_limit', 'package_type', 'zone', 'break_point', 'weight_rate', 'weight_increment', 'package_rate', 'consignment_rate', 'weight_units', 'from_date', 'to_date'];
        }
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
        Mail::to($this->user->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\RateImportResults($this->rate->model, $this->results));
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
                    if ($rowNumber==1) {
                        $closeDate = date('Y-m-d', strtotime('-1 day', strtotime($this->row['from_date'])));
                        $this->closeExistingRate($closeDate);
                    } else {
                        $this->row = $this->assignFieldNames($data);
                        $this->insertRow($rowNumber);
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
        if ($this->rate->model == 'domestic') {
            $validator = Validator::make($this->row, [
                'rate_id' => 'required|exists:rates,id|in:'.$this->rate->id,
                'service' => 'required|exists:services,code',
                'packaging_code' => 'required|string|min:3|max:10',
                'first' => 'required|numeric',
                'others' => 'required|numeric',
                'notional_weight' => 'required|numeric',
                'notional' => 'required|numeric',
                'area' => 'required|string|min:1|max:2',
                'from_date' => 'required|date_format:Y-m-d|after:yesterday',
                'to_date' => 'required|date_format:Y-m-d|after:from_date',
            ]);
        } else {
            $validator = Validator::make($this->row, [
                'rate_id' => 'required|exists:rates,id|in:'.$this->rate->id,
                'residential' => 'required|in:0,1',
                'piece_limit' => 'required|integer|min:1|max:99999',
                'package_type' => 'required|string|min:3|max:10',
                'zone' => 'required|string|min:1|max:2',
                'break_point' => 'required|numeric',
                'weight_rate' => 'required|numeric',
                'weight_increment' => 'required|integer',
                'package_rate' => 'required|numeric',
                'consignment_rate' => 'required|numeric',
                'weight_units' => 'required|in:kg,lb',
                'from_date' => 'required|date_format:Y-m-d|after:yesterday',
                'to_date' => 'required|date_format:Y-m-d|after:from_date',
            ]);
        }

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
        $this->row['rate_id'] = $this->rate->id;

        // Add to our results array
        $this->results['rows'][$rowNumber]['data'] = $this->row;
        $this->results['summary']['inserted']++;

        // Save the record to the database
        if ($this->rate->model == 'domestic') {
            DomesticRate::firstOrCreate($this->row);
        } else {
            RateDetail::firstOrCreate($this->row);
        }
    }

    public function closeExistingRate($closeDate)
    {
        if ($this->rate->model == 'domestic') {
            // If user has re-uploaded or there is a future rate - delete it.
            DomesticRate::where('rate_id', $this->rate->id)->where('from_date', '>=', $this->row['from_date'])->delete();

            // Set the "to_date" of the old rate (if it exists) to the date provided (the day before the new rate starts).
            DomesticRate::where('rate_id', $this->rate->id)->where('to_date', '>=', $closeDate)->update(['to_date' => $closeDate]);
        } else {
            // If user has re-uploaded or there is a future rate - delete it.
            RateDetail::where('rate_id', '10')->where('from_date', '>=', $this->row['from_date'])->delete();

            // Set the "to_date" of the old rate (if it exists) to the date provided (the day before the new rate starts).
            RateDetail::where('rate_id', '10')->where('to_date', '>=', $closeDate)->update(['to_date' => $closeDate]);
        }
    }
}
