<?php

namespace App\Models;

use App\Exports\PricingZonesExport;
use DB;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class PricingZones extends Model
{
    protected $guarded = ['id'];

    /**
     * @param array containing key/ value pairs of criteria to identify zone
     *
     * @return $zone
     */
    public function getZone($data, $model = '-1')
    {
        /*
         * ***************************************
         * Function to calculate the correct zone
         * based on supplied details
         * ***************************************
         */

        if ($model == '-1') {

            // Use default model for the carrier
            $criteria['model_id'] = $this->carrierModel[$data['carrier_id']];
        } else {

            // Request to use specific pricing model
            $criteria['model_id'] = $model;
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

    public function download($modelId, $serviceCode, $reqDate = '', $download = true)
    {
        if ($reqDate=='') {
            $reqDate = date('Y-m-d');
        }
        $data = PricingZones::select('company_id', 'model_id', 'sender_country_code', 'from_sender_postcode', 'to_sender_postcode', 'recipient_country_code', 'recipient_name', 'from_recipient_postcode', 'to_recipient_postcode', 'service_code', 'cost_zone', 'sale_zone', 'from_date', 'to_date')
                ->where('model_id', $modelId)
                ->where('service_code', $serviceCode)
                ->where('from_date', '<=', $reqDate)
                ->where('to_date', '>=', $reqDate)
                ->orderBy('model_id')
                ->orderBy('service_code')
                ->orderBy('sender_country_code')
                ->orderBy('recipient_country_code')
                ->orderBy('from_recipient_postcode')
                ->get()
                ->toArray();

        // Custom formating
        for ($i=0;$i<count($data);$i++) {
            $data[$i]['company_id'] = ($data[$i]['company_id'] == '') ? '0' : $data[$i]['company_id'];
            $data[$i]['model_id'] = ($data[$i]['model_id'] == '') ? '0' : $data[$i]['model_id'];
        }

        if (! empty($data)) {
            if ($download) {
                return Excel::download(
                    new PricingZonesExport($data),
                    'Pricing_Zones_'.$modelId.'_'.ucfirst($serviceCode).'.csv'
                );
            } else {
                return $data;
            }
        }
    }
}
