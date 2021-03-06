<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use App\Models\Tracking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class UploadFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload files to remote hosts';

    /**
     * File upload log.
     *
     * @var array
     */
    protected $log;

    /**
     * An array of IDs that were sent to the host.
     *
     * @var type
     */
    protected $idsSent;

    /**
     * The model that we are working with.
     * `
     * @var string
     */
    protected $model;

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
        $queue = [];

        $fileUploads = \App\Models\FileUpload::whereEnabled(1)->get();

        /*
         * Build a queue of uploads that are scheduled for processing now.
         * Required so that uploads do not miss their scheduled processing window
         * as it may take some time to generate large files.
         */
        foreach ($fileUploads as $fileUpload):
            if ($fileUpload->isScheduled()) {
                $queue[] = $fileUpload;
            }
        endforeach;

        /*
         * Generate and upload the files.
         */
        foreach ($queue as $fileUpload) :

            // Clear the log
            $this->log = [];

            // Clear the IDs
            $this->idsSent = [];

            // Write the data to be uploaded to a temp file
            if (! $tempFile = $this->writeFile($fileUpload)) {
                // If no data then skip gracefully to next upload
                if (is_null($tempFile)) {
                    $fileUpload->setNextUpload();
                    continue;
                }

                // Update logs, timestamps and send error email if required
                $this->finishJob($fileUpload);
                continue;
            }

            $this->log('Created temp file: '.$tempFile);

            // Connect to the remote host
            if (! $connection = $this->connect($fileUpload->fileUploadHost)) {
                $this->log('Failed to connect', 'error');
                $this->finishJob($fileUpload);
                continue;
            }

            // Generate a filename for the file to be uploaded
            $filename = $this->getFilename($fileUpload);

            // Upload the file to the remote host
            $result = $this->upload($filename, $tempFile, $connection);

            // Log the results to database and set next upload time
            $this->finishJob($fileUpload, $result, $tempFile);

        endforeach;
    }

    /**
     * Generate the file to upload and save to local temp directory.
     *
     * @param  type  $fileUpload
     *
     * @return string
     */
    protected function writeFile($fileUpload)
    {
        $data = false;
        $method = 'get'.ucfirst($fileUpload->type);

        if (! method_exists($this, $method)) {
            $this->log('Invalid type - '.$fileUpload->type, 'error');

            return false;
        }

        // Retreive the records
        $records = $this->$method($fileUpload->company_id);

        if (! $records->isEmpty()) {
            // Build a list of ids we are going to send
            $this->idsSent = $records->pluck('id')->toArray();

            // The results as export array
            $data = $this->getExportArray($method, $records, 'Europe/London', $fileUpload->verbose);
        }

        if (is_array($data) && count($data) > 0) {
            $this->log(count($data).' records to upload');

            // Add a heading row if required
            if ($fileUpload->fileUploadHost->heading_row) {
                $headings = array_keys($data[0]);
                array_unshift($data, $headings);
            }

            return writeCsv(
                storage_path().'/app/temp/'.time().Str::random(3).'.csv',
                $data,
                'w',
                $fileUpload->fileUploadHost->csv_delimiter
            );
        }

        $this->log('No data to transfer', 'error');
    }

    /**
     * Output messages and save to array.
     *
     * @param  string  $message
     * @param  string  $method
     */
    protected function log($message, $method = 'info')
    {
        $this->log[] = $message;
        $this->$method($message);
    }

    /**
     * Get a formatted array of results.
     *
     * @param $method
     * @param $records
     * @param  string  $timezone
     * @param  bool  $verbose
     *
     * @return array
     */
    protected function getExportArray($method, $records, $timezone = 'Europe/London', $verbose = true)
    {
        if ($method == 'getTracking' || $method == 'getException') {
            return $this->getTrackingExportArray($records, $timezone);
        }

        return $this->getShipmentExportArray($records, $timezone, $verbose);
    }

    /**
     * Get a shipment array for excel export.
     *
     * @param  type  $shipments
     *
     * @return type
     */
    protected function getTrackingExportArray($trackingEvents, $timezone = 'Europe/London')
    {
        $data = [];

        foreach ($trackingEvents as $tracking) :

            $data[] = [
                'Consignment Number' => $tracking->shipment->consignment_number,
                'Carrier Consignment Number' => $tracking->shipment->carrier_consignment_number,
                'Shipment Reference' => $tracking->shipment->shipment_reference,
                'Recipient' => ($tracking->shipment->company_name) ? $tracking->shipment->company_name.', '.$tracking->shipment->recipient_name : $tracking->shipment->recipient_name,
                'City' => $tracking->shipment->recipient_city,
                'Pieces' => $tracking->shipment->pieces,
                'Weight' => $tracking->shipment->weight.strtoupper($tracking->shipment->weight_uom),
                'Volume' => $tracking->shipment->volumetric_weight,
                'Ship Date' => $tracking->shipment->ship_date->timezone($timezone)->format('d-m-Y'),
                'Service' => $tracking->shipment->service->code,
                'Status' => $tracking->status,
                'Status Detail' => $tracking->status_detail,
                'Message' => $tracking->message,
                'Date/Time' => ($tracking->datetime) ? $tracking->datetime->timezone($timezone)->format('d-m-Y H:i') : null
            ];

        endforeach;

        return $data;
    }

    /**
     * Get a shipment array for excel export.
     *
     * @param  type  $shipments
     *
     * @return type
     */
    protected function getShipmentExportArray($shipments, $timezone = 'Europe/London', $verbose = true)
    {
        $data = [];

        foreach ($shipments as $shipment) :

            if ($verbose) {
                $data[] = [
                    'Consignment Number' => $shipment->consignment_number,
                    'Carrier Consignment Number' => $shipment->carrier_consignment_number,
                    'Shipment Reference' => $shipment->shipment_reference,
                    'Pieces' => $shipment->pieces,
                    'Weight' => $shipment->weight.strtoupper($shipment->weight_uom),
                    'Volume' => $shipment->volumetric_weight,
                    'Sender Name' => $shipment->sender_name,
                    'Sender Company Name' => $shipment->sender_company_name,
                    'Sender Address 1' => $shipment->sender_address1,
                    'Sender Address 2' => $shipment->sender_address2,
                    'Sender Address 3' => $shipment->sender_address3,
                    'Sender City' => $shipment->sender_city,
                    'Sender State' => $shipment->sender_state,
                    'Sender Postcode' => $shipment->sender_postcode,
                    'Sender Country' => $shipment->sender_country_code,
                    'Sender Telephone' => $shipment->sender_telephone,
                    'Sender Email' => $shipment->sender_email,
                    'Recipient Name' => $shipment->recipient_name,
                    'Recipient Company_name' => $shipment->recipient_company_name,
                    'Recipient Address 1' => $shipment->recipient_address1,
                    'Recipient Address 2' => $shipment->recipient_address2,
                    'Recipient Address 3' => $shipment->recipient_address3,
                    'Recipient City' => $shipment->recipient_city,
                    'Recipient State' => $shipment->recipient_state,
                    'Recipient Postcode' => $shipment->recipient_postcode,
                    'Recipient Country' => $shipment->recipient_country_code,
                    'Recipient Telephone' => $shipment->recipient_telephone,
                    'Recipient Email' => $shipment->recipient_email,
                    'Date Created' => $shipment->created_at->timezone($timezone)->format('d-m-Y'),
                    'Ship Date' => $shipment->ship_date->timezone($timezone)->format('d-m-Y'),
                    'Service' => $shipment->service->code,
                    'Time In Transit' => $shipment->timeInTransit,
                    'Status' => $shipment->status->name,
                    'POD Signature' => $shipment->pod_signature,
                    'Delivery Date' => $shipment->getDeliveryDate('d-m-Y H:i'),
                    'Tracking' => url('/tracking/'.$shipment->token),
                ];
            } else {
                $data[] = [
                    'Consignment Number' => $shipment->consignment_number,
                    'Carrier Consignment Number' => $shipment->carrier_consignment_number,
                    'Shipment Reference' => $shipment->shipment_reference,
                    'Pieces' => $shipment->pieces,
                    'Weight' => $shipment->weight.strtoupper($shipment->weight_uom),
                    'Volume' => $shipment->volumetric_weight,
                    'Date Created' => $shipment->created_at->timezone($timezone)->format('d-m-Y'),
                    'Ship Date' => $shipment->ship_date->timezone($timezone)->format('d-m-Y'),
                    'Service' => $shipment->service->code,
                    'Time In Transit' => $shipment->timeInTransit,
                    'Status' => $shipment->status->name,
                    'POD Signature' => $shipment->pod_signature,
                    'Delivery Date' => $shipment->getDeliveryDate('d-m-Y H:i'),
                    'Tracking' => url('/tracking/'.$shipment->token),
                ];
            }

        endforeach;

        return $data;
    }

    /**
     * Log the upload results, send failure email or update last_upload timestamp.
     *
     * @param  type  $param
     */
    protected function finishJob($fileUpload, $uploaded = false, $tempFile = false)
    {
        $fileUpload->last_status = $uploaded;
        $fileUpload->update();

        if (! $uploaded) {
            $fileUpload->retry(10);
            $this->log('*** Upload will be attempted again in 10 minutes ***');
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError(
                'File Upload Failed',
                $this->log,
                $tempFile
            ));
        }

        $log = \App\Models\FileUploadLog::create([
            'output' => implode('<br>', $this->log),
            'uploaded' => $uploaded,
            'file_upload_id' => $fileUpload->id,
        ]);

        if ($uploaded) {
            $fileUpload->last_upload = Carbon::now();
            $fileUpload->setNextUpload();

            // Update "sent" flag on records after successful transfer
            $this->model->whereIn('id', $this->idsSent)->update([$fileUpload->type.'_sent' => 1]);
        }
    }

    /**
     * Upload file to host.
     *
     * @param  type  $filePath
     * @param  type  $host
     *
     * @return type
     */
    protected function connect($host)
    {
        $this->log('Connecting to remote host: '.$host->host.':'.$host->port);

        switch ($host->sftp) {
            case 1:
                $adapter = new SftpAdapter([
                    'host' => $host->host,
                    'port' => $host->port,
                    'username' => $host->username,
                    'password' => $host->password,
                    'privateKey' => $host->private_key,
                    'root' => $host->directory,
                    'timeout' => $host->timeout,
                    'directoryPerm' => $host->direcory_permissions,
                ]);

                try {
                    $filesystem = new Filesystem($adapter);
                } catch (\Exception $exc) {
                    $this->log($exc->getMessage(), 'error');

                    return false;
                }

                return $filesystem;

            default:

                $connection = ftp_connect($host->host);
                $loginResult = ftp_login($connection, $host->username, $host->password);

                // Turn on passive mode
                if ($host->passive) {
                    ftp_pasv($connection, true);
                }

                // Check connection was made
                if ((! $connection) || (! $loginResult)) {
                    $this->log('Unable to connect to FTP host -> '.$host->host, 'error');

                    return false;
                }

                return $connection;
        }
    }

    /**
     * Generate a filename.
     *
     * @param  type  $fileUpload
     *
     * @return string
     */
    protected function getFilename($fileUpload)
    {
        $fileName = $fileUpload->type.'_'.date('d_m_y', time()).'_'.time().$fileUpload->id.'.csv';

        if ($fileUpload->upload_directory) {
            $fileName = $fileUpload->upload_directory.'/'.$fileName;
        }

        return $fileName;
    }

    /**
     * Upload file to host.
     *
     * @param  type  $filePath
     * @param  type  $host
     *
     * @return type
     */
    protected function upload($filename, $tempFile, $connection)
    {
        $this->log("Attempting to upload $filename");

        if ($connection instanceof \League\Flysystem\Filesystem) {
            try {
                $result = $connection->write($filename, fopen($tempFile, 'r'));
                $this->log("UPLOAD SUCCESS: $filename");
            } catch (\Exception $exc) {
                $this->log($exc->getMessage(), 'error');

                return false;
            }

            return $result;
        }


        /*
        * Standard FTP.
        */
        try {
            $result = ftp_put($connection, $filename, $tempFile, FTP_BINARY);
            ftp_close($connection);
            $this->log("UPLOAD SUCCESS: $filename");
        } catch (\Exception $exc) {
            $this->log($exc->getMessage(), 'error');

            return false;
        }

        return $result;
    }

    /**
     * POD - get an array of shipment data. Shipments delivered but POD not sent.
     *
     * @param  type  $criteria
     *
     * @return array
     */
    protected function getPod($companyId)
    {
        $this->model = new Shipment();

        return Shipment::whereDelivered(1)->wherePodSent(0)->whereCompanyId($companyId)->orderBy('ship_date', 'desc')->get();
    }

    /**
     * POD - get an array of shipment data. Shipments delivered but POD not sent.
     *
     * @param  type  $criteria
     *
     * @return array
     */
    protected function getReceived($companyId)
    {
        $this->model = new Shipment();

        return Shipment::whereReceived(1)->whereReceivedSent(0)->whereCompanyId($companyId)->orderBy(
            'ship_date',
            'desc'
        )->get();
    }

    /**
     * Shipments created (ignore saved/cancelled shipments).
     *
     * @param  type  $criteria
     *
     * @return array
     */
    protected function getCreated($companyId)
    {
        $this->model = new Shipment();

        return Shipment::whereNotIn(
            'status_id',
            [1, 7]
        )->whereCreatedSent(0)->whereCompanyId($companyId)->orderBy('ship_date', 'desc')->get();
    }

    /**
     * Tracking events that have not been sent.
     *
     * @param $companyId
     *
     * @return mixed
     */
    protected function getTracking($companyId)
    {
        $this->model = new Tracking();

        return Tracking::select('tracking.*')
            ->where('datetime', '>=', now()->subMonth())
            ->whereTrackingSent(0)
            ->where('shipments.company_id', $companyId)
            ->join('shipments', 'tracking.shipment_id', '=', 'shipments.id')
            ->orderBy('shipment_id')->orderBy('id')->get();
    }

    /**
     * Exceptions that have not been sent.
     *
     * @param $companyId
     *
     * @return mixed
     */
    protected function getException($companyId)
    {
        $this->model = new Tracking();

        return Tracking::select('tracking.*')
            ->where('datetime', '>=', now()->subWeek())
            ->whereExceptionSent(0)
            ->whereStatusDetail('delivery_exception')
            ->where('shipments.company_id', $companyId)
            ->join('shipments', 'tracking.shipment_id', '=', 'shipments.id')
            ->orderBy('shipment_id')->orderBy('id')->get();
    }
}
