<?php

namespace App\Console\Commands;

use App\Manifest;
use App\ManifestProfile;
use Illuminate\Console\Command;

class ManifestUnmanifestedShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:manifest-unmanifested-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manifest unmanifested shipments';

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
        // Load the manifest profiles (exclude fedex intl)
        $manifestProfiles = ManifestProfile::all();

        foreach ($manifestProfiles as $profile) :

            $this->line('loading shipments for '.$profile->name);

        // Get unmanifested shipments
        $shipments = $profile->getShipments();

        foreach ($shipments as $shipment):

                $this->info($shipment->consignment_number.' '.$shipment->ship_date);

        $manifest = Manifest::whereManifestProfileId($profile->id)->where('created_at', '>', $shipment->ship_date)->first();

        if ($manifest) {
            $this->info('Adding '.$shipment->consignment_number.' ('.$shipment->ship_date.') to manifest '.$manifest->number.'('.$manifest->created_at.')');

            $shipment->manifest_id = $manifest->id;
            $shipment->save();
        } else {
            $this->error('Could not find a manifest for '.$shipment->ship_date);
        }

        endforeach;

        endforeach;
    }
}
