<?php

namespace App\Console\Commands\ExpressFreight;

use App\Shipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UploadNIShipmentsToExpressFreight extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-ni-shipments-to-express-freight';

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
     * Filename.
     *
     * @var
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

        $this->fileName = 'express_freight_ni_' . time() . '.csv';
        $this->filePath = '/home/expressfreight/manifests/ni/' . $this->fileName;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load shipments that have been received at IFS
        $this->shipments = \App\Shipment::whereCarrierId(14)->whereReceived(1)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        // Create the file to upload
        $this->createFile();

        // Update the source flag
        $this->setShipmentsToUploaded();

        // Send notification
        if (count($this->validShipments) > 0) {
            Mail::to('ASteenson@expressfreight.co.uk')->cc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Express Freight NI Manifest (' . count($this->validShipments) . ' shipments)', 'Please see attached file', $this->filePath));
        }
    }

    /**
     * Create a file in the temp directory.
     *
     * @param type $shipment
     */
    private function createFile()
    {
        $handle = fopen($this->filePath, "w");

        // Add heading row
        fputcsv($handle, ['Consignment Number', 'Despatch Date', 'Consignee Name', 'Street 1', 'Street 2', 'City/Town', 'County', 'Postcode', 'Location', 'Instructions', 'Contact Number', 'Number Of Cartons', 'Carton Weight', 'Number Of Pallets', 'Pallet Weight', 'Number Of Others', 'Other Weight', 'Number Of Sets', 'Set Weight', 'COD Amount', 'COD Currency', 'Parcel number', 'Service Type', 'Contact Name']);

        foreach ($this->shipments as $shipment) :

            if ($this->isValid($shipment)) {

                $line = [

                    $shipment->consignment_number,
                    $shipment->ship_date->format('d/m/Y'),
                    'Pkg ' . $package->index . ' of ' . $shipment->pieces,
                    $package->weight,
                    $shipment->ship_date->format('d/m/Y'),
                    $shipment->recipient_name,
                    $shipment->recipient_company_name,
                    $shipment->recipient_address1,
                    $shipment->recipient_address2,
                    $shipment->recipient_city,
                    $shipment->recipient_state,
                    $shipment->recipient_postcode,
                    $shipment->recipient_country_code,
                    0
                ];

                // Remove any commas
                $line = array_map(
                    function ($str) {
                        return str_replace(',', '', $str);
                    },
                    $line
                );

                fputcsv($handle, $line);

                // Add to array of valid shipments
                $this->validShipments[] = $shipment->id;
            }

        endforeach;

        fclose($handle);
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
