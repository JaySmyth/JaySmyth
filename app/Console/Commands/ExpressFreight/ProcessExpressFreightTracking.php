<?php

namespace App\Console\Commands\ExpressFreight;

use Validator;
use App\Tracking;
use Carbon\Carbon;
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
    protected $fields = array('carrier_tracking_number', 'status', 'datetime', 'location', 'name', 'attempted_status');

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

            while (($data = fgetcsv($handle, 1000)) !== false) {

                $this->processRow($rowNumber, $data);
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

                $datetime = Carbon::createFromformat('YmdHis', $row['datetime']);
                $datetime = gmtToCarbonUtc($datetime);

                $message = $row['status'];

                if (strlen($row['attempted_status']) > 0 && $row['status'] != $row['attempted_status']) {
                    $message . ' - ' . $row['attempted_status'];
                }

                Tracking::firstOrCreate([
                    'message' => $message,
                    'datetime' => $datetime,
                    'local_datetime' => $datetime,
                    'shipment_id' => $shipment->id,
                    'carrier' => 'Express Freight',
                    'city' => $row['location'],
                    'country_code' => ($row['location'] == 'CRAIGAVON') ? 'GB' : 'IE',
                    'source' => 'Express Freight'
                ]);

                if (strtolower(trim($row['status'])) == 'delivered') {
                    $shipment->setDelivered($datetime, $row['name']);
                }
            }

        } else {
            $this->error('Could not find shipment with carrier tracking number: ' . $row['carrier_tracking_number']);
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
            $row[$field] = (isset($data[$i])) ? trim(preg_replace('/[[:^print:]]/', '', $data[$i])) : null;
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
        // First check for correct number of fields
        if (count($data) != count($this->fields)) {
            $this->error('invalid number of fields detected');
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
