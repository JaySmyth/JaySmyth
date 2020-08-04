<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XdpEas extends Model
{
    public function isOutOfArea($postcode)
    {

        // Look to see if an EAS applies
        for ($i = strlen($postcode); $i >= 2; $i--) {
            $eas = $this->where('postcode', '=', substr($postcode, 0, $i))->first();

            if ($eas) {
                return true;
            }
        }

        return false;
    }
}
