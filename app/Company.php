<?php

namespace App;

use App\Carrier;
use App\CompanyPackagingType;
use App\Rate;
use App\Service;
use App\Shipment;
use App\Surcharge;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Logable;

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
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A user may belong to multiple companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')->orderBy('name');
    }

    /**
     * A company has many shipments.
     *
     * @return
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class)->orderBy('id', 'DESC')->with('service', 'status', 'mode');
    }

    /**
     * A company has many commodities.
     *
     * @return
     */
    public function commodities()
    {
        return $this->hasMany(Commodity::class)->orderBy('id', 'ASC');
    }

    /**
     * A company is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A company has many services.
     *
     * @return
     */
    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('name', 'preference', 'account', 'scs_account', 'country_filter', 'monthly_limit', 'max_weight_limit')->orderBy('preference')->orderBy('name');
    }

    /**
     * A company is has one default print format.
     *
     * @return
     */
    public function printFormat()
    {
        return $this->belongsTo(PrintFormat::class);
    }

    /**
     * A company is has one default print format.
     *
     * @return
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Relationship.
     *
     * @return
     */
    public function packagingTypes()
    {
        return $this->hasMany(CompanyPackagingType::class);
    }

    /**
     * Returns Rates quoted for a customer.
     *
     * @return
     */
    public function companyRates()
    {
        return $this->hasMany(CompanyRates::class);
    }

    /**
     * Company has many file uploads.
     *
     * @return
     */
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    /**
     * A company has one localisation.
     *
     * @return
     */
    public function localisation()
    {
        return $this->belongsTo(Localisation::class);
    }

    /**
     * A company  has one despatch note config.
     *
     * @return
     */
    public function despatchNote()
    {
        return $this->hasOne(DespatchNote::class);
    }

    /**
     * Company has many collection settings.
     *
     * @return
     */
    public function collectionSettings()
    {
        return $this->hasMany(CollectionSetting::class)->orderBy('day');
    }

    /**
     * Get collection settings for a company. If no collection settings are defined, default
     * the values from the postcodes table.
     *
     * @return array
     */
    public function getCollectionSettingsArrayAttribute()
    {
        if ($this->collectionSettings->count() > 0) {
            return $this->collectionSettings->keyBy('day')->toArray();
        }

        return getRouting($this->postcode);
    }

    /**
     * Get collection settings for a specific day. If no collection settings are defined, default
     * the values from the postcodes table.  Days numbered 0 - 6 (Sunday = 0).
     *
     * @param int $day
     *
     * @return mixed array/boolean
     */
    public function getCollectionSettingsForDay($day, $postcode = false)
    {
        // Company has collection settings defined
        if ($this->collectionSettings->count() > 0) {
            return $this->collectionSettings->where('day', $day)->first();
        }

        // Use postcode passed via param or default to compay's postcode
        $postcode = ($postcode) ? $postcode : $this->postcode;

        return getRouting($postcode, $day);
    }

    /**
     * Returns a company's last 10 shipments.
     *
     * @return type
     */
    public function getLatestShipments()
    {
        return Shipment::whereCompanyId($this->id)->orderBy('ship_date', 'desc')->with('service', 'status', 'department', 'mode', 'company')->limit(10)->get();
    }

    /**
     * Returns Company specific Sales rate for specified service.
     *
     *
     * @return id of rate
     */
    public function salesRateForService($serviceId)
    {
        if (! empty($serviceId)) {
            $quotedRate = $this->companyRates->where('service_id', $serviceId)->first();

            // If no Customer specific rate defined then use default rate from service
            if (empty($quotedRate)) {
                $service = Service::find($serviceId);

                // If Service does not exist return null
                if (empty($service)) {
                    return;
                } else {

                    // Return Default Service Sales Rate
                    $rateObj = Rate::find($service->sales_rate_id);
                    if (! empty($rateObj)) {
                        $rate = $rateObj->toArray();
                        $rate['fuel_cap'] = 99;
                        $rate['discount'] = 0;
                        $rate['special_discount'] = 0;

                        return $rate;
                    }
                }
            } else {
                $rateObj = Rate::find($quotedRate['rate_id']);
                if (! empty($rateObj)) {
                    $rate = $rateObj->toArray();
                    $rate['fuel_cap'] = $quotedRate['fuel_cap'];
                    $rate['discount'] = $quotedRate['discount'];
                    $rate['special_discount'] = $quotedRate['special_discount'];

                    return $rate;
                }
            }
        }
    }

    public function surcharges()
    {
        return $this->hasMany(Surcharge::class);
    }

    /**
     * Returns Company specific Sales rate for specified service.
     *
     *
     * @return id of rate
     */
    public function getSurcharges($serviceId)
    {
        if (! empty($serviceId)) {

            // Get Services for my company for this service
            $charges = $this->surcharges()->where('service_id', $serviceId)->get();

            // If no Customer specific rate defined then use default rate from service
            if ($charges->isEmpty()) {
                return Surcharge::where('company_id', '0')->where('service_id', $serviceId)->get();
            } else {
                return $charges;
            }
        }
    }

    /**
     * Returns Company specific Sales rate for specified service.
     *
     *
     * @return id of rate
     */
    public function costRateForService($serviceId = '')
    {
        if (! empty($serviceId)) {
            $service = Service::find($serviceId);

            // If Service does not exist return null
            if (empty($service)) {
                return;
            } else {

                // Return Default Service Sales Rate
                $rateObj = Rate::find($service->cost_rate_id);
                if (! empty($rateObj)) {
                    $rate = $rateObj->toArray();
                    $rate['fuel_cap'] = 999;
                    $rate['discount'] = '';

                    return $rate;
                }
            }
        }
    }

    /**
     * Set address1.
     *
     * @param  string  $value
     * @return string
     */
    public function setAddress1Attribute($value)
    {
        $this->attributes['address1'] = ucwords(strtolower($value));
    }

    /**
     * Set address2.
     *
     * @param  string  $value
     * @return string
     */
    public function setAddress2Attribute($value)
    {
        $this->attributes['address2'] = ucwords(strtolower($value));
    }

    /**
     * Set address3.
     *
     * @param  string  $value
     * @return string
     */
    public function setAddress3Attribute($value)
    {
        $this->attributes['address3'] = ucwords(strtolower($value));
    }

    /**
     * Set the postcode.
     *
     * @param  string  $value
     * @return string
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = ucwords(strtolower($value));
    }

    /**
     * Set the state.
     *
     * @param  string  $value
     * @return string
     */
    public function setStateAttribute($value)
    {
        $this->attributes['state'] = ucwords(strtolower($value));
    }

    /**
     * Set the postcode.
     *
     * @param  string  $value
     * @return string
     */
    public function setPostcodeAttribute($value)
    {
        $this->attributes['postcode'] = strtoupper($value);
    }

    /**
     * Set the email.
     *
     * @param  string  $value
     * @return string
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Set the company code.
     *
     * @param  string  $value
     * @return string
     */
    public function setCompanyCodeAttribute($value)
    {
        $this->attributes['company_code'] = strtoupper($value);
    }

    /**
     * Get the company's salesperson.
     *
     * @return string
     */
    public function getSalespersonAttribute()
    {
        if ($this->sale) {
            return $this->sale->name;
        }
    }

    /**
     * Get the company's default label size.
     *
     * @return string
     */
    public function getLabelSizeAttribute()
    {
        if ($this->printFormat) {
            return $this->printFormat->name;
        }
    }

    /**
     * Get the country.
     *
     * @return string
     */
    public function getCountryAttribute()
    {
        return getCountry($this->country_code);
    }

    /**
     * Flag indicating if the company uses default services only.
     *
     * @return bool
     */
    public function getUsesDefaultServicesAttribute()
    {
        if ($this->services->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Flag indicating if the company uses defined services.
     *
     * @return bool
     */
    public function getUsesDefinedServicesAttribute()
    {
        if ($this->services->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('company_name', 'LIKE', '%'.$filter.'%')
                            ->orWhere('address1', 'LIKE', '%'.$filter.'%')
                            ->orWhere('address2', 'LIKE', '%'.$filter.'%')
                            ->orWhere('postcode', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeHasDepot($query, $depot)
    {
        if (is_numeric($depot)) {
            return $query->where('depot_id', $depot);
        }

        if ($depot) {
            return $query->select('companies.*')
                            ->join('depots', 'companies.depot_id', '=', 'depots.id')
                            ->where('depots.code', '=', $depot);
        }
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeHasTesting($query, $testing)
    {
        if (is_numeric($testing)) {
            return $query->where('testing', $testing);
        }
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeHasEnabled($query, $enabled)
    {
        if (is_numeric($enabled)) {
            return $query->where('enabled', $enabled);
        }
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeHasSalesperson($query, $saleId)
    {
        if (is_numeric($saleId)) {
            return $query->where('sale_id', $saleId);
        }
    }

    /**
     * Returns all Packaging Types available
     * To the company for the current mode
     * of Transport.
     *
     * @parm modeId Mode of shipment
     * @return CompanyPackagingType
     */
    public function getPackagingTypes($modeId)
    {
        if ($this->packagingTypes->count() > 0) {
            return $this->packagingTypes;
        }

        return CompanyPackagingType::whereCompanyId(0)->whereModeId($modeId)->get();
    }

    /**
     * Get all services available to a company.
     *
     * @return
     */
    public function getServices()
    {
        $services = $this->services()->orderBy('code')->orderBy('name')->get();

        if ($services->count() > 0) {
            return $services;
        }

        return Service::whereDefault(1)->whereDepotId($this->depot_id)->orderBy('code')->orderBy('name')->get();
    }

    /**
     * Get all services available to a company for a given mode.
     *
     * @return
     */
    public function getServicesForMode($modeId)
    {
        $services = $this->services()->whereModeId($modeId)->whereDepotId($this->depot_id)->get();

        if ($services->count() > 0) {
            return $services;
        }

        return Service::whereDefault(1)->whereModeId($modeId)->whereDepotId($this->depot_id)->orderBy('name')->get();
    }

    /**
     * Update a company's services.
     *
     * @param array $services       An array of service IDs
     * @param bool $useDefault   Use default services boolean
     *
     * @return mixed
     */
    public function syncServices($services, $useDefault)
    {
        if (! $services || $useDefault) {
            return $this->services()->detach();
        }

        return $this->services()->sync($services);
    }

    /**
     * Function accepts mode_id and Carrier Code then
     * builds an array of all Packaging Types available
     * for this Carrier inc the Carriers Equivalent code.
     *
     * @param int $modeId
     * @param string $carrierCode
     * @return array $packageTypeArray
     */
    public function buildPackageTypesArray($modeId, $carrierCode = null)
    {

        // Return array
        $types = [];

        $packagingTypes = $this->getPackagingTypes($modeId);

        // Build array of allowed PackageTypes and their Fedex equivalent
        $carrier = Carrier::where('code', $carrierCode)->first();

        if ($carrier) {
            foreach ($packagingTypes as $type) {
                $carrierPackaging = CarrierPackagingType::where('packaging_type_id', $type->packaging_type_id)
                        ->where('carrier_id', $carrier->id)
                        ->first();

                if (isset($carrierPackaging['code']) && $carrierPackaging['code'] > '') {
                    $types[$type->code] = $carrierPackaging['code'];
                }
            }
        }

        return $types;
    }

    /**
     * Identifies if User is a member of this company.
     *
     * @param int $userId
     * @return bool
     */
    public function hasUser($userId)
    {
        return ($this->users->where('id', $userId)->first()) ? true : false;
    }

    /**
     * Get last ship date.
     *
     * @param type $timeZone
     * @param type $format
     * @return string
     */
    public function getLastShipDate($timeZone = 'Europe/London', $format = 'd-m-Y H:i')
    {
        $lastShipment = Shipment::select('ship_date')->whereCompanyId($this->id)->whereReceived(1)->whereNotIn('status_id', [1, 7])->orderBy('ship_date', 'desc')->first();

        if ($lastShipment) {
            return $lastShipment->ship_date->timezone($timeZone)->format($format);
        }

        return 'Inactive';
    }
}
