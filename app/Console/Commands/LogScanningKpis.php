<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class LogScanningKpis extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:log-scanning-kpis {--start-date=} {--finish-date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts or updates scanning KPIs for a given date range (defaults to today)';

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
        $startDate = ($this->option('start-date')) ? Carbon::parse($this->option('start-date')) : Carbon::today();
        $finishDate = ($this->option('finish-date')) ? Carbon::parse($this->option('start-date')) : Carbon::today();

        while ($startDate->lessThanOrEqualTo($finishDate)) {
            
            $this->info('Logging KPIs for ' . $startDate);
            
            dispatch(new \App\Jobs\LogScanningKpis($startDate));

            $startDate->addWeekday();
        }

        $this->info('Finished');
    }

}
