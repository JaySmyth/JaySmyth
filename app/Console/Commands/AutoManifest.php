<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ManifestProfile;
use Carbon\Carbon;

class AutoManifest extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:auto-manifest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scheduled auto manifests';

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
        // Load the manifest profiles flagged for automation
        $autoManifests = ManifestProfile::whereAuto(1)->get();

        foreach ($autoManifests as $manifest) {

            if ($this->isRunable($manifest->last_run, $manifest->time)) {
                $manifest->run();
                $this->info($manifest->name . ' manifested');
            }
        }
    }

    /**
     * Determine if the manifest should be run. 
     * 
     * @param type $lastRun
     * @param type $time
     * @return boolean
     */
    private function isRunable($lastRun, $time)
    {
        // already ran within the last 4 hours
        if ($lastRun && Carbon::now()->diffInHours($lastRun) <= 4) {
            return false;
        }

        $runTime = new Carbon('today ' . $time);

        // within 2 minutes
        if (Carbon::now()->diffInMinutes($runTime) <= 2) {
            return true;
        }

        return false;
    }

}
