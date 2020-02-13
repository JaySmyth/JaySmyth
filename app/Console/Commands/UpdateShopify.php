<?php

namespace App\Console\Commands;

use App\Shipment;
use App\Vmi\VvOrders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateShopify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:update-shopify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail a CSV file to Shopify plugin to update orders with tracking number';

    /**
     * Temp file.
     *
     * @var string
     */
    protected $tempFile;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tempFile = storage_path('app/temp/tracking_'.time().'.csv');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shipments = Shipment::whereReceived(1)->whereReceivedSent(0)->whereCompanyId(995)->orderBy('id', 'DESC')->get();

        $this->info($shipments->count().' shipments found');

        $handle = fopen($this->tempFile, 'w');

        $ids = [];

        foreach ($shipments as $shipment) {
            $order = VvOrders::whereOrderNumber($shipment->shipment_reference)->first();

            if ($order) {

                // Add to the array of shipment ids
                $ids[] = $shipment->id;

                foreach ($order->stockItems as $item) {
                    fputcsv($handle, [$order->order_reference, $item->stock->part_number, $item->actual_quantity, $shipment->carrier_consignment_number, strtoupper($shipment->carrier->name)]);
                }
            }
        }

        fclose($handle);

        if (count($ids) > 0) {

            // Set the source field on all shipments to that of the filename
            \App\Shipment::whereIn('id', $ids)->update([
                'received_sent' => 1,
            ]);

            Mail::to('supernova-hair-tools1572962736@in.uptracker.app')->bcc(['it@antrim.ifsgroup.com'])->send(new \App\Mail\GenericError('We Are Paradoxx Tracking Numbers - '.count($ids).' shipments', null, $this->tempFile));
        }
    }
}
