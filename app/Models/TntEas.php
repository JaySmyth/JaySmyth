<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TntEas extends Model
{
    public function isEas($countryCode = '', $postcode = '')
    {
        $eas = $this->where('country_code', $countryCode)->where('from_postcode', '<=', $postcode)->where('to_postcode', '>=', $postcode)->first();
        if ($eas) {
            return true;
        } else {
            return false;
        }
    }

    //
}
