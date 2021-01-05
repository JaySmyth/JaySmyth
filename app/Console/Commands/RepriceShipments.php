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
    protected $signature = 'ifs:reprice-shipments {--companyId=} {--test}';

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

        $this->info("\nRepricing shipments for ".$company->company_name);

        $savePricing = $this->option('test') ? false : true;

        $query = Shipment::where('company_id', $company->id)->whereNull('invoice_run_id')->where('received', true)->where('ship_date', '>=', now()->startOfYear())->whereNotIn('status_id', [1, 7])->orderBy('id', 'asc');

        if ($this->option('test')) {
            //$query->limit(4);
            $this->info("Test mode - pricing changes will NOT be saved\n");
        }

        $shipments = $query->get();

        $this->info("Found ".$shipments->count()." shipments for repricing");

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($shipments as $shipment) {
                $this->info($shipment->consignment_number);

                $this->line("ORIGINAL: charge:".$shipment->shipping_charge);
                $this->line("ORIGINAL: cost:".$shipment->shipping_cost);
                $this->info('Repricing...');

                $originalShipDate = $shipment->ship_date;
                $shipment->ship_date = now();

                $shipment->price($savePricing);

                if(!$this->option('test')){
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
