<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomesticZone extends Model
{
    public function getZone($shipment)
    {
        $zone = '';
        $postcode = '';
        $postCodeFound = false;

        if (isset($shipment['recipient_postcode'])) {
            $postcode = trim($shipment['recipient_postcode']);
        }

        // Remove all extraneous chars and compare against FedexUK DB
        $newPostCode = preg_replace('/\s+/', ' ', $postcode); // Replace multiple spaces
        $newPostCode = trim($newPostCode); // Remove Leading and trailing spaces
        $newPostCode = preg_replace('/[^A-Za-z0-9 ]/', '', $newPostCode); // Replace invalid characters
        if ($newPostCode == $postcode) {

            // Retrieve Cutoff time for the given PostCode/ Part PostCode
            $result = false;
            $l = strlen($newPostCode);
            while ($l > 0) {

                // Must have at least 1 Char
                $zone = self::where('postcode', '=', substr($newPostCode, 0, $l))->first();
                if (! empty($zone)) {
                    $postCodeFound = true;

                    return $zone->zone;
                } else {
                    $l = $l - 1;
                }
            }
        }
    }
}
