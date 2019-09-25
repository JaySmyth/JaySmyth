<?php

namespace App\Console\Commands;

use App\Shipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UploadShipmentsToExpressFreight extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-shipments-to-express-freight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload shipments to express freight';

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

        $this->fileName = 'ef' . time() . '.csv';
        $this->filePath = '/home/expressfreight/unprocessed/' . $this->fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->shipments = \App\Shipment::whereCarrierId(14)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        // Create the file to upload
        $this->createFile();

        // Update the source flag
        $this->setShipmentsToUploaded();

        // Send notification
        if (count($this->validShipments) > 0) {
            Mail::to('ASteenson@expressfreight.co.uk')->cc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Express Freight File Upload (' . count($this->validShipments) . ' shipments)', 'Please see attached file', $this->filePath));
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
                    $shipment->carrier_consignment_number,
                    strtoupper($shipment->shipment_reference),
                    $shipment->pieces,
                    $shipment->ship_date->format('d-m-Y'),
                    $shipment->recipient_name,
                    $shipment->recipient_company_name,
                    $shipment->recipient_address1,
                    $shipment->recipient_address2,
                    $shipment->recipient_city,
                    $shipment->recipient_state,
                    $shipment->recipient_postcode,
                    $shipment->recipient_country_code
                ];

                // Add to array of valid shipments
                $this->validShipments[] = $shipment->id;
            }

        endforeach;

        // fputcsv
    }

    /**
     * Check that a shipment is valid for express Freight upload.
     *
     * @return boolean
     */
    private function isValid($shipment)
    {
        // Already uploaded
        if (stristr($shipment->source, '.csv')) {
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
