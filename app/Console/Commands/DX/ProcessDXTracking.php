<?php

namespace App\Console\Commands\DX;

use App\Models\ProblemEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessDXTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-dx-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads and processes CSV files containing DX tracking events';

    /**
     * Full path to be processed.
     *
     * @var string
     */
    protected $directory = '/home/dx/tracking/';


    /**
     * Array of field names.
     *
     * @var array
     */
    protected $fields = [
        'shipment_reference', 'carrier_tracking_number', 'code', 'date'
    ];

    protected $testMode = true;

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

        $this->info('Checking '.$this->directory.' for files to process');

        if ($handle = opendir($this->directory)) {
            while (false !== ($file = readdir($handle))) {
                if (! is_dir($file) && $file != 'archive') {
                    $this->processFile($file);
                    //$this->archiveFile($file);
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
        $this->info('Attempting to download files from remote SFTP site');

        $files = Storage::disk('dxTracking')->files('DXOutboundCollect');

        foreach ($files as $file) {

            $this->info("Downloading file $file");

            // Read the contents of the txt file
            $contents = Storage::disk('dxTracking')->get($file);

            // Save to local directory
            file_put_contents($this->directory.basename($file), $contents);

            if (! $this->testMode) {
                if (file_exists($this->directory.basename($file))) {
                    $this->line('Attempting to delete file from remote server');
                    Storage::disk('dxTracking')->delete($file);
                }
            }
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

        if (($handle = fopen($this->directory.$file, 'r')) !== false) {
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
     * @param  type  $rowNumber
     * @param  type  $data
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
            $shipment = \App\Models\Shipment::where('carrier_tracking_number', $row['carrier_tracking_number'])->where('carrier_id', 17)->first();

            if ($shipment && $row['carrier_tracking_number'] == '1566345465') {
                $event = $this->getEvent($row, $shipment);

                if ($event) {
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
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param  type  $data
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
     *
     * @param  type  $row
     *
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
        $dxStatus = DB::table('dx_statuses')->where('code', $row['code'])->first();

        if ($dxStatus) {
            return [
                'status' => $dxStatus->status,
                'status_detail' => null,
                'city' => ($dxStatus->status == 'delivered') ? $shipment->recipient_city : null,
                'country_code' => 'GB',
                'postcode' => null,
                'local_datetime' => gmtToCarbonUtc(Carbon::createFromformat('d/m/YH:i:s', $row['date'])),
                'datetime' => gmtToCarbonUtc(Carbon::createFromformat('d/m/YH:i:s', $row['date'])),
                'carrier' => 'DX',
                'source' => 'DX',
                'message' => $dxStatus->description,
                'signed_by' => null,
            ];
        }

        return false;
    }


    /**
     * Perform any necessary actions based upon the current event status.
     *
     * @param  type  $event
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
            case 'pre_transit':
            case 'in_transit':
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
                if (strlen($event['message']) > 0) {
                    $shipment->alertProblem($event['message'], ['s', 'b', 'o', 'd']);
                    $sentProblem = true;
                }
                break;

            case 'delivered':
                $shipment->setDelivered($event['datetime'], $event['signed_by']);
                break;

            default:
                // unknown status
                break;
        }

        if (! $sentProblem) {
            $this->alertProblem($event['message'], $shipment);
        }
    }


    /**
     * Set to received using tracking event.
     *
     * @param  type  $event
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
     * Check if we have received a "problem" event that we need to send email for.
     *
     * @param  type  $message
     */
    private function alertProblem($message, $shipment)
    {
        $problemEvents = ProblemEvent::all();

        foreach ($problemEvents as $problemEvent) {
            $relevance = explode(',', $problemEvent->relevance);

            if (stristr($message, $problemEvent->event)) {
                $shipment->alertProblem($problemEvent->event, $relevance);
            }
        }
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
        $archiveFile = $this->directory.'archive'.'/'.$file;

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
