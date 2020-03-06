<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAddress extends Model
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
    ];

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->company_name;
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
     * Set the type.
     *
     * @param  string  $value
     * @return string
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
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
                $query->where('name', 'LIKE', '%'.$filter.'%')
                                ->orWhere('company_name', 'LIKE', '%'.$filter.'%');
            });
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
            return $query->where('city', 'LIKE', '%'.$city.'%');
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
}
