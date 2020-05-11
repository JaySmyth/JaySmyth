<?php

namespace App\Console\Commands\Transend;

use Carbon\Carbon;
use Illuminate\Console\Command;

class SendJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transend:send {route*?} {--setRoutesOnly}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send jobs to Transend';

    /**
     * Array of company ids that jobs will be sent for.
     *
     * @var array
     */
    protected $companyIds = [];

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
        // Today's shippers
        $this->companyIds = \App\Shipment::orderBy('company_id')->whereNotIn('status_id',
            [7])->shipDateBetween(Carbon::today(), Carbon::today())->pluck('company_id')->toArray();

        // Set routes on the transport jobs before trying to send them
        $this->setRoutes();

        // Load the jobs
        $jobs = \App\TransportJob::whereSent(0)->whereCompleted(0)->limit(2500)->get();

        foreach ($jobs as $job) :
            if ($this->sendToTransend($job)) {
                $this->info('Sending ' . $job->number . ' to transend');

                dispatch(new \App\Jobs\TransendOrderImport($job));

                $message = ($job->is_resend) ? 'Re-sent to transend (' . $job->transend_route . ')' : 'Sent to transend (' . $job->transend_route . ')';

                $job->log($message);
            }
        endforeach;
    }

    /**
     * Set the routes before sending.
     */
    protected function setRoutes()
    {
        $jobs = \App\TransportJob::whereSent(0)->whereNull('transend_route')->get();

        foreach ($jobs as $job) {
            $job->setTransendRoute();
        }

        if ($this->option('setRoutesOnly')) {
            $this->info('Routes set');
            exit;
        }
    }

    /**
     * Determine if a job should be sent to Transend.
     *
     * @return bool
     */
    protected function sendToTransend($job)
    {
        if (Carbon::now()->isWeekend()) {
            $this->error('No jobs processed over the weekend');

            return false;
        }

        // If it's a resend, only send on date resend defined
        if ($job->is_resend && $job->resend_date) {
            $this->info('Resend identified for ' . $job->number);

            if ($job->resend_date->startOfDay()->gt(Carbon::today()->startOfDay())) {
                $this->error('Ignoring resend: ' . $job->resend_date->startOfDay() . ' > ' . Carbon::today()->startOfDay());

                return false;
            }
        }

        // Courier job
        if ($job->shipment) {
            // If we already have a job for this customer then add this one to the list too (regardless of collection day)
            //if (in_array($job->shipment->company->id, $this->companyIds) && $job->type == 'c') {
            //     return true;
            //  }

            // No previous jobs - company allows collection on this day of the week
            if ($job->shipment->company->getCollectionSettingsForDay(Carbon::now()->dayOfWeekIso) && $job->date_requested->startOfDay()->lte(Carbon::today()->startOfDay())) {
                return true;
            }
        } elseif ($job->date_requested->startOfDay()->lte(Carbon::today()->startOfDay())) {
            // Non courier - anything where date requested is for today or previous
            return true;
        }

        $this->error($job->number . ' rejected. Date requested ' . $job->date_requested->startOfDay() . ' is not equal to ' . Carbon::today()->startOfDay());

        return false;
    }
}
