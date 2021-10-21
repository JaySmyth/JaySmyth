<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GetTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:get-tracking {--active=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform tracking requests for all active shipments';

    /**
     * Carrier IDs that we want to pull tracking for.
     *
     * @var array
     */
    protected $enabledCarriers = [3];

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
        /*
        $shipments = Shipment::whereIn('carrier_tracking_number', ['981838468'])->get();

        foreach ($shipments as $shipment) {
            $this->info('Getting tracking updates for '.$shipment->carrier->name.' shipment: '.$shipment->carrier_consignment_number);

            $shipment->updateTracking();
        }
*/


        $active = $this->option('active');

        if (! $active) {
            $this->info('Getting tracking updates for inactive shipments');

            // Shipments that have not been marked as received - wait 10 hours before trying to track them
            foreach (
                Shipment::whereIn('carrier_id', $this->enabledCarriers)->whereNull('external_tracking_url')->whereDelivered(0)->whereReceived(0)->whereNotIn('status_id', [1, 7])->where('created_at', '>', Carbon::now()->subMonths(2))->where('created_at', '<',
                    Carbon::now()->subHours(10))->orderBy('id', 'asc')->cursor() as $shipment
            ) {
                $this->info('Getting tracking updates for shipment: '.$shipment->carrier_consignment_number);

                $shipment->updateTracking();
            }
        } else {
            $this->info('Getting tracking updates for active shipments');

            // Shipments that have been received
            foreach (Shipment::whereIn('carrier_id', $this->enabledCarriers)->whereNull('external_tracking_url')->isActive()->orderBy('id', 'asc')->cursor() as $shipment) {
                $this->info('Getting tracking updates for shipment: '.$shipment->carrier_consignment_number);

                $shipment->updateTracking();
            }
        }

        $this->info('Finished');
    }
}
