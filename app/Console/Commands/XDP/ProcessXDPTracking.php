<?php

namespace App\Console\Commands\XDP;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessXDPTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-xdp-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads and processes CSV files containing XPD tracking events';

    /**
     * Full path to be processed.
     *
     * @var string
     */
    protected $directory = '/home/xdp/tracking/';


    /**
     * Array of field names.
     *
     * @var array
     */
    protected $fields = [
        'Consignment No',
        'Barcode',
        'Date',
        'Time',
        'Location',
        'Status Type',
        'Status Data',
        'Shipment Reference'
    ];


    /**
     * FTP CONNECTION.
     */
    protected $ftpHost = 'ftp.xdp.co.uk';
    protected $ftpUsername = '0D933A';
    protected $ftpPassword = 'tpCrKS4ZF51KXATg0vvx';
    protected $ftpWorkingDirectory = 'statuses/';


    protected $testMode = false;

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
        $this->downloadFiles();

        $this->info('Checking ' . $this->directory . ' for files to process');

        if ($handle = opendir($this->directory)) {
            while (false !== ($file = readdir($handle))) {
                if (! is_dir($file) && $file != 'archive') {
                    $this->processFile($file);
                    $this->archiveFile($file);
                }
            }

            closedir($handle);
        }
    }

    /**
     * Download files from ftp site.
     *
     * @return void
     */
    private function downloadFiles()
    {
        $this->info('Attempting to download files from remote FTP site');

        $connection = ftp_connect($this->ftpHost);

        // Check connection was made
        if (! $connection) {
            $this->error('Unable to connect to FTP host -> ' . $this->ftpHost);

            return false;
        }

        $loginResult = ftp_login($connection, $this->ftpUsername, $this->ftpPassword);

        if (! $loginResult) {
            $this->error('Unable to login to FTP host -> ' . $this->ftpHost);

            return false;
        }

        $this->info('Connection established to FTP host -> ' . $this->ftpHost);

        ftp_pasv($connection, true);

        // Get the file list for /
        $fileList = ftp_rawlist($connection, $this->ftpWorkingDirectory);

        dd($fileList);

        $this->line(count($fileList) . ' files on remote server');

        foreach ($fileList as $file):

            if ($filename = $this->validateFile($file)) {
                // Open a local file to write to
                $handle = fopen($this->directory . $filename, 'w');

                // Download file
                ftp_fget($connection, $handle, $this->ftpWorkingDirectory . $filename, FTP_ASCII, 0);

                if (! $this->testMode) {
                    if (file_exists($this->directory . $filename)) {
                        $this->line('Attempting to delete file from remote server');

                        if (ftp_delete($connection, $this->ftpWorkingDirectory . $filename)) {
                            $this->info('File deleted from remote server');
                        } else {
                            $this->error('Unable to delete file:' . $this->ftpWorkingDirectory . $filename);
                        }
                    }
                }
            }

        endforeach;

        ftp_close($connection);
    }

    /**
     * Check if a file has been downloaded before and returns filename.
     *
     * @param type $string
     * @return string filename
     */
    protected function validateFile($string)
    {
        // Obtain the file name from the string returned from ftp_rawlist
        $pieces = explode(' ', $string);

        // Last array element
        $filename = end($pieces);

        return $filename;

        // Check the current directory and the archive directory to see if the file has already been downloaded
        if (file_exists($this->directory . $filename) || file_exists($this->directory . 'archive' . '/' . $filename)) {
            $this->error("File $filename has already been downloaded");
            return false;
        }

        return $filename;
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
                $this->processRow($data);
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
    protected function processRow($data)
    {
        // Assign field names to the data array
        $row = $this->assignFieldNames($data);

        // Row passes validation, continue
        if ($this->validateRow($row)) {
            // Load the shipment record
            $shipment = \App\Models\Shipment::where('carrier_tracking_number',
                $row['Consignment No'])->where('carrier_id', 16)->first();

            if ($shipment) {
                $event = $this->getEvent($row, $shipment);

                $tracking = \App\Models\Tracking::firstOrCreate([
                    'message' => $event['message'],
                    'status' => $event['status'],
                    'shipment_id' => $shipment->id
                ])->update($event);

                if ($tracking) {
                    $this->processEvent($event, $shipment);
                }
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
        $row = [];

        $i = 0;
        foreach ($this->fields as $field) {
            $row[$field] = (isset($data[$i])) ? trim(preg_replace('/[[:^print:]]/', '', $data[$i])) : null;
            $i++;
        }

        return $row;
    }

    /**
     * Basic row validation.
     * `
     * @param type $row
     * @return bool
     */
    protected function validateRow($row)
    {
        if (count($row) != count($this->fields)) {
            $this->error('invalid number of fields detected');
            return false;
        }

        return true;
    }

    protected function getEvent($row, $shipment)
    {
        $datetime = gmtToCarbonUtc(Carbon::createFromformat('d/m/Y H:i:s', $row['Date'] . $row['Time']));

        return [
            'status' => $this->getStatus($row),
            'status_detail' => null,
            'city' => $this->getCity($row, $shipment),
            'country_code' => 'GB',
            'postcode' => null,
            'local_datetime' => $datetime,
            'datetime' => $datetime,
            'carrier' => 'XPD',
            'source' => 'XPD',
            'message' => $row['Status Data'],
            'signed_by' => null,
        ];
    }

    /**
     * Determine status from tracking event.
     *
     * @param $activity
     *
     * @return string
     */
    protected function getStatus($row)
    {
        switch ($row['Status Type']) {
            case 'CREATE':
                return 'pre_transit';

            case 'COLLECT':
            case 'EDIT':
                return 'in_transit';

            case 'DELIVERY':
                return 'out_for_delivery';

            case 'DELIVERED':
                return 'delivered';

            case 'EXCEPTION':
                if (stristr($row['Status Data'], 'return') || stristr($row['Status Data'], 'refused')) {
                    return 'return_to_sender';
                }

                if (stristr($row['Status Data'], 'carded')) {
                    return 'available_for_pickup';
                }
                return 'failure';

            default:
                return 'unknown';
        }
    }

    /**
     * Determine location.
     *
     * @param $row
     * @param $shipment
     * @return string
     */
    protected function getCity($row, $shipment)
    {
        if ($row['Status Type'] == 'DELIVERY' || $row['Status Type'] == 'DELIVERED') {
            return $shipment->recipient_city;
        }

        return 'Birmingham';
    }

    /**
     * Perform any necessary actions based upon the current event status.
     *
     * @param type $event
     */
    private function processEvent($event, $shipment)
    {
        $sentProblem = false;

        // Set shipment to received - catches scans missed by IFS
        $this->ensureShipmentReceived($event, $shipment);

        // Update the shipment status (ignore pre-transit and delivered)
        if (! in_array($event['status'], ['pre_transit', 'delivered', 'failure'])) {
            $shipment->setStatus($event['status'], 0, false, false);
        }

        switch ($event['status']) {
            case 'in_transit':
            case 'pre_transit':
            case 'out_for_delivery':
                // do nothing
                break;

            case 'return_to_sender':
                $shipment->alertProblem('Shipment returned to sender', ['s', 'b', 'o', 'd']);
                $sentProblem = true;
                break;

            case 'error':
            case 'failure':
            case 'unknown':
            case 'available_for_pickup':
                $shipment->alertProblem($event['message'], ['s', 'b', 'o', 'd']);
                $sentProblem = true;
                break;

            case 'delivered':
                $shipment->setDelivered($event['datetime'], $event['signed_by']);
                break;

            default:
                // unknown status
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Unknown tracking status (' . $event['status'] . ')',
                    $this->trackingNumber));
                break;
        }

        if (! $sentProblem) {
            $this->alertProblem($event['message']);
        }
    }


    /**
     * Set to received using tracking event.
     *
     * @param type $event
     */
    protected function ensureShipmentReceived($event, $shipment)
    {
        $ignore = ['pre_transit', 'cancelled', 'unknown', 'error', 'failure'];

        if (! in_array($event['status'], $ignore)) {
            // Set to received
            if (! $shipment->received) {
                $shipment->setReceived($event['datetime'], 0, true);
            }

            // Ensure hold flag is removed
            if ($shipment->on_hold) {
                $shipment->on_hold = false;
                $shipment->save();
            }
        }
    }

    /**
     * Move file to archive directory.
     *
     * @param string $file
     * @return bool
     */
    protected function archiveFile($file)
    {
        $this->info("Archiving file $file");

        $originalFile = $this->directory . $file;
        $archiveFile = $this->directory . 'archive' . '/' . $file;

        $this->info("Moving $originalFile to archive");

        if (! file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (copy($originalFile, $archiveFile)) {
            unlink($originalFile);
            $this->info('File archived successfully');
        }
    }
}
