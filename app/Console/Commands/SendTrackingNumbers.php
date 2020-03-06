<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTrackingNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:send-tracking-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail a CSV file containing tracking numbers to specified mail address';

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
        $shipments = \App\Models\Shipment::whereReceived(1)->whereReceivedSent(0)->whereCompanyId(855)->orderBy('id', 'DESC')->get();

        $this->info($shipments->count().' shipments found');

        $handle = fopen($this->tempFile, 'w');

        foreach ($shipments as $shipment) {
            $this->line('Adding shipment '.$shipment->consignment_number);
            fputcsv($handle, [$shipment->shipment_reference, 1, $shipment->pieces, $shipment->carrier_consignment_number]);
        }

        fclose($handle);

        // Set the source field on all shipments to that of the filename
        \App\Models\Shipment::whereIn('id', $shipments->pluck('id'))->update([
            'received_sent' => 1,
        ]);

        Mail::to('kerrikids1526376033@in.fulfillment.stock-sync.com')->cc(['it@antrim.ifsgroup.com', 'info@babocush.com'])->send(new \App\Mail\GenericError('Babocush Tracking Numbers (non US) - '.$shipments->count().' shipments', 'Please see attached file', $this->tempFile));
    }
}
