<?php

namespace App\Console\Commands\Maintenance;

use Carbon\Carbon;
use App\TransportJob;
use Illuminate\Console\Command;

class CorrectStatusOnTransportJobs extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:correct-status-on-transport-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct job status';

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
        $transportJobs = TransportJob::whereCompleted(1)
            ->where('sent', 1)
            ->whereNotIn('status_id', [15, 7])
            ->orderBy('id', 'DESC')
            ->get();

        $this->info($transportJobs->count() . ' transport jobs found');

        foreach ($transportJobs as $transportJob) {

            if ($transportJob->shipment) {

                if ($transportJob->shipment->status_id == 7) {

                    $this->info($transportJob->number . ': shipment canceled, updating status to CANCELLED');

                    $transportJob->setStatus('cancelled');
                } elseif ($transportJob->shipment->received || $transportJob->shipment->delivered) {

                    $this->info($transportJob->number . ': shipment received, updating status to COMPLETED');

                    $transportJob->setStatus('completed');
                } else {
                    $this->error($transportJob->number . ': unknown');
                }
            } else {
                $transportJob->setStatus('completed');
            }
        }
    }

}
