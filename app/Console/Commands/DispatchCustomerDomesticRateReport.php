<?php

namespace App\Console\Commands;

use App\Jobs\DomesticRatesReport;
use Illuminate\Console\Command;

class DispatchCustomerDomesticRateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:dispatch-customer-domestic-rate-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches a job to generate a report of the domestic rates for all customers';


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
        dispatch(new DomesticRatesReport());
    }
}
