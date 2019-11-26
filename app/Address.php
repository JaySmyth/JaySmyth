<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'name',
        'company_name',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'postcode',
        'country_code',
        'telephone',
        'email',
        'type',
        'definition',
        'account_number',
        'company_id'
    ];

    /**
     * An address is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name . " - " . $this->company_name;
    }

    /**
     * Get the country.
     *
     * @return type
     */
    public function getCountryAttribute()
    {
        return getCountry($this->country_code);
    }

    /**
     * Set the postcode.
     *
     * @param string $value
     * @return string
     */
    public function setPostcodeAttribute($value)
    {
        $this->attributes['postcode'] = strtoupper($value);
    }

    /**
     * Set the type.
     *
     * @param string $value
     * @return string
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    /**
     * Scope definition.
     *
     * @param type $query
     * @param type $definition
     * @return type
     */
    public function scopeOfDefinition($query, $definition)
    {
        if (!in_array($definition, ['sender', 'recipient'])) {
            \App::abort(404);
        }
        return $query->where('definition', $definition);
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
                $query->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('company_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('postcode', 'LIKE', '%' . $filter . '%');
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
    public function scopeHasCity($query, $city)
    {
        if ($city) {
            return $query->where('city', 'LIKE', '%' . $city . '%');
        }
    }

    /**
     * Scope country.
     *
     * @param type $query
     * @param type $countryCode
     * @return type
     */
    public function scopeHasCountry($query, $countryCode)
    {
        if ($countryCode) {
            return $query->where('country_code', $countryCode);
        }
    }

    /*
     * Scope restrict results by company.
     *
     */

    public function scopeRestrictCompany($query, $companyIds)
    {
        return $query->whereIn('company_id', $companyIds);
    }

}
