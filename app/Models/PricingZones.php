<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class PricingZones extends Model
{
    /**
     * @param array containing key/ value pairs of criteria to identify zone
     *
     * @return $zone
     */
    public function getZone($data, $model = '')
    {
        /*
         * ***************************************
         * Function to calculate the correct zone
         * based on supplied details
         * ***************************************
         */

        if ($model > '') {

            // Request to use specific pricing model
            $criteria['model_id'] = $model;
        } else {

            // Use default model for the carrier
            $criteria['model_id'] = $this->carrierModel[$data['carrier_id']];
        }

        if (isset($data['ship_date'])) {
            $criteria['ship_date'] = date('Y-m-d', strtotime($data['ship_date']));
        } else {
            $criteria['ship_date'] = date('Y-m-d');
        }

        // Extract necessary values from data array
        $fields = ['company_id', 'sender_country_code', 'sender_postcode', 'service_code', 'recipient_country_code', 'recipient_postcode'];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $criteria[$field] = $data[$field];
            }
        }

        // Search using all details provided
        $zone = $this->searchForZone($criteria);
        if ($zone->count() == 0) {

            // No Company specific zones found. Try Default Company
            $criteria['company_id'] = '0';
            $zone = $this->searchForZone($criteria);
        }

        if (isset($zone[0])) {
            return $zone[0];
        } else {
            return $zone;
        }
    }

    /**
     * Tries different combinations of parameters to find appropriate zone.
     * @param array containing key/ value pairs of criteria to identify zone
     *
     * @return $zone
     */
    public function searchForZone($data)
    {

        // Firstly Check for specific postcode
        $zone = $this->findZones($data);

        // If zone not found remove recipient_postcode and try again
        if ($zone->count() == 0) {
            $parameters = $data;
            unset($parameters['recipient_postcode']);
            $zone = $this->findZones($parameters);
        }

        // If zone not found remove sender_postcode and try again
        if ($zone->count() == 0) {
            $parameters = $data;
            unset($parameters['sender_postcode']);
            $zone = $this->findZones($parameters);
        }

        // If zone not found remove recipient_postcode and sender_postcode then try again
        if ($zone->count() == 0) {
            $parameters = $data;
            unset($parameters['sender_postcode']);
            unset($parameters['recipient_postcode']);
            $zone = $this->findZones($parameters);
        }

        // Nothing found so return null
        return $zone;
    }

    public function scopeFindZones($query, $parameters)
    {
        foreach ($parameters as $parameter => $value) {
            if ($value > '') {
                switch ($parameter) {
                    case 'sender_postcode':
                    case 'recipient_postcode':
                        $query->where("from_$parameter", '<=', $value);
                        $query->where("to_$parameter", '>=', $value);
                        break;

                    case 'ship_date':
                        $query->where('from_date', '<=', $value);
                        $query->where('to_date', '>=', $value);
                        break;

                    default:
                        $query->where($parameter, '=', $value);
                        break;
                }
            }
        }

        return $query->orderBy('company_id', 'DESC')->orderBy('from_sender_postcode')->orderBy('from_recipient_postcode')->get();
    }
}
