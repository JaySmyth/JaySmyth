<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Driver;
use App\DriverManifest;
use Carbon\Carbon;

class OpenDriverManifests extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:open-driver-manifests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open a manifest for all currently enabled drivers';

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
        $drivers = Driver::whereEnabled(1)->whereNotNull('vehicle_id')->orderBy('name')->get();

        foreach ($drivers as $driver) {

            DriverManifest::create([
                'number' => \App\Sequence::whereCode('DRIVER')->lockForUpdate()->first()->getNextAvailable(),
                'driver_id' => $driver->id,
                'vehicle_id' => $driver->vehicle_id,
                'date' => time(),
                'depot_id' => $driver->depot_id
            ]);
            
        }
    }

}
