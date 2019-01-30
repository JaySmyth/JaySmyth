<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SurchargeDetail extends Model
{

    protected $fillable = [
        'name',
        'code',
        'weight_rate',
        'package_rate',
        'consignment_rate',
        'min',
        'surcharge_id',
        'company_id',
        'from_date',
        'to_date'
    ];

    /*
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['from_date', 'to_date', 'created_at', 'updated_at'];

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setFromDateAttribute($value)
    {
        $this->attributes['from_date'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setToDateAttribute($value)
    {
        $this->attributes['to_date'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Get the date.
     *
     * @param  string  $value
     * @return string
     */
    public function getFromDateAttribute($value)
    {
        return date('d-m-Y', strtotime($this->attributes['from_date']));
    }

    /**
     * Get the date.
     *
     * @param  string  $value
     * @return string
     */
    public function getToDateAttribute($value)
    {
        return date('d-m-Y', strtotime($this->attributes['to_date']));
    }

    /**
     * 
     * @return type
     */
    public function addCharge()
    {
        return $this->belongsTo(AddCharge::class);
    }

    /**
     * 
     * @return type
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('surcharge_details.name', 'LIKE', '%' . $filter . '%')
                            ->orWhere('code', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (!empty($dateFrom)) {
            $query->where('from_date', '>=', Carbon::parse($dateFrom));
        }

        if (!empty($dateTo)) {
            $query->where('to_date', '<=', Carbon::parse($dateTo));
        }

        return $query;
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
     * Scope surcharge_id.
     * 
     * @return
     */
    public function scopeHasSurcharge($query, $surchargeId)
    {
        if (is_numeric($surchargeId)) {
            return $query->where('surcharge_id', $surchargeId);
        }
    }

}
