<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;

class ClearUpTrackingEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:clear-up-tracking-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete duplicate tracking events';

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
        $tracking = \App\Tracking::whereNotNull('shipment_id')->groupBy('shipment_id', 'message', 'status')->havingRaw('count(*) > 1')->orderBy('shipment_id', 'DESC')->limit(20000)->get();

        $this->info($tracking->count().' duplicate tracking events found');

        foreach ($tracking as $duplicate) {
            $duplicateTracking = \App\Tracking::whereShipmentId($duplicate->shipment_id)->whereMessage($duplicate->message)->whereStatus($duplicate->status)->orderBy('id', 'DESC')->get();

            $this->info($duplicateTracking->count().' duplicate events loaded');

            foreach ($duplicateTracking as $event) {
                $count = \App\Tracking::whereShipmentId($duplicate->shipment_id)->whereMessage($duplicate->message)->whereStatus($duplicate->status)->count();

                if ($count > 1) {
                    $this->error('Shipment ID: '.$event->shipment_id.' - deleting '.$event->id.' / '.$event->message);
                    $event->delete();
                } else {
                    $this->info('Shipment ID: '.$event->shipment_id.' - no duplicates');
                }
            }
        }
    }
}
