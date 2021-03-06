<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TntPostcode extends Model
{
    /**
     * Lookup a TNT postcode for a given town / country code.
     *
     * @param type string $town
     *
     * @return string
     */
    public function getPostcode($countryCode, $town, $county)
    {
        $tntPostcode = $this->whereCountryCode($countryCode)->whereTown($town)->first();

        // Postcode found, return the code
        if ($tntPostcode) {
            return $tntPostcode->postcode;
        }

        // No exact match for town, so try similarity to account for typo
        $tntPostcodes = $this->whereCountryCode($countryCode)->get();

        // Empty array for search results
        $result = [];

        foreach ($tntPostcodes as $postcode) {

            // Compares the strings and returns simularity as a percentage
            similar_text(strtoupper($postcode->town), strtoupper($town), $simularity);

            // Disregard anything with a simularity of less than 80%
            if ($simularity > 90) {
                $result[] = [
                    'simularity' => $simularity,
                    'postcode' => $postcode->postcode,
                ];
            }
        }

        if (count($result) > 0) {
            // Sort the array by simularity
            $result = array_values(Arr::sort($result, function ($value) {
                return $value['simularity'];
            }));

            // Get the result with the highest simularity
            $result = last($result);

            return $result['postcode'];
        }

        $county = Str::afterLast($county, ' ');

        // Try a match at county level
        $tntPostcode = $this->where('town', 'LIKE', 'ALL OTHER COUNTY '.$county.'%')->first();

        // Postcode found, return the code
        if ($tntPostcode) {
            return $tntPostcode->postcode;
        }
    }
}
