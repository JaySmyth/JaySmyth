<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CongestionPostcode extends Model
{
    protected $fillable = [
        'carrier_id',
        'from_postcode',
        'to_postcode',
        'charge_type',
        'from_date',
        'to_date',
    ];

    public function isCongested($postcode)
    {

        // Look to see if postcode exists
        for ($i = strlen($postcode); $i >= 2; $i--) {
            $found = $this->where('from_postcode', '<=', substr($postcode, 0, $i))
            ->where('to_postcode', '>=', substr($postcode, 0, $i))
            ->first();

            if ($found) {
                return true;
            }
        }

        return false;
    }
}
