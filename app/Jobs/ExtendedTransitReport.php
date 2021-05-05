<?php

namespace App\Jobs;

use \App\Models\Carrier;
use \App\Models\Shipment;

use DB;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ExtendedTransitReport implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $timeout = 999;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get Data
        $recipients = ['sfleck@antrim.ifsgroup.com', 'aplatt@antrim.ifsgroup.com'];
        $startDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->subDays(14)->endOfDay()->format('Y-m-d H:i:s');
        $data = Shipment::where('status_id', '4')
                    ->where('ship_date', '>=', $startDate)
                    ->where('ship_date', '<=', $endDate)
                    ->orderBy('ship_date')
                    ->orderBy('consignment_number')
                    ->get();
        // Format data
        $table = [];
        foreach ($data as $row) {
            $table[$row->carrier_id][] = $row;
        }

        // Send report to user
        Mail::to($recipients)->bcc('gmcbroom@antrim.ifsgroup.com')->send(new \App\Mail\ExtendedTransitReport($table, $startDate, $endDate));
    }
}
