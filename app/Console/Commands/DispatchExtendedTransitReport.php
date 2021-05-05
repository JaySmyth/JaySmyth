<?php

namespace App\Console\Commands;

use App\Jobs\ExtendedTransitReport;
use Illuminate\Console\Command;

class DispatchExtendedTransitReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:dispatch-extended-transit-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches the extended transit report';


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
        dispatch(new ExtendedTransitReport());
    }
}
