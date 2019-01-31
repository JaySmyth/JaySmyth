<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Shipment;
use Illuminate\Support\Facades\Mail;

class UploadShipmentsToPrimaryFreight extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-shipments-to-primary-freight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload shipments to primary freight';

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

        $this->fileName = 'pf' . time() . '.txt';
        $this->filePath = '/home/primaryfreight/unprocessed/' . $this->fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->shipments = \App\Shipment::whereCarrierId(12)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        // Create the file to upload
        $this->createFile();

        // Update the souce flag
        $this->setShipmentsToUploaded();

        if (count($this->validShipments) > 0) {
            // Notify IT
            Mail::to('njwhse@primaryfreight.com')->cc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Primary Freight File Upload (' . count($this->validShipments) . ' orders)', 'Please see attached file', $this->filePath));
        } else {
            // Nothing uploaded, notify IT
            //Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Primary Freight - zero shipments uploaded', 'No shipments were uploaded. Please check.'));
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
                    $shipment->consignment_number, // ReferenceNumber
                    strtoupper($shipment->shipment_reference), // PurchaseOrderNumber                    
                    'FedEx',
                    'Ground', // ShipService
                    'third party', // ShipBilling
                    631510906, // ShipAccount
                    $shipment->ship_date->format('m/d/Y'), // ShipDate
                    null, // CancelDate
                    null, // Notes
                    $shipment->recipient_name, // ShipToName
                    $shipment->recipient_company_name ?: 'IFS Group', // ShipToCompany
                    $shipment->recipient_address1, // ShipToAddress1
                    $shipment->recipient_address2, // ShipToAddress2
                    $shipment->recipient_city, // ShipToCity
                    $shipment->recipient_state, // ShipToState
                    $shipment->recipient_postcode, // ShipToZip 11580
                    $shipment->recipient_country_code, // ShipToCountry
                    $shipment->recipient_telephone, // ShipToPhone
                    null, // ShipToFax
                    $shipment->recipient_email, // ShipToEmail
                    null, // ShipToCustomerID
                    null, // ShipToDeptNumber
                    null, // RetailerID
                    'Babocush', // ItemNumber
                    $shipment->pieces, // Quantity
                    null, // UseCOD
                    null, // UseInsurance
                    null, // Saved Elements
                    null, // Order Item Saved Elements
                    null // Carrier Notes
                ];

                // Add the line to the tab delim text file
                file_put_contents($this->filePath, implode(chr(9), $line) . "\n", FILE_APPEND | LOCK_EX);

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

        // Not US or Canada
        if (!in_array(strtoupper($shipment->recipient_country_code), ['US', 'CA'])) {
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
