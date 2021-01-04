<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Shipment;
use Illuminate\Console\Command;

class RepriceShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:reprice-shipments {--companyId=} {--today} {--test}';

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
        $company = Company::findOrFail($this->option('companyId'));

        $this->info('Repricing shipments for '.$company->company_name);

        $savePricing = $this->option('test') ? false : true;

        if($this->option('test')){
            $this->info('Test mode - pricing changes will not be saved');
        }

        foreach (Shipment::where('company_id', $company->id)->whereNull('invoice_run_id')->whereNotIn('status_id', [1, 7])->orderBy('id', 'asc')->cursor() as $shipment) {
            $this->info("\n\n".$shipment->consignment_number);

            if ($this->option('today')) {
                $this->info('Setting ship date to today');
                $shipment->ship_date = now();
            }

            $this->line("Shipping charge:" . $shipment->shipping_charge);
            $this->line("Shipping cost:" . $shipment->shipping_cost);
            $this->info('Repricing...');

            $shipment->price($savePricing);

            $this->line("Shipping charge:" . $shipment->shipping_charge);
            $this->line("Shipping cost:" . $shipment->shipping_cost);
        }


        $this->info('Finished');
    }
}
