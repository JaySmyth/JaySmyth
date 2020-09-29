<?php

namespace App\Console\Commands\Transend;

use App\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transend:process-files {--testMode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the files that have been uploaded from Transend and update the transport jobs accordingly';

    /**
     * The SFTP directory that transend upload files.
     *
     * @var string
     */
    protected $directory = '/home/transend/exports/';

    /**
     * Folder that processed files are to be archived.
     *
     * @var string
     */
    protected $archiveDirectory = 'archive';

    /**
     * Transport job.
     *
     * @var type
     */
    protected $transportJob;

    /**
     * Transend code.
     *
     * @var type
     */
    protected $transendCode;

    /**
     * @var type
     */
    protected $shipment;

    /**
     * Order of fields in Transend file.
     *
     * @var array
     */
    protected $fields = [
        0 => [
            'RecordType',
            'JobRef1',
            'JobRef2',
            'JobRef3',
            'JobRef4',
            'JobTypeCode',
            'RouteNumber',
            'RouteDate',
            'VehicleReg',
            'DriverName',
            'CompletedTime',
            'TypedName',
            'Signature',
            'Barcode',
            'Description',
            'Quantity',
            'UnitMeasure',
            'TickOrScan',
            'ExceptionReasonCode',
            'ExceptionQty',
        ],
        1 => [
            'RecordType',
            'JobRef1',
            'JobRef2',
            'JobRef3',
            'JobRef4',
            'JobTypeCode',
            'RouteNumber',
            'RouteDate',
            'VehicleReg',
            'DriverName',
            'CompletedTime',
            'ExceptionReasonCode',
        ],
    ];

    /**
     * @var type
     */
    protected $testMode = false;

    /**
     * FTP CONNECTION.
     */
    protected $ftpHost = '34.242.17.212';
    protected $ftpPort = '21';
    protected $ftpUsername = 'ifs';
    protected $ftpPassword = 'F1L3UpL0Ad5$';
    protected $ftpWorkingDirectory = 'Live/Confirmations/';

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
        if ($this->option('testMode')) {
            $this->info('** TEST MODE **');
            $this->testMode = true;
        }

        // Download the files from remote FTP site
        $this->downloadFiles();

        $this->line('Checking '.$this->directory.' for CSV files to process');

        if ($handle = opendir($this->directory)) {
            while (false !== ($file = readdir($handle))) {
                if (! is_dir($file) && substr($file, -4) == '.csv') {
                    if (file_exists($this->directory.$file)) {
                        $lockFile = $file.'.LCK';

                        // Rename the file - give it a .LCK extension
                        rename($this->directory.$file, $this->directory.$lockFile);

                        // Process the lock file
                        $this->processFile($lockFile);

                        if (! $this->testMode) {
                            $this->archiveFile($lockFile);
                        }
                    } else {
                        $this->info('File processed by another task!');
                    }
                }
            }

            closedir($handle);
        }

        $this->info('Finished processing files');
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
            $this->error('Unable to connect to FTP host -> '.$this->ftpHost);

            return false;
        }

        $loginResult = ftp_login($connection, $this->ftpUsername, $this->ftpPassword);

        if (! $loginResult) {
            $this->error('Unable to login to FTP host -> '.$this->ftpHost);

            return false;
        }

        $this->info('Connection established to FTP host -> '.$this->ftpHost);

        /*
         * FTP BETWEEN TWO EC2 INSTANCES REQUIRES PASSIVE AND IP IGNORE
         *
         */
        ftp_set_option($connection, FTP_USEPASVADDRESS, false);
        ftp_pasv($connection, true);

        // Get the file list for /
        $fileList = ftp_rawlist($connection, $this->ftpWorkingDirectory);

        $this->line(count($fileList).' files on remote server');

        foreach ($fileList as $file):

            if ($filename = $this->getFilename($file)) {
                // Open a local file to write to
                $handle = fopen($this->directory.'/'.$filename, 'w');

                // Download file
                ftp_fget($connection, $handle, $this->ftpWorkingDirectory.$filename, FTP_ASCII, 0);

                if (! $this->testMode) {
                    if (file_exists($this->directory.'/'.$filename)) {
                        $this->line('Attempting to delete file from remote server');

                        if (ftp_delete($connection, $this->ftpWorkingDirectory.$filename)) {
                            $this->info('File deleted from remote server');
                        } else {
                            $this->error('Unable to delete file:'.$this->ftpWorkingDirectory.$filename);
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
     * @param  type  $string
     *
     * @return string filename
     */
    private function getFilename($string)
    {
        // Obtain the file name from the string returned from ftp_rawlist
        $pieces = explode(' ', $string);

        // Last array element
        return end($pieces);
    }

    /**
     * Read the file contents and insert records.
     *
     * @param  type  $file
     */
    protected function processFile($file)
    {
        $this->line("Processing lock file: $file");

        if (($handle = fopen($this->directory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                $row = $this->assignFieldNames($data);

                $this->transportJob = (! empty($row['JobRef1'])) ? \App\Models\TransportJob::whereNumber($row['JobRef1'])->first() : false;
                $this->transendCode = (! empty($row['ExceptionReasonCode'])) ? \App\Models\TransendCode::whereCode($row['ExceptionReasonCode'])->first() : false;
                $this->shipment     = false;

                if ($this->transportJob) {
                    $this->shipment = ($this->transportJob->shipment) ? $this->transportJob->shipment : false;

                    // Log the transaction
                    $this->transportJob->log(
                        'Transend transaction received',
                        Str::before($file, '.LCK'),
                        $row
                    );
                }

                $datetime = $this->parseDateTime($row['CompletedTime']);

                if ($row['RecordType'] == 1) {
                    $this->processRecordType1($row, $datetime);
                } else {
                    $this->handleTransendCode($row['ExceptionReasonCode'], $datetime);
                }
            }
        }

        fclose($handle);
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function assignFieldNames($data)
    {
        $numberOfFields = count($data);

        $fields = ($numberOfFields == 12) ? $this->fields[1] : $this->fields[0];

        $i = 0;
        foreach ($fields as $field) {
            $row[$field] = (isset($data[$i]) && $data[$i] != '?') ? trim($data[$i]) : null;
            $i++;
        }

        return $row;
    }

    /**
     * Parse the datetime string to carbon UTC.
     *
     * @param  type  $datetime
     *
     * @return type
     */
    protected function parseDateTime($datetime)
    {
        if (empty($datetime)) {
            $datetime = \Carbon\Carbon::now();
        } else {
            $datetime = str_replace('/', '.', $datetime);
            $datetime = \Carbon\Carbon::createFromTimestamp(strtotime($datetime))->toDateTimeString();
            $datetime = gmtToCarbonUtc($datetime);
        }

        return $datetime;
    }

    /**
     * Completed job - one row per barcode.
     *
     * @param  type  $row
     *
     * @return
     */
    protected function processRecordType1($row, $datetime)
    {
        $package = false;

        if ($this->shipment) {
            $package = $this->shipment->packages->where('barcode', $row['Barcode'])->first();
        } elseif (! empty($row['Barcode'])) {
            $package = Package::whereBarcode($row['Barcode'])->first();

            if ($package) {
                $this->shipment = $package->shipment;
                $this->info('Found shipment record from package barcode');
            } else {
                $this->error('Package not found: '.$row['Barcode']);
            }
        }

        if ($package) {
            // Process the different job types
            switch ($row['JobTypeCode']) {
                case 'LOADJOB':

                    if (! $this->testMode && $row['ExceptionReasonCode'] != 'LOADSHORT') {
                        // Packaged loaded to vehicle
                        $package->setLoaded($datetime);

                        // Insert a tracking event to show package loaded
                        $this->shipment->addTracking(
                            $this->shipment->status->code,
                            $datetime,
                            0,
                            'Package '.$package->index.' loaded onto vehicle'
                        );

                        // NI delivery loaded - set the shipment status to "out for delivery"
                        if (in_array(
                            strtoupper($this->shipment->service->carrier_code),
                            ['NI24', 'NI48']
                        ) && $this->shipment->isActive()) {
                            $this->shipment->setStatus('out_for_delivery', 0, $datetime->addMinutes(5));
                        }

                        $this->info($this->shipment->consignment_number.': '.'Package '.$package->index.' loaded onto vehicle');

                        $package->shipment->log('Package '.$package->index.' loaded to route by TranSend LOADJOB: '.$datetime->toDateTimeString());
                    }

                    break;

                case 'UNLOADJOB':

                    if (! $this->testMode) {
                        if ($package->received && $package->loaded) {
                            $package->date_received = $package->date_loaded->subMinutes(5);
                            $package->save();
                        }

                        if ($row['ExceptionReasonCode'] != 'UNLODSHORT') {
                            // Mark the package as received
                            $package->setReceived($datetime);

                            $this->info($this->shipment->consignment_number.': '.'Package '.$package->index.' received');

                            $package->shipment->log('Package '.$package->index.' marked as received by TranSend UNLOADJOB: '.$datetime->toDateTimeString());
                        }
                    }
                    break;

                default:

                    if ($this->canBeCollected($package, $row)) {
                        // Mark the package as collected
                        $package->setCollected($datetime);

                        $this->shipment->addTracking(
                            $this->shipment->status->code,
                            $datetime,
                            0,
                            'Package '.$package->index.' collected',
                            'shipper'
                        );

                        $this->info($this->shipment->consignment_number.': '.'Package '.$package->index.' collected');

                        $package->shipment->log('Package '.$package->index.' marked as collected by TranSend: '.$datetime->toDateTimeString());
                    }

                    break;
            }
        }

        if ($this->transportJob) {
            // We have a signature, job not completed, not a loadjob, not a shortage, not package collected
            if ($this->canBeClosedOff($row)) {
                $this->transportJob->close(
                    $datetime,
                    $row['TypedName'],
                    0,
                    $this->getSignatureImageUrl($row['Signature'])
                );

                $this->info($this->transportJob->number.' CLOSED!');
            }

            if (! empty($row['ExceptionReasonCode'])) {
                $this->handleTransendCode($row['ExceptionReasonCode'], $datetime);
            }
        }
    }

    /**
     * Determine if a package can be marked as collected.
     *
     * @param  type  $package
     *
     * @return bool
     */
    protected function canBeCollected($package, $row)
    {
        if ($this->testMode) {
            return false;
        }

        if ($row['JobTypeCode'] == 'BULKCOL') {
            return false;
        }

        if ($package->collected || $package->received) {
            return false;
        }

        if ($this->transportJob) {
            if ($this->transportJob->type != 'c') {
                return false;
            }
        }

        if ($this->transendCode) {
            if ($this->transendCode->resend || $this->transendCode->hold || $this->transendCode->no_collection) {
                return false;
            }
        }

        if ($this->shipment->company->bulk_collections) {
            return false;
        }

        return true;
    }

    /**
     * Determine if a transport job can be closed.
     *
     * @param  type  $row
     *
     * @return bool
     */
    protected function canBeClosedOff($row)
    {
        if ($this->testMode) {
            return false;
        }

        if (! $this->transportJob) {
            return false;
        }

        if ($this->transendCode) {
            if ($this->transendCode->resend || $this->transendCode->hold) {
                return false;
            }
        }

        if ($this->transportJob->type == 'd' && $row['JobTypeCode'] == 'UNLOADJOB') {
            return false;
        }

        if ($row['ExceptionReasonCode'] == 'UNLODSHORT') {
            return false;
        }

        if (! empty($row['TypedName']) && ! $this->transportJob->completed && $row['JobTypeCode'] != 'LOADJOB') {
            return true;
        }
    }

    /**
     * Image url.
     *
     * @param  type  $signature
     *
     * @return type
     */
    protected function getSignatureImageUrl($signature)
    {
        if (strlen($signature) > 0) {
            return 'https://tsapp.ifsgroup.com/images/signatures/'.$signature;
        }
    }

    /**
     * Lookup transend code and handle accordingly.
     *
     * @param  type  $code
     * @param  type  $datetime
     *
     * @return void
     */
    protected function handleTransendCode($code, $datetime)
    {
        if (! $this->transportJob) {
            return false;
        }

        if ($this->transendCode) {
            if ($this->transendCode->notify_department) {
                // Notify relevant department that an exception has been received
                $subject = 'Possible issue with '.verboseCollectionDelivery($this->transportJob->type).' '.$this->transportJob->number.' / '.$this->transportJob->scs_job_number.' ('.$this->transportJob->from_company_name.' > '.$this->transportJob->to_company_name.')';
                Mail::to($this->transportJob->department->email)->send(new \App\Mail\GenericError(
                    $subject,
                    'The IFS driver allocated job '.$this->transportJob->number.' / '.$this->transportJob->scs_job_number.' has reported: '.$this->transendCode->description.'. Please investigate further.'
                ));
            }

            // Update the transport job to "not sent"
            if ($this->transendCode->resend) {
                if (! $this->testMode) {
                    $this->transportJob->resend($this->transendCode->resend_same_day);
                }

                $this->line('Job resent: '.$this->transportJob->number);

                if ($this->transendCode->hold) {
                    if (! $this->testMode) {
                        $this->transportJob->setTransendRoute('HOLD');
                    }

                    $this->line('Job added to HOLD route: '.$this->transportJob->number);
                }
            }

            // Add tracking event if applicable
            if ($this->shipment && $this->transendCode->add_tracking_event) {
                $scanLocation = ($this->transportJob->type == 'c') ? 'shipper' : 'destination';

                if (! $this->testMode) {
                    $this->shipment->addTracking(
                        $this->shipment->status->code,
                        $datetime,
                        0,
                        $this->transendCode->description,
                        $scanLocation
                    );
                }

                $this->line('Tracking event added to shipment '.$this->shipment->consignment_number.': '.$this->transendCode->description);
            }

            return;
        } else {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError(
                'Unknown transend exception code - '.$code,
                $code.' not defined in transend_codes table'
            ));
        }

        $this->error('Unable to find transend code: '.$code);
    }

    /**
     * Move file to archive directory.
     *
     * @param  string  $file
     *
     * @return bool
     */
    protected function archiveFile($file)
    {
        $this->info("Archiving file $file");

        $originalFile = $this->directory.$file;
        $archiveFile  = $this->directory.$this->archiveDirectory.'/'.Str::before($file, '.LCK');

        $this->info("Moving $originalFile to archive");

        if (! file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (! file_exists($archiveFile)) {
            if (copy($originalFile, $archiveFile)) {
                unlink($originalFile);
                $this->info('File archived successfully');
            }
        } else {
            $this->error("Problem archiving $archiveFile  - already exists");
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError(
                'Error archiving transend export file',
                "Problem archiving $archiveFile  - already exists"
            ));
        }
    }
}
