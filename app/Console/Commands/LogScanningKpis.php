<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Models\ScanningKpi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LogScanningKpis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:log-scanning-kpis {--start-date=} {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts or updates scanning KPIs for a given date range (defaults to today) and send email report';

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
    protected $cc = ['transport@antrim.ifsgroup.com', 'shaunf@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com', 'ghanna@antrim.ifsgroup.com'];

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
        Log::channel('single')->info('Started LogScanningKpis');

        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Started LogScanningKpis', 'Server date: ' . date('d-m-Y H:i:s', time())));

        $startDate = ($this->option('start-date')) ? Carbon::parse($this->option('start-date')) : Carbon::today()->modify('last weekday');
        $finishDate = Carbon::today()->modify('last weekday');

        $this->info('** Logging Scanning KPIs **');

        while ($startDate->lessThanOrEqualTo($finishDate)) {
            $this->info('Logging KPIs for '.$startDate);

            $this->logKpis($startDate);

            $startDate->addWeekday();
        }

        $this->info('Finished');

        Log::channel('single')->info('Finished LogScanningKpis');
    }

    /**
     * Insert KPI record.
     *
     * @param type $date
     * @return bool
     */
    protected function logKpis($date)
    {
        // Load package records for date specified
        $packages = Package::select('packages.*')
            ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
            ->whereBetween('ship_date', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()])
            ->where('shipments.depot_id', 1)
            ->whereNotIn('shipments.status_id', [1, 7])
            ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
            ->whereNotIn('shipments.company_id', [965]) // ignore electrical world
            ->where('sender_postcode', 'LIKE', 'BT%')
            ->with('shipment')
            ->orderBy('sender_company_name')
            ->orderBy('shipments.id')
            ->orderBy('packages.index')
            ->get();

        $totals = ['expected' => $packages->count(), 'collection' => 0, 'receipt' => 0, 'route' => 0, 'receipt_missed' => 0, 'route_missed' => 0];
        $receiptMissed = [];
        $routeMissed = [];

        // Loop through each package and increment relevant counter
        foreach ($packages as $package) {
            inc($totals['collection'], $package->collected);
            inc($totals['receipt'], $package->true_receipt_scan);
            inc($totals['route'], $package->loaded);

            if ($package->collected || $package->received || $package->loaded) {
                if (! $package->true_receipt_scan) {
                    inc($totals['receipt_missed'], 1);
                    $receiptMissed[] = $package;
                }

                if (! $package->loaded) {
                    inc($totals['route_missed'], 1);
                    $routeMissed[] = $package;
                }
            }
        }


        if(! $this->option('test')){

            // Insert or update KPI
            ScanningKpi::firstOrCreate(['date' => $date])->update([
                'expected' => $totals['expected'],
                'collection' => $totals['collection'],
                'collection_percentage' => ($totals['expected'] > 0 && $totals['collection'] > 0) ? round((100 / $totals['expected']) * $totals['collection'], 1) : 0,
                'receipt' => $totals['receipt'],
                'receipt_percentage' => ($totals['expected'] > 0 && $totals['receipt'] > 0) ? round((100 / $totals['expected']) * $totals['receipt'], 1) : 0,
                'route' => $totals['route'],
                'route_percentage' => ($totals['expected'] > 0 && $totals['route'] > 0) ? round((100 / $totals['expected']) * $totals['route'], 1) : 0,
                'receipt_missed' => $totals['receipt_missed'],
                'route_missed' => $totals['route_missed'],
            ]);
        }



        // Only send the email when start date option has not been supplied
        if (! $this->option('start-date')) {

            if($this->option('test')){
                Mail::to('dshannon@antrim.ifsgroup.com')->send(new \App\Mail\MissedScans($receiptMissed, $routeMissed, 'Missed Scans (receipt: '.$totals['receipt_missed'].' / route: '.$totals['route_missed'].') - '.$date->format('d-m-y')));
            } else {
                Mail::to($this->recipient)->cc($this->cc)->send(new \App\Mail\MissedScans($receiptMissed, $routeMissed, 'Missed Scans (receipt: '.$totals['receipt_missed'].' / route: '.$totals['route_missed'].') - '.$date->format('d-m-y')));
            }

        }
    }
}
