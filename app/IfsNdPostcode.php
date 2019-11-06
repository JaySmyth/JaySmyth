<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IfsNdPostcode extends Model
{
    public function isServed($postcode)
    {

        // Look to see if postcode is "Not Served"
        for ($i = strlen($postcode); $i >= 2; $i--) {
            $notServed = $this->where('postcode', '=', substr($postcode, 0, $i))->first();

            if ($notServed) {
                return false;
            }
        }

        return true;
    }
}
