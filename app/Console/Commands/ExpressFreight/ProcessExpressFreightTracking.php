<?php

namespace App\Console\Commands;

use Validator;
use Illuminate\Console\Command;

class ProcessExpressFreightTracking extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-express-freight-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads directory for csv files uploaded by EF and inserts tracking events';

    /**
     * Full path to be processed.
     *
     * @var string
     */
    protected $directory = '/home/expressfreight/tracking/';

    /**
     * Directory processed files to be archived.
     *
     * @var string
     */
    protected $archiveDirectory = 'archive';

    /**
     * Array of field names.
     *
     * @var array
     */
    protected $fields = array('carrier_tracking_number', 'date', 'location', 'name', 'status');

    /**
     * Results array.
     *
     * @var array
     */
    protected $results;

    /**
     * Temp file.
     *
     * @var string
     */
    protected $tempFile;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Checking ' . $this->directory . ' for files to process');

        if ($handle = opendir($this->directory)) {

            while (false !== ($file = readdir($handle))) {

                if (!is_dir($file) && $file != $this->archiveDirectory) {
                    $this->processFile($file);
                    $this->archiveFile($file);
                }
            }

            closedir($handle);
        }
    }

    /**
     * Read through the file and process the rows.
     *
     * @return void
     */
    protected function processFile($file)
    {

        $this->info("Processing $file");

        $rowNumber = 1;
        $data = null;

        if (($handle = fopen($this->directory . $file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, chr(9))) !== false) {
                if ($rowNumber >= 2) {
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
     * @param type $data
     *
     * @return void
     */
    protected function processRow($rowNumber, $data)
    {
        // Assign field names to the data array
        $row = $this->assignFieldNames($data);

        // Row passes validation, continue
        if ($this->validateRow($rowNumber, $data, $row)) {

            // Load the shipment record
            $shipment = \App\Shipment::where('carrier_tracking_number', $row['carrier_tracking_number'])->where('carrier_id', 14)->first();

            if ($shipment) {

            }

        }
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param type $data
     *
     * @return void
     */
    protected function assignFieldNames($data)
    {
        $row = array();

        $i = 0;
        foreach ($this->fields as $field) {
            $row[$field] = (isset($data[$i])) ? trim($data[$i]) : null;
            $i++;
        }

        return $row;
    }

    /**
     * Basic row validation. Returns true/false and sets row to failed if validation fails.
     *
     * @param type $rowNumber
     * @param type $data
     * @param type $row
     * @return boolean
     */
    protected function validateRow($rowNumber, $data, $row)
    {
        return true;
        // First check for correct number of fields
        if (count($data) != count($this->fields)) {
            $this->setRowFailed($rowNumber, $row, "Invalid number of fields detected. Detected " . count($data) . " fields. " . count($this->fields) . " required");
            return false;
        }

        $rules = [
            'ReferenceNumber' => 'required',
            'TrackingNum' => 'required|string|min:6',
            'ShipDate' => 'required|string',
        ];

        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $this->setRowFailed($rowNumber, $row, $errors->all());
            return false;
        }

        return true;
    }


    /**
     * Move file to archive directory.
     *
     * @param string $file
     * @return boolean
     */
    protected function archiveFile($file)
    {
        $this->info("Archiving file $file");

        $originalFile = $this->directory . $file;
        $archiveFile = $this->directory . $this->archiveDirectory . '/' . $file;

        $this->info("Moving $originalFile to archive");

        if (!file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (copy($originalFile, $archiveFile)) {
            unlink($originalFile);
            $this->info("File archived successfully");
        }
    }

}
