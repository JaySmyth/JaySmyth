<?php

namespace App\Console\Commands\ExpressFreight;

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
    protected $description = 'Upload NI shipments to express freight';

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

        $this->fileName = 'exp_ni_' . time() . '.csv';
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
        $this->shipments = \App\Models\Shipment::whereCarrierId(15)->whereReceived(1)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        if ($this->shipments->count() > 0) {

            // Create the file to upload
            $this->createFile();

            Mail::to('ASteenson@expressfreight.co.uk')->cc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Express Freight NI Manifest (' . $this->shipments->count() . ' shipments)', 'Please see attached file', $this->filePath));
        }
    }

    /**
     * Create a file in the temp directory.
     *
     * @param type $shipment
     */
    private function createFile()
    {
        $handle = fopen($this->filePath, 'w');

        // Add heading row
        fputcsv($handle, [
            'Consignment Number',
            'Despatch Date',
            'Consignee Name',
            'Street 1',
            'Street 2',
            'City/Town',
            'County',
            'Postcode',
            'Location',
            'Instructions',
            'Contact Number',
            'Number Of Cartons',
            'Carton Weight',
            'Number Of Pallets',
            'Pallet Weight',
            'Number Of Others',
            'Other Weight',
            'Number Of Sets',
            'Set Weight',
            'COD Amount',
            'COD Currency',
            'Parcel number',
            'Service Type',
            'Contact Name'
        ]);

        foreach ($this->shipments as $shipment) :

            $line = [
                $shipment->carrier_consignment_number,
                $shipment->ship_date->format('d/m/Y'),
                $shipment->recipient_name,
                $shipment->recipient_company_name . ' - ' . $shipment->recipient_address1,
                $shipment->recipient_address2,
                $shipment->recipient_city,
                $shipment->recipient_state,
                $shipment->recipient_postcode,
                'North Ireland',
                $shipment->special_instructions,
                $shipment->recipient_telephone,
                $shipment->pieces,
                $shipment->weight,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                null,
                $shipment->consignment_number,
                'STANDARD',
                $shipment->recipient_name,
            ];

            // Remove any commas
            $line = array_map(
                function ($str) {
                    return str_replace(',', '', $str);
                },
                $line
            );

            fputcsv($handle, $line);

            $shipment->received_sent = true;
            $shipment->source = $this->fileName;
            $shipment->save();

            $shipment->log('Uploaded to Express Freight');

        endforeach;

        fclose($handle);
    }


}
