<?php

namespace App;

use App\CarrierAPI\TNT\TNTManifest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
/*
 * Temporary code.
 */

use Illuminate\Support\Facades\Mail;

class ManifestProfile extends Model
{
    /*
     * No timestamps.
     *
     */

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'mode_id', 'carrier_id', 'route_id', 'depot_id', 'collect_shipments_only', 'exclude_collect_shipments', 'auto', 'time'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_run'];

    /**
     * A manifest profile may have multiple services defined.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * A manifest profile may have multiple countries defined.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    /**
     * A manifest profile belongs to a mode of transport (courier, air, etc.).
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    /**
     * A manifest profile is owned by a route.
     *
     * @return
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * A manifest profile is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A manifest profile has one carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * Get an array of service IDs defined for the profile.
     *
     * @return
     */
    public function getServiceIds()
    {
        return $this->services->pluck('id')->unique();
    }

    /**
     * Get an array of service IDs defined for the profile.
     *
     * @return
     */
    public function getCountryCodes()
    {
        return $this->countries->pluck('country_code')->unique();
    }

    /**
     * Get the total weight of available shipments.
     *
     * @return string
     */
    public function getWeightAvailableAttribute()
    {
        return number_format($this->getShipments()->sum('weight'), 2);
    }

    /**
     * Get the total weight of available shipments.
     *
     * @return string
     */
    public function getWeightHoldAttribute()
    {
        return number_format($this->getShipments('on_hold')->sum('weight'), 2);
    }

    /**
     * Get the total viable shipments on hold.
     *
     * @return string
     */
    public function getOnHoldAttribute()
    {
        return $this->getShipments(1)->count();
    }

    /**
     * Get the total viable shipments on hold.
     *
     * @return string
     */
    public function getAvailableAttribute()
    {
        return $this->getShipments()->count();
    }

    /*
     * Get the shipments available for manifesting.
     */

    public function getShipments($onHold = 0, $companyId = false)
    {
        $query = Shipment::OrderBy('ship_date', 'desc')
            ->orderBy('service_id', 'DESC')
            ->availableForManifesting()
            ->hasMode($this->mode_id)
            ->hasDepot($this->depot_id)
            ->hasCarrier($this->carrier_id)
            ->hasRoute($this->route_id)
            ->hasCompany($companyId)
            ->whereOnHold($onHold);

        if ($this->collect_shipments_only && $this->carrier_id == 2) {
            $query->isFedexCollect($query);
        }

        if ($this->exclude_collect_shipments && $this->carrier_id == 2) {
            $query->isNotFedexCollect($query);
        }

        if ($this->services->count() > 0) {
            $query->whereIn('service_id', $this->getServiceIds());
        }

        if ($this->countries->count() > 0) {
            $query->whereIn('recipient_country_code', $this->getCountryCodes());
        }

        return $query->with('carrier', 'depot', 'service', 'route', 'company')->get();
    }

    /**
     * Determine if a shipment is viable for a profile.
     *
     * @param type $shipmentId
     * @return bool
     */
    public function isShipmentViable($shipmentId)
    {
        $query = Shipment::whereId($shipmentId)
            ->hasDepot($this->depot_id)
            ->hasCarrier($this->carrier_id)
            ->hasRoute($this->route_id);

        if ($this->collect_shipments_only && $this->carrier_id == 2) {
            $query->isFedexCollect($query);
        }

        if ($this->exclude_collect_shipments && $this->carrier_id == 2) {
            $query->isNotFedexCollect($query);
        }

        if ($this->services->count() > 0) {
            $query->whereIn('service_id', $this->getServiceIds());
        }

        if ($this->countries->count() > 0) {
            $query->whereIn('recipient_country_code', $this->getCountryCodes());
        }

        if ($query->first()) {
            return true;
        }
    }

    /*
     * Run the manifest profile (set shipments to manifested).
     *
     * @return boolean
     */

    public function run($manifestId = false)
    {
        // load the shipments
        $shipments = $this->getShipments();

        if ($shipments->count() <= 0) {
            return false;
        }

        if (is_numeric($manifestId)) {
            // append to manifest
            $manifest = Manifest::findOrFail($manifestId);
        } else {
            // generate a manifest number
            $manifestNumber = $this->getManifestNumber();

            // create a new manifest
            $manifest = Manifest::create([
                'number' => $manifestNumber,
                'mode_id' => $this->mode_id,
                'depot_id' => $this->depot_id,
                'carrier_id' => $this->carrier_id,
                'manifest_profile_id' => $this->id,
            ]);
        }

        // an array of the shipment IDs
        $shipmentIds = $shipments->pluck('id');

        // update all the shipments with the manifest id
        Shipment::whereIn('id', $shipmentIds)->update([
            'manifest_id' => $manifest->id,
        ]);

        $this->last_run = Carbon::now();
        $this->save();

        /*
         * Upload is required to carrier. Add it to manifest upload queue
         * for processing.
         */
        if ($this->upload_required) {
            //dispatch((new ManifestUpload)->onQueue('manifest_uploads'));

            /*
             * Temporary code.
             */
            if ($this->carrier_id == 4) {
                $TNTManifest = new TNTManifest($shipments);
                $pdf = $TNTManifest->create();
                Mail::queue(new \App\Mail\TntManifest($pdf));
            }
        }

        return true;
    }

    /**
     * Generate a manifest number.
     *
     * @return string
     */
    private function getManifestNumber()
    {
        // set a default return value
        $number = time();

        $prefix = $this->prefix;
        $prefix = str_pad($prefix, 4, '0', STR_PAD_RIGHT);

        // get a sequence number
        $sequence = Sequence::whereCode('MANIFEST')->first();

        if ($sequence) {
            $number = $sequence->getNextAvailable();
        }

        return $prefix.str_pad($number, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Get the last run time for a manifest profile.
     *
     * @param type $timeZone
     * @param type $format
     * @return string
     */
    public function getLastRunTime($timeZone = 'Europe/London', $format = 'd-m-Y')
    {
        if ($this->last_run) {
            return $this->last_run->timezone($timeZone)->format($format.' H:i');
        }

        return 'Never';
    }

    /**
     * Update the hold flag on all shipments for a given company.
     *
     * @param type $hold
     * @param type $companyId
     */
    public function bulkHold($hold, $companyId)
    {
        $shipments = $this->getShipments($hold, $companyId);

        if ($shipments->count() > 0) {
            // Set the source field on all shipments to that of the filename
            \App\Shipment::whereIn('id', $shipments->pluck('id'))->update([
                'on_hold' => ($hold) ? 0 : 1,
            ]);

            return true;
        }

        return false;
    }
}
