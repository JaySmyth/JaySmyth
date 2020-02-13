<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FuelSurcharge extends Model
{
    /*
     * Mass assignable.
     */

    protected $fillable = ['carrier_id', 'service_code', 'fuel_percent', 'from_date', 'to_date'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['from_date', 'to_date', 'created_at', 'updated_at'];

    /**
     * A fuel surcharge is owned by a carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * A fuel surcharge is owned by a service.
     *
     * @return
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            $filter = trim($filter);

            return $query->where('service_code', $filter);
        }
    }

    /**
     * Scope company.
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
     * @param type $query
     * @param type $fromDate
     * @return type
     */
    public function scopeFromDate($query, $fromDate)
    {
        if ($fromDate) {
            return $query->where('from_date', '>=', Carbon::parse($fromDate)->startOfDay());
        }
    }

    /**
     * @param type $query
     * @param type $fromDate
     * @return type
     */
    public function scopeToDate($query, $toDate)
    {
        if ($toDate) {
            return $query->where('to_date', '<=', Carbon::parse($toDate)->endOfDay());
        }
    }

    /**
     * @param type $carrierId
     * @param type $serviceCode
     * @param type $shipDate
     * @return type
     */
    public function getFuelPercentage($carrierId, $serviceCode, $shipDate)
    {
        $shipDate = date('Y-m-d', strtotime($shipDate));

        return $this->where('carrier_id', $carrierId)
                        ->where('service_code', $serviceCode)
                        ->where('from_date', '<=', $shipDate)
                        ->where('to_date', '>=', $shipDate)
                        ->first();
    }
}
