<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /*
     * Mass assignable.
     */

    protected $fillable = [
        'index',
        'length',
        'width',
        'height',
        'weight',
        'volumetric_weight',
        'dry_ice_weight',
        'packaging_code',
        'carrier_packaging_code',
        'carrier_tracking_number',
        'barcode',
        'received',
        'date_received',
        'location',
        'shipment_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'date_collected', 'date_received', 'date_loaded'];

    /**
     * A package belongs to a shipment.
     *
     * @return
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * A package has one packaging type.
     *
     * @return
     */
    public function packaging()
    {
        return $this->hasOne(PackagingType::class, 'id', 'packaging_type_id');
    }

    /**
     * Get the route code for the package.
     *
     * @return string
     */
    public function getRouteAttribute()
    {
        // Override Fedex International BFS
        if ($this->shipment->route->code == 'BFS') {
            return 'FEDF';
        }

        return $this->shipment->service->transend_route;
    }

    /**
     * Sets a package to collected.
     *
     * @param type $userId
     * @param type $dateReceived
     * @param type $carrierScan
     * @return bool
     */
    public function setCollected($dateCollected = null)
    {
        if ($this->collected) {
            return false;
        }

        $dateCollected = toCarbon($dateCollected);

        $this->collected = true;
        $this->date_collected = $dateCollected;
        $this->save();

        return true;
    }

    /**
     * Sets a package to received and inserts a tracking event.
     *
     * @param type $userId
     * @param type $dateReceived
     * @param type $carrierScan
     * @return bool
     */
    public function setReceived($dateReceived = null, $userId = 0, $carrierScan = false, $location = 'depot')
    {
        if ($this->received) {
            return false;
        }

        $dateReceived = toCarbon($dateReceived);

        $this->received = true;
        $this->date_received = $dateReceived;
        $this->save();

        /*
         * Add a tracking event to the shipment
         */

        $message = 'Package '.$this->index;
        $message = ($carrierScan) ? $message.' assumed received (carrier scan)' : $message.' received';

        if ($carrierScan) {
            $location = 'sender';
        }

        $this->shipment->addTracking('received', $dateReceived, $userId, $message, $location);

        /*
         * Check if all packages have been received
         */

        $complete = true;

        foreach ($this->shipment->packages as $package) {
            if (! $package->received) {
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

    /**
     * Sets a package to received and inserts a tracking event.
     *
     * @param type $userId
     * @param type $dateReceived
     * @param type $carrierScan
     * @return bool
     */
    public function setLoaded($dateLoaded = null)
    {
        if ($this->loaded) {
            return false;
        }

        $dateLoaded = toCarbon($dateLoaded);

        $this->loaded = true;
        $this->date_loaded = $dateLoaded;
        $this->save();

        $this->shipment->addTracking('received', $dateLoaded, 0, 'Package '.$this->index.' loaded to route', 'depot');

        return true;
    }

    /**
     * Get the contents of a package.
     *
     * @param type $packageIndex
     */
    public function getContents()
    {
        if ($this->shipment->contents) {
            $contents = $this->shipment->contents->where('package_index', $this->index)->first();

            if ($contents) {
                return [
                    'description' => $contents->description,
                    'quantity' => $contents->quantity,
                ];
            }
        }

        return [
            'description' => ($this->shipment->goods_description) ? $this->shipment->goods_description : 'unknown',
            'quantity' => 1,
        ];
    }

    /**
     * Determine if package received a valid receipt scan or was marked as received by route scan.
     *
     * @return bool
     */
    public function getTrueReceiptScanAttribute()
    {
        if ($this->received) {
            if ($this->loaded && $this->date_received == $this->date_loaded) {
                return false;
            }

            return true;
        }

        return false;
    }
}
