<?php

namespace App\Console\Commands\Maintenance;

use Carbon\Carbon;
use App\ShipmentUpload;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;


class CheckForUnprocessedUploads extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-for-unprocessed-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for unprocessed shipment upload files';


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
        // Get the enabled shipment uploads
        $shipmentUploads = ShipmentUpload::whereEnabled(1)->get();

        $oldFiles = [];

        // Loop through each of the uploads
        foreach ($shipmentUploads as $shipmentUpload):

            if (!file_exists($shipmentUpload->directory)) {
                continue;
            }

            if ($handle = opendir($shipmentUpload->directory)) {

                while (false !== ($file = readdir($handle))) {

                    if (stristr($file, '.csv')) {

                        $time = Carbon::createFromTimestamp(filemtime($shipmentUpload->directory . '/' . $file));

                        if (Carbon::now()->diffInMinutes($time) >= 30) {
                            $oldFiles[] = $file;
                        }

                    }
                }

                closedir($handle);
            }

        endforeach;

        $filecount = count($oldFiles);

        if ($filecount > 0) {

            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Warning: ' . $filecount . ' unprocessed shipment upload files', $filecount . ' files detected for processing in ' . $shipmentUpload->directory . '. These files are uploaded via SFTP by customer to create shipments. This directory should be empty. This program will attempt to restart the job queue. This process runs every 30 minutes. If you receive no further notificiations the issue has been resolved.'));

            exec('restart-job-queue');
        }

    }

}