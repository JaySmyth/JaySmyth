<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;
use Carbon\Carbon;

class Manifest extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A manifest has many shipments.
     *
     * @return
     */
    public function shipments()
    {
        return $this->hasMany('App\Shipment')->orderBy('sender_company_name')->with('company', 'service');
    }

    /**
     * A manifest is owned by a depot.
     *
     * @return
     */
    public function manifestProfile()
    {
        return $this->belongsTo(ManifestProfile::class);
    }

    /**
     * A manifest belongs to a mode of transport (courier, air, etc.)
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo('App\Mode');
    }

    /**
     * A manifest is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo('App\Depot');
    }

    /**
     * A manifest has one carrier
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo('App\Carrier');
    }

    /**
     * Set the manifest number.
     *
     * @param  string  $value
     * @return string
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }

    /**
     * Returns the different services that are found within the manifest.
     *
     * @return array
     */
    public function getServicesArray()
    {
        return Service::whereIn('id', $this->shipments->pluck('service_id')->unique())->pluck('name', 'id')->toArray();
    }

    /**
     * Returns the number of different services that are found within the manifest.
     *
     * @return integer
     */
    public function getServicesAttribute()
    {
        return count($this->getServicesArray());
    }

    /**
     * Get the total weight of the manifest.
     *
     * @return string
     */
    public function getWeightAttribute()
    {
        return number_format($this->shipments->sum('weight'), 2);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('number', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope mode.
     *
     * @return
     */
    public function scopeHasMode($query, $mode)
    {
        if (is_numeric($mode)) {
            return $query->where('mode_id', $mode);
        }

        if ($mode) {
            return $query->select('manifests.*')
                            ->join('modes', 'manifests.mode_id', '=', 'modes.id')
                            ->where('modes.name', '=', $mode);
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (!$dateFrom && $dateTo) {
            return $query->where('created_at', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && !$dateTo) {
            return $query->where('created_at', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('created_at', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

    /**
     * Scope carrier.
     *
     * @return
     */
    public function scopeHasCarrier($query, $carrierId)
    {
        if (is_numeric($carrierId)) {
            return $query->where('carrier_id', $carrierId);
        }
    }

    /**
     * Scope depot.
     *
     * @return
     */
    public function scopeHasDepot($query, $depotId)
    {
        if (is_numeric($depotId)) {
            return $query->where('depot_id', $depotId);
        }
    }

    /**
     * Scope carrier.
     *
     * @return
     */
    public function scopeHasProfile($query, $profileId)
    {
        if (is_numeric($profileId)) {
            return $query->where('manifest_profile_id', $profileId);
        }
    }

}
