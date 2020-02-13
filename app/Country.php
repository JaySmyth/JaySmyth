<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Accepts a country name and attempts to find it's code.
     *
     * @param type string $countryName
     *
     * @return string
     */
    public function getCode($countryName)
    {
        $country = false;
        $countryName = strtoupper($countryName);

        if (strlen($countryName) == 2) {
            $country = $this->whereCountryCode($countryName)->first();
        } else {
            $country = $this->whereCountry($countryName)->first();
        }

        // Country found, return the code
        if ($country) {
            return $country->country_code;
        }

        // Country not recognised, so try and guess it
        $countries = $this->all();

        // Empty array for search results
        $result = [];

        foreach ($countries as $country) {

            // Compares the strings and returns simularity as a percentage
            similar_text($country->country, $countryName, $simularity);

            // Disregard anything with a simularity of less than 75%
            if ($simularity > 75) {
                $result[] = [
                    'simularity' => $simularity,
                    'code' => $country->country_code,
                ];
            }
        }

        if (count($result) > 0) {
            // Sort the array by simularity
            $result = array_values(array_sort($result, function ($value) {
                return $value['simularity'];
            }));

            // Get the result with the highest simularity
            $result = last($result);

            return $result['code'];
        }

        // Nothing found, try domestic strings
        return $this->getDomesticCode($countryName);
    }

    /**
     * Find country code for likely domestic country strings.
     *
     * @param type $string
     * @return string
     */
    private function getDomesticCode($string)
    {
        switch (strtoupper($string)) {
            case 'NI':
            case 'NIR':
            case 'N.I.':
            case 'N.IRELAND':
            case 'N. IRELAND':
            case 'N IRELAND':
            case 'NORTHERN IRELAND':
            case 'ENGLAND':
            case 'SCOTLAND':
            case 'WALES':
            case 'GREAT BRITAIN':
                return 'GB';
            case 'IRE':
            case 'IE':
            case 'I.E.':
            case 'I.E':
                return 'IE';
            default:
                return;
        }
    }
}
