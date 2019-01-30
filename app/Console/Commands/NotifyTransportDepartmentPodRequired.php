<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Shipment;
use App\TransportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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


        $receiptScans = \App\Package::where('date_received', '=', DB::raw('date_loaded'))
                ->whereBetween('date_received', [Carbon::today()->modify("last weekday")->startOfDay(), Carbon::today()->modify("last weekday")->endOfDay()])
                ->where('shipments.depot_id', 1)
                ->where('sender_postcode', 'LIKE', 'BT%')
                ->whereNotIn('shipments.status_id', [1, 7])                
                ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
                ->orderBy('sender_company_name')
                ->orderBy('shipments.id')
                ->orderBy('packages.index')
                ->get();

        $routeScans = \App\Package::where('packages.received', 1)
                ->where('packages.loaded', 0)
                ->whereBetween('date_received', [Carbon::today()->modify("last weekday")->startOfDay(), Carbon::today()->modify("last weekday")->endOfDay()])
                ->where('shipments.depot_id', 1)
                ->where('sender_postcode', 'LIKE', 'BT%')
                ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
                ->whereNotIn('shipments.status_id', [1, 7])
                ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
                ->orderBy('sender_company_name')
                ->orderBy('shipments.id')
                ->orderBy('packages.index')
                ->get();

        if ($receiptScans->count() > 0 || $routeScans->count() > 0) {
            Mail::to('scharlton@antrim.ifsgroup.com')->cc(['transport@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com', 'gdonald@antrim.ifsgroup.com', 'shaunf@antrim.ifsgroup.com'])->send(new \App\Mail\MissedScans($receiptScans, $routeScans, 'Missed Scans (receipt: ' . $receiptScans->count() . ' / route: ' . $routeScans->count() . ') - ' . date('d-m-y', strtotime('last weekday'))));
        }
    }

}
