<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RepriceShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:reprice-shipments {--startDate=} {--companyId=} {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reprice shipments for a given company';


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
        if ($this->option('startDate')) {
            $startDate = Carbon::parse($this->option('startDate'));
            $this->info('Start date: '.$startDate->format('d-M-Y'));
        } else {
            $startDate = now()->subDay()->startOfDay();
            $this->line('No start date specified, starting yesterday');
        }

        $shipments = Shipment::where('company_id', '!=', 965)->whereNull('invoice_run_id')->where('ship_date', '>=', $startDate)->whereNotIn('status_id', [1, 7]);

        if ($this->option('companyId')) {
            $company = Company::findOrFail($this->option('companyId'));

            $this->info("\nRepricing shipments for ".$company->company_name);

            $shipments->where('company_id', $company->id);
        }

        $savePricing = $this->option('test') ? false : true;

        if ($this->option('test')) {
            $this->info("Test mode - pricing changes will NOT be saved\n");
        }

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($shipments->orderBy('id', 'asc')->cursor() as $shipment) {
                $this->info($shipment->consignment_number);

                $this->line("ORIGINAL: charge:".$shipment->shipping_charge);
                $this->line("ORIGINAL: cost:".$shipment->shipping_cost);
                $this->info('Repricing...');

                $originalShipDate = $shipment->ship_date;
                $shipment->ship_date = now();

                $shipment->price($savePricing);

                if (! $this->option('test')) {
                    $shipment->ship_date = $originalShipDate;
                    $shipment->save();
                }

                $this->line("REPRICED: charge:".$shipment->shipping_charge);
                $this->line("REPRICED: cost:".$shipment->shipping_cost."\n");
            }
        }

        $this->info('Finished');
    }
}
