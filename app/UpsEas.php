<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpsEas extends Model
{
    public function getSurcharge($countryCode, $postcode = '', $city = '')
    {
        $postcode = trim(strtoupper($postcode));
        $postcode = str_ireplace('-', '', $postcode);
        $city = strtoupper($city);
        $countryCode = strtoupper($countryCode);
        $response = null;

        // Get all records for country
        $eas = $this->where('recipient_country_code', $countryCode)->get();
        if ($eas) {

            // Loop through records and check if apply
            foreach ($eas as $rec) {
                $response = $this->findEas($rec, $postcode, $city);

                if ($response) {
                    return $response;
                }
            }
        }

        return $response;
    }

    public function findEas($eas, $postcode = '', $city = '')
    {

        // Check for postcode match
        if ($eas->from_recipient_postcode > '' && $eas->to_recipient_postcode > '') {
            if ($eas->from_recipient_postcode <= $postcode && $eas->to_recipient_postcode >= $postcode) {
                return $eas;
            }
        } else {

            // Check for city match
            if ($eas->recipient_city > '') {
                if (strtoupper($eas->recipient_city) == $city) {
                    return $eas;
                }
            } else {

                // Generic match
                return $eas;
            }
        }
    }
}
