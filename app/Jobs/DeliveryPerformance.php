<?php

namespace App\Jobs;

use \App\Models\Carrier;

use DB;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DeliveryPerformance implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

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
        $recipient = 'aplatt@antrim.ifsgroup.com';
        $startDate = Carbon::now()->subMonths(1)->addDays(1)->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        $data = DB::select(DB::raw("
          SELECT carrier_id, carriers.code, status_id, statuses.code, COUNT(shipments.id) AS COUNT FROM shipments
          JOIN statuses ON statuses.id = status_id
          JOIN carriers ON carriers.id = carrier_id
          WHERE ship_date >= '$startDate' AND ship_date <= '$endDate' AND status_id NOT IN ('1', '2', '8', '7', '17', '18', '19') AND carrier_id != '1' AND company_id != '1015'
          GROUP BY carrier_id, status_id
          ORDER BY carrier_id, status_id;
        "));

        // Format data
        $table = [];
        foreach ($data as $row) {
            $table[$row->status_id][$row->carrier_id] = $row->COUNT;
            if (! isset($carriers[$row->carrier_id])) {
                $carriers[$row->carrier_id] = Carrier::find($row->carrier_id)->name;
            }
        }

        // Inform user that the import has been completed
        Mail::to($recipient)->send(new \App\Mail\DeliveryPerformanceResults($table, $carriers, $startDate, $endDate));
    }
}
