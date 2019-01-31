<?php

namespace App\Console\Commands\Transend;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CancelJobs extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transend:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send cancellation jobs to Transend';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load the cancelled jobs
        $jobs = \App\TransportJob::whereSent(0)->whereCompleted(1)->whereStatusId(7)->get();

        foreach ($jobs as $job) :
            if (!Carbon::now()->isWeekend()) {
                $this->info("Sending " . $job->number . " cancellation to transend");
                dispatch(new \App\Jobs\TransendOrderImport($job, 'D'));
                $job->log('Cancelled');
            }
        endforeach;
    }

}
