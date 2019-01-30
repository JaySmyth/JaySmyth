<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FukPackage extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FUKPackage';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * A package belongs to a shipment.
     *
     * @return
     */
    public function shipment()
    {
        return $this->belongsTo(FukShipment::class, 'docketno', 'docketno');
    }

    /**
     * Sets a package to received and inserts a tracking event.
     * 
     * @param type $userId
     * @param type $dateReceived
     * @param type $carrierScan
     * @return boolean
     */
    public function setReceived($dateReceived = null, $userId = 0, $carrierScan = false)
    {
        if ($this->scanned == 'Y') {
            return false;
        }

        $dateReceived = toCarbon($dateReceived, 'Europe/London');

        $this->scanned = 'Y';
        $this->tstamp = $dateReceived;
        $this->scanlocn = 'Antrim WH';
        $this->save();

        /*
         * Add a tracking event to the shipment
         */

        $message = 'Package ' .  $this->shipment->getPackageScanCount() . ' received';
        $message = ($carrierScan) ? $message . ' (carrier scan)' : $message;

        DB::connection('legacy')->table('ShipTrack')->insert(
                [
                    'depot' => $this->gateway,
                    'docketno' => $this->docketno,
                    'packageID' => $this->packageno,
                    'datetime' => $dateReceived->toDateTimeString(),
                    'status' => $message,
                    'parcels' => $this->shipment->pieces,
                    'eventtype' => 'FirstScan',
                    'location' => 'IFS Global Logistics',
                    'city' => 'Antrim',
                    'countrycode' => 'GB'
                ]
        );

        // Check if all packages have been received
        $complete = true;

        foreach ($this->shipment->packages as $package) {
            if ($package->scanned == 'N') {
                $complete = false;
                break;
            }
        }

        // All packages scanned - update the shipment receipt status         
        if ($complete) {
            $this->shipment->setReceived($dateReceived, $userId, $carrierScan, false);
        }

        return true;
    }

}
