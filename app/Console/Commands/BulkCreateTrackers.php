<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BulkCreateTrackers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:bulk-create-trackers {--start-date=} {--finish-date=} {--received} {--carrier-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk create easypost trackers for a given start/finish dates';

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
        $startDate = $this->option('start-date');
        $finishDate = $this->option('finish-date');

        if ($this->option('carrier-id')) {
            $this->info('createing trackers for carrier '.$this->option('carrier-id'));

            $shipments = Shipment::whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($finishDate)->endOfDay()])->hasCarrier($this->option('carrier-id'))->get();
        } else {
            if ($this->option('received')) {
                $shipments = Shipment::whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($finishDate)->endOfDay()])->isActive()->get();
            } else {
                $shipments = Shipment::whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($finishDate)->endOfDay()])->get();
            }
        }

        $count = $shipments->count();

        $input = $this->ask("$count active shipments found. Are you sure you want to create $count trackers? y/n");

        if ($input != 'y') {
            $this->error('Aborted');
            exit();
        }

        foreach ($shipments as $shipment) {
            if ($shipment->carrier->easypost != '***') {
                dispatch(new \App\Jobs\CreateEasypostTracker($shipment->carrier_consignment_number, $shipment->carrier->easypost));
                $this->info('Dispatched job: CreateEasyPostTracker('.$shipment->carrier_consignment_number.','.$shipment->carrier->easypost.')');
            }
        }

        $this->info('Finished');
    }
}
