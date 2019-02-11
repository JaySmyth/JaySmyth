<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogScanningKpis implements ShouldQueue
{

    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date = null)
    {
        $this->date = ($date) ? new Carbon($date) : Carbon::today();
    }

    /**
     * Execute the job.
     * 
     * @return void
     */
    public function handle()
    {
        // Load package records for date specified
        $packages = \App\Package::select('packages.*')
                ->join('shipments', 'packages.shipment_id', '=', 'shipments.id')
                ->whereBetween('ship_date', [Carbon::parse($this->date)->startOfDay(), Carbon::parse($this->date)->endOfDay()])
                ->where('shipments.depot_id', 1)
                ->whereNotIn('shipments.status_id', [1, 7])
                ->whereNotIn('shipments.service_id', [7, 18, 20, 39, 44, 45, 48, 50])
                ->where('sender_postcodes', 'LIKE', 'BT%')
                ->with('shipment')
                ->get();

        $totals = ['expected' => $packages->count(), 'collection' => 0, 'receipt' => 0, 'route' => 0, 'receipt_missed' => 0, 'route_missed' => 0];

        // Loop through each package and increment relevant counter
        foreach ($packages as $package) {
            inc($totals['collection'], $package->collected);
            inc($totals['receipt'], $package->true_receipt_scan);
            inc($totals['route'], $package->loaded);

            if (!$package->true_receipt_scan) {
                inc($totals['receipt_missed'], 1);
            }

            if (!$package->loaded) {
                inc($totals['route_missed'], 1);
            }
        }

        // Insert or update KPI
        \App\ScanningKpi::firstOrCreate(['date' => $this->date])->update([
            'expected' => $totals['expected'],
            'collection' => $totals['collection'],
            'receipt' => $totals['receipt'],
            'route' => $totals['route'],
            'receipt_missed' => $totals['receipt_missed'],
            'route_missed' => $totals['route_missed']]
        );
    }

}
