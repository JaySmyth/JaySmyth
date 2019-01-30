<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FxAwb extends Model
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
    protected $table = 'FX_AWBS';

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
        return $this->belongsTo(FxHeader::class, 'MATxID', 'TxID');
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
        if ($this->Rec_IFS == 'Y') {
            return false;
        }

        $dateReceived = toCarbon($dateReceived, 'Europe/London');

        $this->Rec_IFS = 'Y';
        $this->Rec_Date = $dateReceived->toDateString();
        $this->Rec_Time = $dateReceived->toTimeString();
        $this->locn = 'Antrim WH';
        $this->save();

        /*
         * Add a tracking event to the shipment
         */

        $message = 'Package ' .  $this->shipment->getPackageScanCount() . ' received';
        $message = ($carrierScan) ? $message . ' (carrier scan)' : $message;

        DB::connection('legacy')->table('ShipTrack')->insert(
                [
                    'depot' => $this->gateway,
                    'docketno' => $this->MATxID,
                    'packageID' => $this->MAAWBNo,
                    'datetime' => $dateReceived->toDateTimeString(),
                    'status' => $message,
                    'parcels' => $this->shipment->Total_Pkgs,
                    'eventtype' => 'FirstScan',
                    'location' => 'IFS Global Logistics',
                    'city' => 'Antrim',
                    'countrycode' => 'GB'
                ]
        );

        /*
         * Check if all packages have been received
         */

        $complete = true;

        foreach ($this->shipment->packages as $package) {
            if ($package->Rec_IFS == 'N') {
                $complete = false;
                break;
            }
        }

        /*
         * All packages scanned - update the shipment receipt status
         */
        if ($complete) {
            $this->shipment->setReceived($dateReceived, $userId, $carrierScan, false);
        }

        return true;
    }

}
