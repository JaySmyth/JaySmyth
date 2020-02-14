<?php

namespace App\Console\Commands;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckForDuplicateShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-for-duplicate-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for duplicate shipment consignment numbers';

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
        $cutOff = Carbon::yesterday()->startOfDay();

        $shipments = Shipment::where('ship_date', '>=', $cutOff)->groupBy('consignment_number')->havingRaw('count(*) > 1')->get();

        if ($shipments->count() > 0) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Duplicate consignment number detected', 'Please check shipments table for duplicate consignment numbers'));
        }

        $this->info('** '.$shipments->count().' duplicate(s) found **');
    }
}
