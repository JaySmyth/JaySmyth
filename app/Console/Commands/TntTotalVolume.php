<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TntTotalVolume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:tnt-total-volume {--start-date=} {--finish-date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get volume for TNT shipments';

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

        $shipments = Shipment::whereCarrierId(4)->whereBetween('ship_date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($finishDate)->endOfDay()])->whereNotIn('status_id', [1, 7])->get();

        $count = $shipments->count();

        $this->info("$count TNT shipments found");

        foreach ($shipments as $shipment) {
            $totalVolume = $this->getTotalVolume($shipment);
            $volumetricWeight = $totalVolume * 250;
            $this->info($shipment->consignment_number.','.$shipment->carrier_consignment_number.','.$totalVolume.','.$volumetricWeight);
        }

        $this->info('Finished');
    }

    /**
     * Get shipment volume.
     *
     * @return float|int
     */
    protected function getTotalVolume($shipment)
    {
        $totalVolume = 0;

        foreach ($shipment->packages as $package) {
            $totalVolume += ($package->length / 100) * ($package->width / 100) * ($package->height / 100);
        }

        return $totalVolume;
    }
}
