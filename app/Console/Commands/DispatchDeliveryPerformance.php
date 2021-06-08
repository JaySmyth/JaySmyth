<?php

namespace App\Console\Commands;

use App\Jobs\DeliveryPerformance;
use Illuminate\Console\Command;

class DispatchDeliveryPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:dispatch-delivery-performance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to report on carrier delivery performance';


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
        // Antrim
        dispatch(new DeliveryPerformance('1'));

        // Blind Co
        dispatch(new DeliveryPerformance('7'));
    }
}
