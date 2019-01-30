<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FedexEas extends Model
{

    public function getSurcharge($postcode)
    {

        // Look to see if an EAS applies
        for ($i = strlen($postcode); $i >= 2; $i--) {

            $eas = $this->where('postcode', '=', substr($postcode, 0, $i))->first();

            if ($eas) {
                return $eas->type;
            }
        }
    }

}
