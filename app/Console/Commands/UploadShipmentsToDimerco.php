<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Shipment;
use Illuminate\Support\Facades\Mail;

class UploadShipmentsToDimerco extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-shipments-to-dimerco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload shipments to Dimerco';

    /**
     * Collection of shipments.
     *
     * @var collection
     */
    protected $shipments;

    /**
     * File path.
     *
     * @var string
     */
    protected $fileName;

    /**
     * File path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * Array of valid shipment ids.
     */
    protected $validShipments = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fileName = 'dim' . time() . '.txt';
        $this->filePath = '/home/dimerco/unprocessed/' . $this->fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->shipments = \App\Shipment::whereCarrierId(13)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        // Create the file to upload
        $this->createFile();

        // Update the souce flag
        $this->setShipmentsToUploaded();

        if (count($this->validShipments) > 0) {
            // Notify IT
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Dimerco File Upload (' . count($this->validShipments) . ' orders)', 'Please see attached file', $this->filePath));
        }
    }

    /**
     * Create a file in the temp directory.
     *
     * @param type $shipment
     */
    private function createFile()
    {
        if (file_exists($this->filePath)) {
            return true;
        }

        foreach ($this->shipments as $shipment) :

            if ($this->isValid($shipment)) {

                $line = [
                    $shipment->consignment_number,
                    strtoupper($shipment->shipment_reference),
                    'IFS',
                    strtoupper($shipment->instructions),
                    $shipment->recipient_name,
                    $shipment->recipient_company_name,
                    $shipment->recipient_address1,
                    $shipment->recipient_address2,
                    null,
                    $shipment->recipient_city,
                    $shipment->recipient_state,
                    $shipment->recipient_postcode,
                    $shipment->recipient_country_code,
                    $shipment->recipient_telephone,
                    'BABOCUSH',
                    $shipment->pieces
                ];

                // Add the line to the text file
                file_put_contents($this->filePath, implode('|', $line) . ";\n", FILE_APPEND | LOCK_EX);

                // Add to array of valid shipments
                $this->validShipments[] = $shipment->id;
            }

        endforeach;
    }

    /**
     * Check that a shipment is valid for Primary Freight upload.
     *
     * @return boolean
     */
    private function isValid($shipment)
    {
        // Not babocush
        if ($shipment->company_id != 874) {
            return false;
        }

        // Already uploaded
        if (stristr($shipment->source, '.txt')) {
            return false;
        }

        // Not China
        if (!in_array(strtoupper($shipment->recipient_country_code), ['CN'])) {
            return false;
        }

        return true;
    }

    /**
     * Update shipment records with an identifier to show that they have been uploaded to PF.
     */
    private function setShipmentsToUploaded()
    {
        // set the source field on all shipments to that of the filename
        Shipment::whereIn('id', $this->validShipments)->update([
            'received_sent' => 1,
            'source' => $this->fileName,
        ]);
    }

}
