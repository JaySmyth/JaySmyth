<?php

namespace App\Console\Commands;

use App\Jobs\CreateEasypostTracker;
use App\Models\Shipment;
use Illuminate\Console\Command;

class DispatchEasypostTrackers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:dispatch-easypost-trackers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to create easypost trackers';


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
        foreach (Shipment::where('tracker_created', false)->whereNotIn('status_id', [1, 7])->cursor() as $shipment) {
            $this->line('Dispatching job for '.$shipment->consignment_number);

            if($shipment->carrier->easypost != '***'){
                dispatch(new CreateEasypostTracker($shipment->carrier_consignment_number, $shipment->carrier->easypost));
            }

            $shipment->tracker_created = true;
            $shipment->save();
        }
    }
}
