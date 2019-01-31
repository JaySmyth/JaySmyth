<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendScanningReportEmail extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:send-scanning-report-email {--recipient=} {--last-weekday}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scanning statistics email';

    /**
     * The default recipient.
     *
     * @var string
     */
    protected $recipient = 'scharlton@antrim.ifsgroup.com';

    /**
     * Default cc.
     *
     * @var string
     */
    protected $cc = ['transport@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com', 'gdonald@antrim.ifsgroup.com', 'shaunf@antrim.ifsgroup.com'];

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

        $recipient = $this->option('recipient');

        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->recipient = $recipient;
            $this->cc = [];
        }

        $period = [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        $date = date('d-m-y', strtotime('last weekday'));

        if ($this->option('last-weekday')) {
            $period = [Carbon::today()->modify("last weekday")->startOfDay(), Carbon::today()->modify("last weekday")->endOfDay()];
            $date = date('d-m-y', strtotime('last weekday'));
        }

        $receiptScans = \App\Package::where('date_received', '=', DB::raw('date_loaded'))
                ->whereBetween('date_received', $period)
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
                ->whereBetween('date_received', $period)
                ->where('shipments.depot_id', 1)
                ->where('sender_postcode', 'LIKE', 'BT%')
                ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
                ->whereNotIn('shipments.status_id', [1, 7])
                ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
                ->orderBy('sender_company_name')
                ->orderBy('shipments.id')
                ->orderBy('packages.index')
                ->get();

        Mail::to($this->recipient)->cc($this->cc)->send(new \App\Mail\MissedScans($receiptScans, $routeScans, 'Missed Scans (receipt: ' . $receiptScans->count() . ' / route: ' . $routeScans->count() . ') - ' . $date));
    }

}
