<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A company is owned by a depot.
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo('App\Mode');
    }

    /**
     * A service has one carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo('App\Carrier');
    }

    /*
     * A Service is used by many Companies
     */

    public function companies()
    {
        return $this->belongsToMany('App\Company')->withTimestamps();
    }

    /**
     * Enable this Service for provided Company
     * and set the rate to be used.
     *
     * @param type $companyId
     * @param type $rateId
     * @return bool
     */
    public function enableForCompany($companyId, $rateId)
    {

        // If $companyId or $rateId missing return false
        if (empty($companyId) || empty($rateId)) {
            return false;
        }

        $effectiveDate = date('Y-m-d');

        // Attach service to the company if not already defined
        if (! $this->companies()->where('company_id', $companyId)->first()) {
            $this->companies()->attach($companyId);
        }

        $companyRates = new CompanyRates();
        $minDiscount = $companyRates->getMinMaxDiscount($companyId, $rateId, $this->code, $effectiveDate, 'min');
        $maxDiscount = $companyRates->getMinMaxDiscount($companyId, $rateId, $this->code, $effectiveDate, 'max');

        // If Rate already defined for this service then reset it, else create it.
        $rate = $companyRates->firstOrNew(['company_id' => $companyId, 'service_id' => $this->id])
                ->fill([
                    'rate_id' => $rateId,
                    'discount' => 0.00,
                    'special_discount' => ! ($minDiscount == 0 && $maxDiscount == 0),
                    'fuel_cap' => 99.99,
                ])
                ->save();

        return true;
    }

    public function surcharge()
    {
        return $this->hasOne('App\Surcharge', 'id', 'surcharge_id');
    }

    public function getSurcharges($companyId = '0', $code = '', $shipDate = '')
    {
        return Surcharge::getCharges($this->surcharge_id, $code, $companyId, $shipDate);
    }
}
