<?php

namespace App\Models\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AddChargeDetail_old extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

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
     * @return type
     */
    public function addCharge()
    {
        return $this->belongsTo(AddCharge::class);
    }

    /**
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
            return $query->where('add_charge_details.name', 'LIKE', '%'.$filter.'%')
                            ->orWhere('code', 'LIKE', '%'.$filter.'%');
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
    public function scopeHasCategory($query, $categoryId)
    {
        if (is_numeric($categoryId)) {
            return $query->where('add_charge_id', $categoryId);
        }
    }

    /**
     * Scope company.
     *
     * @return
     */
    public function scopeHasType($query, $type)
    {
        if (! empty($type)) {
            return $query->where('type', $type);
        }
    }
}
