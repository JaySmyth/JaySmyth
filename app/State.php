<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;

    /**
     * Accepts a country code and State name
     * and attempts to return the ansi state code.
     *
     * @param type string 2 Char Country Code
     * @param type string State name
     *
     * @return string Returns a 2 char state code or empty string
     */
    public static function getAnsiStateCode($countryCode, $name)
    {
        $state = self::where('country_code', $countryCode)->where('name', $name)->first();

        // State found, return the ansi code
        if ($state) {
            return $state->code;
        }

        // State not recognised, so try and guess it
        $states = self::where('country_code', $countryCode)->get();

        // Empty array for search results
        $result = [];

        foreach ($states as $state) {

            // Compares the strings and returns simularity as a percentage
            similar_text($state->name, $name, $simularity);

            // Disregard anything with a simularity of less than 70%
            if ($simularity > 70) {
                $result[] = [
                    'simularity' => $simularity,
                    'code' => $state->code,
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

            return $result['code'];
        }
    }
}
