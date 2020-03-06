<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surcharge extends Model
{
    /*
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['from_date', 'to_date', 'created_at', 'updated_at'];

    /*
     * The fields that are mass-assignable
     *
     * @var array
     */
    protected $fillable = ['name',
        'code',
        'cost_consignment_rate',
        'cost_weight_rate',
        'cost_package_rate',
        'cost_min',
        'sales_consignment_rate',
        'sales_weight_rate',
        'sales_package_rate',
        'sales_min',
        'service_id',
        'company_id',
        'from_date',
        'to_date',
    ];

    /**
     * A surcharge has many surcharge details.
     *
     * @return
     */
    public function surchargeDetails()
    {
        return $this->hasMany(SurchargeDetail::class)->orderBy('name');
    }

    /**
     * Get the type (verbose).
     *
     * @return string
     */
    public function getVerboseTypeAttribute()
    {
        return ($this->type == 'c') ? 'Cost' : 'Sale';
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('name', 'LIKE', '%'.$filter.'%')
                            ->orWhere('code', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope company.
     *
     * @return
     */
    public function scopeHasCompany($query, $companyId)
    {
        if (is_numeric($companyId)) {
            return $query->where('company_id', $companyId);
        }
    }

    /**
     * Scope category.
     *
     * @return
     */
    public function scopeHasService($query, $serviceId)
    {
        if (is_numeric($serviceId)) {
            return $query->where('service_id', $serviceId);
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (! empty($dateFrom)) {
            $query->where('from_date', '>=', Carbon::parse($dateFrom));
        }

        if (! empty($dateTo)) {
            $query->where('to_date', '<=', Carbon::parse($dateTo));
        }

        return $query;
    }

    /**
     * A user may belong to multiple companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getCharges($surchargeId, $code = '', $companyId = '0', $shipDate = null)
    {
        if (! $shipDate) {
            $shipDate = date('Y-m-d');
        }

        $surCharges = new SurchargeDetail();
        $surCharges = $surCharges->newQuery();
        $surCharges = $surCharges->whereIn('company_id', ['0', $companyId]);

        if ($code > '') {
            $surCharges = $surCharges->where('code', $code);
        }

        $surCharges = $surCharges->where('surcharge_id', $surchargeId)
                        ->where('from_date', '<=', $shipDate)
                        ->where('to_date', '>=', $shipDate)
                        ->orderBy('name')
                        ->orderBy('company_id', 'DESC')->get();

        if ($surCharges) {

            /*
             * ****************************************
             * Cycle through charges and remove default
             * charge if company specific charge exists
             *
             * Company charge will always appear first
             * ****************************************
             */
            $chargeCodes = [];
            foreach ($surCharges as $key => $surCharge) {

                // If Charge already added then ignore
                if (in_array($surCharge->code, $chargeCodes)) {
                    $surCharges->forget($key);
                } else {

                    // Charge not already added so add
                    $chargeCodes[] = $surCharge->code;
                }
            }
        }

        return $surCharges;
    }
}
