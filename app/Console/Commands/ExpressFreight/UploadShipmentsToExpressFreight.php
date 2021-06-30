<?php

namespace App\Console\Commands\ExpressFreight;

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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fileName = 'express_freight_'.time().'.csv';
        $this->filePath = '/home/expressfreight/manifests/roi/'.$this->fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load shipments that have been received at IFS
        $this->shipments = \App\Models\Shipment::whereCarrierId(14)->whereReceived(1)->whereCsvUploaded(0)->whereNotIn('status_id', [1, 7])->get();

        if ($this->shipments->count() > 0) {
            // Create the file to upload
            $this->createFile();

            Mail::to('itsupport@expressfreight.co.uk')->send(new \App\Mail\GenericError('Express Freight Manifest ('.$this->shipments->count().' shipments)', 'Please see attached file', $this->filePath));
        }
    }

    /**
     * Create a file in the temp directory.
     *
     * @param  type  $shipment
     */
    private function createFile()
    {
        $handle = fopen($this->filePath, 'w');

        // Add heading row
        fputcsv($handle, ['Consignment', 'IFS Reference', 'Piece', 'Weight', 'Ship Date', 'Name', 'Company', 'Address 1', 'Address 2', 'City', 'County', 'Postcode', 'Country Code', 'Cancelled']);

        foreach ($this->shipments as $shipment) :

            foreach ($shipment->packages as $package):

                $line = [
                    $package->carrier_tracking_number,
                    $shipment->consignment_number,
                    'Pkg '.$package->index.' of '.$shipment->pieces,
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
                    0,
                ];

                // Remove any commas
                $line = array_map(
                    function ($str) {
                        return preg_replace('/[[:^print:]]/', '', trim(str_replace(',', '', $str)));
                    },
                    $line
                );


                $result = fputcsv($handle, $line);

                if (! $result) {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Express Freight - failed to add package to csv', $package->carrier_tracking_number.'/'.$shipment->consignment_number));
                }

            endforeach;

            $shipment->csv_uploaded = true;
            $shipment->source = $this->fileName;
            $shipment->save();

            $shipment->log('Uploaded to Express Freight');

        endforeach;

        fclose($handle);
    }

}
