<?php

namespace App\Console\Commands;

use App\Shipment;
use Illuminate\Console\Command;

class GetTracking extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:get-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform tracking requests for all active shipments';

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
        //foreach (Shipment::where('carrier_id', 3)->orderBy('id', 'asc')->isActive()->cursor() as $shipment) {

        $shipments = Shipment::whereIn('carrier_tracking_number', ['1Z922E2A0494697663'])->get();

        foreach ($shipments as $shipment) {
            $this->info('Getting tracking updates for ' . $shipment->carrier->name . ' shipment: ' . $shipment->carrier_consignment_number);
            $shipment->updateTracking();
        }

        $this->info('Finished');
    }

}