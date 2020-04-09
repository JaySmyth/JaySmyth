<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FedexRoute extends Model
{
    public $timestamps = false;

    /**
     * Get the route id from the parameters provided.
     *
     * @return int
     */
    public function getRouteId($shipment)
    {
        $code = $this->getRouteCode($shipment);

        return \App\Models\Route::whereCode($code)->first()->id;
    }

    /**
     * Get the route code from the parameters provided.
     *
     * @return string
     */
    public function getRouteCode($shipment)
    {

        // Any Dry Ice shipments must go via IFS Antrim depot
        if (isset($shipment['dry_ice_flag']) && $shipment['dry_ice_flag']) {
            return 'ANT';
        }

        // Any hazardous shipments must go via IFS Antrim depot
        if (isset($shipment['hazardous']) && ((is_numeric($shipment['hazardous']) || strtoupper($shipment['hazardous']) == 'E'))) {
            return 'ANT';
        }

        // Check service_code is defined
        if (! isset($shipment['service_code']) && isset($shipment['service_id'])) {
            $shipment['service_code'] = Service::find($shipment['service_id'])->code;
        }

        // Any IPF shipments must go via IFS Antrim depot
        if (isset($shipment['service_code']) && (strtoupper($shipment['service_code']) == 'IPF')) {
            return 'ANT';
        }

        // Any Shipments not PrePaid
        if (isset($shipment['bill_shipping']) && strtolower($shipment['bill_shipping']) != 'sender') {
            return 'ANT';
        }

        // Route remainder based on Country code/ Postcode
        if (isset($shipment['recipient_country_code'])) {

            // Any Domestic Shipments
            if (isUkDomestic(strtoupper($shipment['recipient_country_code']))) {
                return 'ANT';
            }

            // US is handled in the opposite way (i.e. it must be defined in fedex_routes). If a Zip is found in fedex_routes, the shipment can go direct to BFS
            if (strtoupper($shipment['recipient_country_code']) == 'US') {
                return $this->getUSRoute($shipment);
            }

            // Return Non US Route
            return $this->getNonUSRoute($shipment);
        }

        return 'BFS';
    }

    public function getUSRoute($shipment)
    {
        if (isset($shipment['recipient_postcode'])) {
            $fedexRoute = $this->whereCountryCode($shipment['recipient_country_code'])->whereZip($shipment['recipient_postcode'])->first();
            if (! is_null($fedexRoute)) {
                return 'BFS';
            }
        }

        return 'ANT';
    }

    public function getNonUSRoute($shipment)
    {

        // Non US country codes
        $fedexRoutes = $this->whereCountryCode($shipment['recipient_country_code'])->get();

        if ($fedexRoutes && isset($shipment['recipient_postcode']) && isset($shipment['service_code'])) {
            foreach ($fedexRoutes as $route) {
                if ($this->zipMatch($shipment['recipient_postcode'], $route) && $this->serviceMatch($shipment['service_code'], $route)) {
                    return 'ANT';
                }
            }
        }

        return 'BFS';
    }

    /**
     * Determine if we have a zip code match.
     *
     * @return bool
     */
    protected function zipMatch($zip, $route)
    {
        // Zips not defined
        if ($route->zip == '' && $route->zip_from == '' && $route->zip_to == '') {
            return true;
        }

        // Zips match
        if ($zip != '' && $zip == $route->zip) {
            return true;
        }

        // Check if zip has been defined as a range
        if (is_numeric($route->zip_from) && is_numeric($route->zip_to)) {

            // Within range
            if ($zip >= $route->zip_from && $zip <= $route->zip_to) {
                return true;
            }
        }

        // No match
        return false;
    }

    /**
     * Determine if we have a service match.
     *
     * @param type $service
     * @param type $route
     * @return bool
     */
    protected function serviceMatch($serviceCode, $route)
    {
        // Service not defined, so all services apply
        if (strlen($route->service) == 0) {
            return true;
        }

        // Match
        if (strtoupper($serviceCode) == $route->service) {
            return true;
        }

        return false;
    }
}
