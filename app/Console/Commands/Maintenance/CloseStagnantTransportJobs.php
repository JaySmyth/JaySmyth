<?php

namespace App\Console\Commands\Maintenance;

use App\Models\TransportJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseStagnantTransportJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:close-stagnant-transport-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close off stagnant transport jobs that have never been POD';

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
        $cutOff = new Carbon('-4 weeks');

        $transportJobs = TransportJob::whereCompleted(0)
                ->where('sent', 1)
                ->where('status_id', '!=', 7)
                ->where('date_requested', '<', $cutOff->endOfDay())
                ->orderBy('date_requested', 'ASC')
                ->get();

        $this->info($transportJobs->count().' transport jobs found');

        foreach ($transportJobs as $transportJob) {
            $datetime = Carbon::now();

            $transportJob->close($datetime, '* NO POD RECEIVED *', 0, null, false);

            $this->info($transportJob->number.' closed!');

            if ($transportJob->shipment) {
                if (strlen($transportJob->shipment->pod_signature) == 0) {
                    $transportJob->shipment->setStatus('unknown', 0, $datetime, false);
                    $this->info($transportJob->shipment->consignment_number.' set to status "unknown"');
                } else {
                    $transportJob->shipment->setStatus('delivered', 0, $transportJob->shipment->delivery_date, false);
                    $this->info($transportJob->shipment->consignment_number.' set to status "DELIVERED"');
                }
            }
        }

        /*
         * Delete any duplicates
         */

        $transportJobs = \App\Models\TransportJob::whereNotNull('shipment_id')->whereType('d')->groupBy('shipment_id')->havingRaw('count(*) > 1')->orderBy('shipment_id')->get();

        foreach ($transportJobs as $job) {
            $duplicateTransportJobs = \App\Models\TransportJob::whereShipmentId($job->shipment_id)->whereType($job->type)->whereCompleted(0)->get();

            foreach ($duplicateTransportJobs as $dup) {
                $count = \App\Models\TransportJob::whereShipmentId($job->shipment_id)->whereType($job->type)->count();

                if ($count > 1) {
                    $this->error('Deleting '.$dup->number.' / '.$job->reference);
                    $dup->delete();
                } else {
                    $this->info('No duplicates');
                }
            }
        }

        $this->info('Finished');
    }
}
