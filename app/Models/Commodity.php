<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $fillable = [
        'company_id',
        'description',
        'product_code',
        'country_of_manufacture',
        'manufacturer',
        'unit_value',
        'currency_code',
        'unit_weight',
        'weight_uom',
        'uom',
        'commodity_code',
        'harmonized_code',
        'shipping_cost',
    ];

    /**
     * A commodity is owned by a company.
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
     * @param type $query
     * @param type $filter
     * @return type
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('description', 'LIKE', '%'.$filter.'%')
                                ->orWhere('product_code', 'LIKE', '%'.$filter.'%')
                                ->orWhere('commodity_code', 'LIKE', '%'.$filter.'%');
            });
        }
    }

    /**
     * Scope company.
     *
     * @param type $query
     * @param type $companyId
     * @return type
     */
    public function scopeHasCompany($query, $companyId)
    {
        if (is_numeric($companyId)) {
            return $query->where('company_id', $companyId);
        }
    }

    /**
     * Scope city.
     *
     * @param type $query
     * @param type $city
     * @return type
     */
    public function scopeHasCurrency($query, $currencyCode)
    {
        if ($currencyCode) {
            return $query->where('currency_code', 'LIKE', '%'.$currencyCode.'%');
        }
    }

    /**
     * Scope restrict results by company.
     *
     * @param type $query
     * @param type $companyIds
     * @return type
     */
    public function scopeRestrictCompany($query, $companyIds)
    {
        return $query->whereIn('company_id', $companyIds);
    }
}
