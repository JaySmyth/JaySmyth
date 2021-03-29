<?php

namespace App\Console\Commands\Maintenance;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteSavedShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:delete-saved-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete saved shipments over a month old';

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
        $cutOff = Carbon::now()->subMonths(1)->startOfDay();

        $this->info('Deleting saved shipments before '.$cutOff->toDateTimeString());

        \App\Models\Shipment::where('status_id', '=', 1)->where('created_at', '<=', $cutOff)->delete();

        $this->info('Finished');
    }
}
