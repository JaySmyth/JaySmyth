<?php

 namespace App\Models;

use App\Models\CarrierService;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    public $timestamps = false;

    /**
     * A carrier has many purchase invoice charge codes.
     *
     * @return
     */
    public function chargeCodes()
    {
        return $this->hasMany(CarrierChargeCode::class);
    }

    /**
     * Return equivilent carrier services to an IFS service code.
     *
     * @return
     */
    public function getServices($serviceCode)
    {
        return $this->services()->where('services.code', $serviceCode)->get();
    }

    /**
     * A carrier has many services.
     *
     * @return
     */
    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('carrier_name');
    }

    /**
     * Packaging types.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packagingTypes()
    {
        return $this->hasMany(\App\Models\CarrierPackagingType::class);
    }
}
