<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DriverManifest;

class CloseDriverManifests extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:close-driver-manifests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close currently open manifests (today or older)';

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
        $driverManifests = DriverManifest::whereClosed(0)->where('date', '<', \Carbon\Carbon::now()->endOfDay())->get();

        foreach ($driverManifests as $driverManifest) {
            $driverManifest->close();
        }
    }

}
