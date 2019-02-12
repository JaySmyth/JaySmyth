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

        $this->info('** Logging Scanning KPIs **');

        while ($startDate->lessThanOrEqualTo($finishDate)) {

            $this->info('Logging KPIs for ' . $startDate);

            $this->logKpis($startDate);

            $startDate->addWeekday();
        }

        $this->info('Finished');
    }

    /**
     * Insert KPI record.
     * 
     * @param type $date
     * @return boolean
     */
    protected function logKpis($date)
    {
        // Load package records for date specified
        $packages = \App\Package::select('packages.*')
                ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
                ->whereBetween('ship_date', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()])
                ->where('shipments.depot_id', 1)
                ->whereNotIn('shipments.status_id', [1, 7])
                ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
                ->where('sender_postcode', 'LIKE', 'BT%')
                ->with('shipment')
                ->get();

        $totals = ['expected' => $packages->count(), 'collection' => 0, 'receipt' => 0, 'route' => 0, 'receipt_missed' => 0, 'route_missed' => 0];

        // Loop through each package and increment relevant counter
        foreach ($packages as $package) {
            inc($totals['collection'], $package->collected);
            inc($totals['receipt'], $package->true_receipt_scan);
            inc($totals['route'], $package->loaded);

            if ($package->collected || $package->received || $package->loaded) {

                if (!$package->true_receipt_scan) {
                    inc($totals['receipt_missed'], 1);
                    $receipt_missed[] = $package->id;
                }

                if (!$package->loaded) {
                    inc($totals['route_missed'], 1);
                    $route_missed[] = $package->id;
                }
            }
        }

        // Insert or update KPI
        \App\ScanningKpi::firstOrCreate(['date' => $date])->update([
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

}
