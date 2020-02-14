<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckForMissingPrimaryFreightDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-for-missing-primary-freight-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing shipment details from Primary Freight';

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
        $date = Carbon::now();
        $shipDate = $date->subWeekday()->format('Y-m-d');
        $shipDate = $date->format('Y-m-d');

        $shipments = Shipment::where('company_id', '=', '874')
                        ->whereRaw('carrier_consignment_number = consignment_number')
                        ->where('source', 'cartrover')
                        ->where('status_id', '!=', '7')
                        ->where('id', '>', '756170')
                        ->where('ship_date', '<', $shipDate)
                        ->orderBy('consignment_number')->get();

        if ($shipments->count() > 0) {
            Mail::to(['ryepez@primaryfreight.com', 'chenderson@primarylogistics.net'])->cc(['aplatt@antrim.ifsgroup.com', 'babocushenquiry@antrim.ifsgroup.com'])->bcc(['gmcbroom@antrim.ifsgroup.com'])->send(new \App\Mail\MissingPrimaryFreightDetails($shipments));
        }
    }
}
