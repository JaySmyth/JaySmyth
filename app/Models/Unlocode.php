<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unlocode extends Model
{
    public $timestamps = true;

    /**
     * Packaging types.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getLoCode($countryCode, $locn = '', $town = '')
    {
        $unlocode = null;

        if ($locn > '') {
            $unlocode = Unlocode::where('country_code', $countryCode)->where('location', $locn)->first();
            if ($unlocode) {
                return $unlocode->country_code.$unlocode->location;
            }
        }

        $unlocode = Unlocode::where('country_code', $countryCode)
                        ->where(function ($query) use ($town) {
                            $query->where('name', $town)->orWhere('name_plain', $town);
                        })
                        ->first();

        if ($unlocode) {
            return $unlocode->country_code.$unlocode->location;
        }
    }
}
