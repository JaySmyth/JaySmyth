<?php

namespace App\Console\Commands\Maintenance;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class EmptyTempStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:empty-temp-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty all files from the temp storage directory';

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
        $files = Storage::allFiles('temp');

        if (count($files) <= 0) {
            $this->info('Temp storage empty');
            exit();
        }

        foreach ($files as $key => $file) {
            $time = Carbon::createFromTimestamp(Storage::lastModified($file));

            // younger than 24 hours - ignore
            if (Carbon::now()->diffInHours($time) <= 24) {
                $this->info("Ignoring $file");
                unset($files[$key]);
            } else {
                $this->info("Deleting $file");
            }
        }

        Storage::delete($files);
    }
}
