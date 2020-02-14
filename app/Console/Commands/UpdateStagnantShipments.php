<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStagnantShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:update-stagnant-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of shipments with high transit time';

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
        $total = 0;

        $cutOff = Carbon::now()->subDays(21)->endOfDay();

        $shipments = Shipment::orderBy('ship_date', 'ASC')->isActive()->where('ship_date', '<=', $cutOff)->whereModeId(1)->get();

        $this->info($shipments->count()." shipments within cut off period: $cutOff");

        foreach ($shipments as $shipment):

            if ($shipment->tracking) {
                $lastEvent = $shipment->tracking->first();

                $this->info($shipment->consignment_number.' - '.$shipment->ship_date->format('d-m-Y').' - '.$lastEvent->status.' - '.$lastEvent->message.' - '.$lastEvent->city);

                if ($lastEvent->status == 'delivered') {
                    $shipment->setDelivered($lastEvent->datetime, 'Unknown', 0, false);
                    continue;
                }

                if (($lastEvent->message == 'On carrier vehicle for delivery' && $lastEvent->city == 'ANTRIM GB') || stristr($lastEvent->message, 'returned to sender')) {
                    $shipment->setStatus('return_to_sender', 0, false, false);
                } else {
                    switch ($lastEvent->status) {
                        case 'return_to_sender':
                        case 'failure':
                        case 'available_for_pickup':
                            $shipment->setStatus($lastEvent->status, 0, false, false);

                        default:
                            $shipment->setStatus('unknown', 0, false, false);
                            break;
                    }
                }

                $total++;
            } else {
                $this->error('No tracking found for '.$shipment->consignment_number);
            }

        endforeach;

        $this->info("** $total shipment(s) updated **");
    }
}
