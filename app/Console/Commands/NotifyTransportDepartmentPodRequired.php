<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use App\TransportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyTransportDepartmentPodRequired extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:notify-transport-department-pod-required';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Advise transport department that there are jobs that need POD';

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
        $transportJobs = TransportJob::whereCompleted(0)
                ->where('driver_manifest_id', '>', 0)
                ->where('status_id', '!=', 7)
                ->where('date_requested', '<', Carbon::yesterday()->endOfDay())
                ->orderBy('date_requested', 'ASC')
                ->get();

        if ($transportJobs->count() > 20) {
            Mail::to('scharlton@antrim.ifsgroup.com')->cc(['transport@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com'])->send(new \App\Mail\PodTransportJobs($transportJobs));
        }

        $localPod = Shipment::where('carrier_id', 1)
                ->where('recipient_country_code', 'IE')
                ->where('ship_date', '<', Carbon::yesterday()->endOfDay())
                ->orderBy('ship_date', 'ASC')
                ->isActive()
                ->get();

        if ($localPod->count() > 0) {
            Mail::to(['lclose@antrim.ifsgroup.com', 'cgordon@antrim.ifsgroup.com'])->cc(['gmcnicholl@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com'])->send(new \App\Mail\PodShipments($localPod));
        }
    }

}
