<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DhlEas extends Model
{
    public function isEAS($countryCode, $city = '', $postcode = '')
    {
        $postcode = trim(strtoupper($postcode));
        $postcode = str_ireplace('-', '', $postcode);
        $postcode = str_ireplace(' ', '', $postcode);
        $city = strtoupper($city);
        $countryCode = strtoupper($countryCode);
        $response = null;

        $length = strlen($postcode);
        for ($i = $length; $i >= 4; $i--) {
            if ($this->checkForMatch($countryCode, '', substr($postcode, 0, $i))) {
                return true;
            }
        }

        if ($this->checkForMatch($countryCode, $city)) {
            return true;
        }

        if ($this->checkForMatch($countryCode)) {
            return true;
        }

        return false;
    }

    public function checkForMatch($countryCode, $city = '', $postcode = '')
    {
        if ($postcode == '') {
            $eas = $this->where('recipient_country_code', $countryCode)
                    ->where('recipient_town', $city)
                    ->where('recipient_postcode', $postcode)
                    ->first();
        } else {
            $eas = $this->where('recipient_country_code', $countryCode)
                    ->where('recipient_postcode', $postcode)
                    ->first();
        }

        if ($eas) {
            return true;
        } else {
            return false;
        }
    }
}
