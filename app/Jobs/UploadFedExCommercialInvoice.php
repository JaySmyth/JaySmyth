<?php

namespace App\Jobs;

use App\CarrierAPI\Fedex\FedexAPI;
use App\CarrierAPI\Pdf;
use App\Models\EtdLog;
use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;


class UploadFedExCommercialInvoice implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $shipment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shipmentId)
    {
        $this->shipment = Shipment::find($shipmentId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // ETD Log
        $log = EtdLog::firstOrCreate(['shipment_id' => $this->shipment->id]);
        $log->attempts++;
        $log->save();

        // Filepath on FedEx server
        $filepath = 'C:/FedEx/EDT/'.$this->shipment->carrier_consignment_number.'.pdf';

        // Generate CI and get path
        $tempFile = $this->getDocumentPath();

        if ($tempFile) {
            $log->document_created = true;

            // Upload the PDF to the FedEx server
            $result = $this->upload($this->shipment->carrier_consignment_number.'.pdf', $tempFile);

            if ($result) {
                $log->uploaded = true;

                // Delete the temp file
                unlink($tempFile);

                // Send 049 transaction to FedEx server to link the document with the shipment
                $fedex = new FedexAPI('production');
                $msg = '0,"049"1,"ETD upload"26,"2501"29,"'.$this->shipment->carrier_consignment_number.'"50,"'.$this->shipment->recipient_country_code.'"117,"'.$this->shipment->sender_country_code.'"2805,"Y"2818,"1 "2819,"'.$filepath.'"2820,"Y"7705,"Y"99,""';
                $response = $fedex->transmitMessage($msg);

                $log->response = $response;

                if (stristr($response, '0,"149"10')) {
                    $this->shipment->etd = true;
                    $this->shipment->update();
                    $log->success = true;
                }
            }
        }

        $log->save();
    }


    /**
     * Generates commercial invoice and returns local temp path.
     *
     * @return string
     */
    protected function getDocumentPath()
    {
        // Path for temp PDF file
        $path = storage_path('app/temp/'.$this->shipment->carrier_consignment_number.'.pdf');

        if (file_exists($path)) {
            return $path;
        }

        // createShippingDocs expects a collection of shipments
        $shipments = Shipment::where('id', $this->shipment->id)->get();

        $pdf = new Pdf();
        $base64 = $pdf->createShippingDocs($shipments, 'CUSTOMS');

        // Write to file
        file_put_contents($path, base64_decode($base64));

        if (file_exists($path)) {
            return $path;
        }

        return false;
    }


    /**
     * Upload file to FedEx server.
     *
     * @param  type  $filePath
     * @param  type  $host
     *
     * @return type
     */
    protected function upload($filename, $tempFile)
    {
        $adapter = new SftpAdapter([
            'host' => config('services.fxrs.url'),
            'port' => 22,
            'username' => 'administrator',
            'password' => 'ED$9&hqmK!',
            'root' => '/C:/FedEx/EDT/'
        ]);

        $connection = new Filesystem($adapter);

        if ($connection->has($filename)) {
            $connection->delete($filename);
        }

        return $connection->write($filename, fopen($tempFile, 'r'));
    }

}
