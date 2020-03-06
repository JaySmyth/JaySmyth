<?php

namespace App\Console\Commands;

use App\Models\ShipmentUpload;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProcessShipmentUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-shipment-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes shipment import files uploaded to IFS server by customers';

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

        // Loop through each of the uploads
        foreach ($shipmentUploads as $shipmentUpload):

            if (! $shipmentUpload->importConfig->user) {
                $this->error('User not defined on import config');
                continue;
            }

        $this->info($shipmentUpload->importConfig->company_name.': checking "'.$shipmentUpload->directory.'" for files to process');

        if ($handle = opendir($shipmentUpload->directory)) {
            while (false !== ($file = readdir($handle))) {
                if (stristr($file, '.csv')) {
                    $originalFile = $shipmentUpload->directory.$file;

                    $this->info("Found $originalFile");

                    $tempFile = storage_path('app/temp/original_'.Str::random(12).'.csv');

                    // Move the uploaded file to "storage/app/temp" for processing
                    if (copy($originalFile, $tempFile)) {

                            // Delete the original
                        unlink($originalFile);

                        // Dispatch the job
                        dispatch(new \App\Jobs\ImportShipments($tempFile, $shipmentUpload->importConfig->id, $shipmentUpload->importConfig->user))->onQueue('import');

                        // Update the last upload time on the shipment upload record
                        $shipmentUpload->last_upload = time();
                        $shipmentUpload->save();

                        $shipmentUpload->log('File Processed', $originalFile);

                        $shipmentUpload->incrementTotalProcessed();
                    }
                }
            }

            closedir($handle);
        }

        endforeach;

        $this->info('Finished processing shipment uploads');
    }
}
