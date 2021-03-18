<?php

namespace App\Console\Commands\Maintenance;

use App\Models\Shipment;
use Illuminate\Console\Command;

class CancelOldShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:cancel-old-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel old shipments';

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

        /*
         * Cancel shipments before cut off
         */
        $shipments = Shipment::whereReceived(0)
            ->whereDelivered(0)
            ->where('created_at', '>', now()->subYear())
            ->where('ship_date', '<=', now()->subDays(15)->endOfDay())
            ->whereNull('invoice_run_id')
            ->whereIn('status_id', [2, 11])
            ->get();

        foreach ($shipments as $shipment) {
            $shipment->setCancelled(2);
            $this->info('Shipment '.$shipment->consignment_number.' cancelled (ship date '.$shipment->ship_date->format('d-m-Y').')');
            $total++;
        }

        /*
         * Cancel shipments from test companies
         */
        $shipments = Shipment::select('shipments.*')
            ->join('companies', 'shipments.company_id', '=', 'companies.id')
            ->where('companies.testing', 1)
            ->where('received', 0)
            ->where('delivered', 0)
            ->whereNull('invoice_run_id')
            ->whereIn('status_id', [2, 11])
            ->get();

        foreach ($shipments as $shipment) {
            $shipment->setCancelled(2);
            $this->info('Test Shipment '.$shipment->consignment_number.' cancelled (ship date '.$shipment->ship_date->format('d-m-Y').')');
            $total++;
        }

        $this->info("** $total shipment(s) cancelled **");
    }
}
