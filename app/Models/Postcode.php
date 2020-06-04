<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;

    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Set the country code.
     *
     * @param  string  $value
     * @return void
     */
    public function setPostcodeAttribute($value)
    {
        $this->attributes['postcode'] = strtoupper($value);
    }

    /**
     * Set the country code.
     *
     * @param  string  $value
     * @return void
     */
    public function setCountryCodeAttribute($value)
    {
        $this->attributes['country_code'] = strtoupper($value);
    }

    /**
     * Set the country code.
     *
     * @param  string  $value
     * @return void
     */
    public function setCollectionRouteAttribute($value)
    {
        $this->attributes['collection_route'] = strtoupper($value);
    }

    /**
     * Set the country code.
     *
     * @param  string  $value
     * @return void
     */
    public function setDeliveryRouteAttribute($value)
    {
        $this->attributes['delivery_route'] = strtoupper($value);
    }

    /**
     * Searches for a postcode match, removing one character from the right
     * of the postcode until a match is found.
     *
     * @param type $postcode
     * @param type $countryCode
     * @return type
     */
    public function find($postcode, $countryCode = 'GB')
    {
        $postcode = ltrim($postcode);

        $l = strlen($postcode);

        for ($i = $l; $i >= 3; $i--) {
            $result = $this->where('postcode', substr($postcode, 0, $i))->where('country_code', strtolower($countryCode))->first();

            if ($result) {
                return $result;
            }
        }

        return false;
    }

    /**
     * Get the pickup time for a postcode.
     *
     * @param type $countryCode
     * @param type $postCode
     * @return string
     */
    public function getPickupTime($countryCode, $postcode)
    {
        $postcode = $this->find($postcode, $countryCode);

        if ($postcode) {
            return $postcode->pickup_time;
        }

        // No pickup time found
        return date('23:59:59');
    }

    /**
     * @param type $countryCode
     * @param type $postCode
     * @param type $timeZone
     * @param type $weekDaysOnly
     * @return type
     */
    public function getPickUpDate($countryCode, $postCode, $timeZone, $weekDaysOnly = true)
    {

        // Identify the Pickup Cutoff time
        $pickupTime = $this->getPickupTime($countryCode, $postCode);

        $cutoffTime = \Carbon\Carbon::createFromformat('Y-m-d H:i:s', date('Y-m-d').' '.$pickupTime, $timeZone);

        $now = \Carbon\Carbon::now($timeZone);

        if ($now->gt($cutoffTime)) {

            // Take into account the week-end
            if ($weekDaysOnly == true) {

                // If Weekend, next pickup is Monday
                if (date('D') == 'Fri' || date('D') == 'Sat' || date('D') == 'Sun') {
                    return date('Y-m-d', strtotime('next Monday'));
                }
            }

            return date('Y-m-d', strtotime('tomorrow'));
        } else {
            return date('Y-m-d');
        }
    }

    /**
     * Get the routing information for this postcode.
     * Days numbered 0-7 (Sunday = 0).
     *
     * @return array
     */
    public function getRouting($day = false)
    {
        $routing = [];

        for ($i = 0; $i <= 6; $i++) {
            $routing[$i] = [
                'day' => $i,
                'collection_time' => substr($this->pickup_time, 0, 5),
                'delivery_time' => substr($this->pickup_time, 0, 5),
                'collection_route' => $this->collection_route,
                'delivery_route' => $this->delivery_route,
            ];
        }

        if ($day !== false && isset($routing[$day])) {
            return $routing[$day];
        } elseif (count($routing) > 0) {
            return $routing;
        }

        return false;
    }
}
