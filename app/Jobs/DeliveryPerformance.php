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

    public $timeout = 999;
    protected $depot;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($depot, $type = "domestic")
    {
        $this->depot = $depot;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // AND carrier_id IN ('2','3','4','5','14','16','17')

        // Get Data
        $depot = $this->depot;
        $type = $this->type;
        $recipients = ['aplatt@antrim.ifsgroup.com', 'sanderton@antrim.ifsgroup.com', 'epalframan@antrim.ifsgroup.com'];
        // $recipients = ['gmcbroom@antrim.ifsgroup.com'];
        $startDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->subDays(2)->endOfDay()->format('Y-m-d H:i:s');
        if ($this->type == "domestic") {
            $data = $this->getDomestic($startDate, $endDate, $depot);
        } else {
            $data = $this->getNonDomestic($startDate, $endDate, $depot);
        }

        // Format data
        $table = [];
        foreach ($data as $row) {
            $table[$row->status_id][$row->carrier_id] = $row->COUNT;
            if (! isset($carriers[$row->carrier_id])) {
                $carriers[$row->carrier_id] = Carrier::find($row->carrier_id)->name;
            }
        }

        // Send report to user
        Mail::to($recipients)->send(new \App\Mail\DeliveryPerformanceResults($depot, $table, $carriers, $startDate, $endDate, $type));
    }

    private function getDomestic($startDate, $endDate, $depot)
    {
        return DB::select(DB::raw("
            SELECT carrier_id, carriers.code, status_id, statuses.code, COUNT(shipments.id) AS COUNT FROM shipments
            JOIN statuses ON statuses.id = status_id
            JOIN carriers ON carriers.id = carrier_id
            WHERE ship_date >= '$startDate'
            AND ship_date <= '$endDate'
            AND status_id IN ('3', '4', '5', '6','9', '10', '11', '20', '21')
            AND service_id not in ('4')
            AND depot_id = '$depot'
            AND recipient_country_code in ('GB','IE')
            AND recipient_country_code IS NOT NULL
            GROUP BY carrier_id, status_id
            ORDER BY carrier_id, status_id;
        "));
    }

    private function getNonDomestic($startDate, $endDate, $depot)
    {
        return DB::select(DB::raw("
            SELECT carrier_id, carriers.code, status_id, statuses.code, COUNT(shipments.id) AS COUNT FROM shipments
            JOIN statuses ON statuses.id = status_id
            JOIN carriers ON carriers.id = carrier_id
            WHERE ship_date >= '$startDate'
            AND ship_date <= '$endDate'
            AND status_id IN ('3', '4', '5', '6','9', '10', '11', '20', '21')
            AND service_id not in ('4')
            AND depot_id = '$depot'
            AND carrier_id not in ('1')
            GROUP BY carrier_id, status_id
            ORDER BY carrier_id, status_id;
        "));
    }
}
