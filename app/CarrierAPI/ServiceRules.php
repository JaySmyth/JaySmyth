<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CarrierAPI;

use App\Country;
use App\Carrier;
use App\PackagingType;
use App\CompanyPackagingType;

/**
 * Description of ServiceRules
 *
 * @author gmcbroom
 */
class ServiceRules
{

    public $debug = false;
    public $eol = "\n";

    public function isSuitable($shipment, $serviceDetails)
    {

        // Do we need to set the debug flag
        if (isset($shipment['debug']) && $shipment['debug'] == 'true') {
            $this->debug = true;
        }

        // Check Sender country code present
        if (!isset($shipment['sender_country_code']) || $shipment['sender_country_code'] == '') {
            return false;
        }

        // Check Recipient country code present
        if (!isset($shipment['recipient_country_code']) || $shipment['recipient_country_code'] == '') {
            return false;
        }

        // Do any PreProcessing Required
        $shipment = $this->preprocess($shipment);

        // Check Service is valid for this shipment
        $result = $this->doChecks($shipment, $serviceDetails);

        // Output Debug messages if required
        if ($this->debug) {
            $msg = ($result) ? " suitable" : " not suitable";
            echo "Service " . $serviceDetails['code'] . $msg . $this->eol;
        }

        return $result;
    }

    private function preprocess($shipment)
    {

        if (!isset($shipment['carrier_code'])) {
            $shipment['carrier_code'] = 'cost';
        }
        $shipment = $this->fixDescriptions($shipment);
        $shipment = fixShipmentCase($shipment);

        return $shipment;
    }

    /*
     * ***************************************************
     * SECTION - Custom service Checks
     * ***************************************************
     */

    private function fixDescriptions($shipment)
    {

        // Sort out Description of contents
        if (isset($shipment['contents'][0]['description'])) {

            // If Commodity set then use first commodity description
            $shipment['goods_description'] = $shipment['contents'][0]['description'];
            $shipment['documents_description'] = '';
        } elseif (isset($shipment['goods_description']) && $shipment['goods_description'] > '') {

            // If Goods Description already set then clear Documents Description
            $shipment['documents_description'] = '';
        }

        return $shipment;
    }

    private function doChecks($shipment, $serviceDetails)
    {

        // Check Service valid for this customer to this country
        if (!$this->country_filter($shipment, $serviceDetails)) {
            return false;
        }

        // Check service rules
        if (!$this->checkServiceRules($shipment, $serviceDetails)) {
            return false;
        }

        // Check Grouped tests
        if (!$this->checkGroupedTests($shipment, $serviceDetails)) {
            return false;
        }

        // uk24 Service specific checks
        if ($serviceDetails['code'] == 'uk24' && !$this->checkUk24($shipment, $serviceDetails)) {
            return false;
        }

        // Fastway ie48 Service specific checks
        if ($serviceDetails['code'] == 'ie48' && !$this->checkIe48($shipment, $serviceDetails)) {
            return false;
        }

        return true;
    }

    /*
     * *******************************************
     * Check individual fields
     * *******************************************
     */

    /**
     * Check Customer is allowed to use this Service
     * To the destination country
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return boolean
     */
    private function country_filter($shipment, $serviceDetails)
    {

        if (isset($serviceDetails['pivot']['country_filter']) && $serviceDetails['pivot']['country_filter'] > "") {

            if (substr($serviceDetails['pivot']['country_filter'], 0, 1) == "!") {

                if (strpos($serviceDetails['pivot']['country_filter'], $shipment['recipient_country_code']) !== false) {

                    return false;
                } else {

                    return true;
                }
            } else {

                if (strpos($serviceDetails['pivot']['country_filter'], $shipment['recipient_country_code']) !== false) {

                    return true;
                } else {

                    return false;
                }
            }
        } else {

            return true;
        }
    }

    /*
     * *******************************************
     * Check groups of fields
     * *******************************************
     */

    private function checkServiceRules($shipment, $serviceDetails)
    {

        // Output Debuging info if required
        if ($this->debug) {
            if (isset($shipment['service_code'])) {
                echo "Checking if " . $shipment['service_code'] . "suitable" . $this->eol;
            } else {
                echo "No Service Code Selected" . $this->eol;
            }
        }

        // Fields to check
        $checklist = ['carrier_code', 'code', 'sender_country_codes', 'recipient_country_codes', 'sender_postcode_regex', 'recipient_postcode_regex', 'packaging_types', 'min_weight', 'max_weight', 'max_pieces', 'max_dimension', 'max_girth', 'max_customs_value', 'hazardous',
            'dry_ice', 'alcohol', 'broker', 'eu', 'non_eu', 'account_number_regex'];

        foreach ($checklist as $test) {
            if ($this->debug) {
                echo "**************************************" . $this->eol;
                echo "Test : $test " . $serviceDetails['id'] . " - " . $serviceDetails['code'] . $this->eol;
                echo "**************************************" . $this->eol;
            }
            if ($serviceDetails[$test] > '' && $serviceDetails[$test] <> '0') {
                $result = $this->$test($shipment, $serviceDetails);
                if (!$result) {

                    // Test Failed - Return false - no need to do any more tests
                    if ($this->debug) {
                        echo "$test - failed" . $this->eol;
                        echo "Not Suitable" . $this->eol;
                    }
                    return false;
                }
            }
        }

        return true;
    }

    /*
     * ***********************************
     * Perform any necessary preprocessing
     * of the data
     * ***********************************
     */

    private function checkGroupedTests($shipment, $serviceDetails)
    {
        // Grouped tests
        $checklist = ['category', 'options'];

        foreach ($checklist as $test) {
            $result = $this->$test($shipment, $serviceDetails);
            if (!$result) {

                // Test Failed - Return false - no need to do any more tests
                if ($this->debug) {
                    echo "$test - failed" . $this->eol;
                    echo "Not Suitable" . $this->eol;
                }
                return false;
            }
        }

        return true;
    }

    private function checkUk24($shipment, $serviceDetails)
    {

        // Fail if service is uk24 and both origin & destination postcodes are in NI
        if (strtolower($serviceDetails['code']) == 'uk24') {

            if (substr($shipment['sender_postcode'], 0, 2) == substr($shipment['recipient_postcode'], 0, 2)) {

                if (substr($shipment['sender_postcode'], 0, 2) == 'BT') {

                    if ($this->debug) {
                        echo "UK24 service not suitable for local NI movements" . $this->eol;
                    }
                    return false;
                }
            }
        }

        return true;
    }

    /*
     * ******************************************
     * SECTION - Functions to check individual fields
     * ******************************************
     */

    private function checkIe48($shipment, $serviceDetails)
    {

        /*
         * **************************************************
         * Applies to Fastway only
         * Glen (NI) excluded as delivered by IFS
         *
         * Email From: Caroline Gordon Sent: 23-03-2017 11:49
         * **************************************************
         */
        if ($serviceDetails['id'] == 3 && ($shipment['company_id'] != 550)) {

            $packages = $shipment['packages'];
            foreach ($packages as $package) {

                $vol = $package['length'] * $package['width'] * $package['height'];
                if ($vol > 179641 || $package['weight'] > 30) {
                    return false;
                }
            }
        }

        return true;
    }

    private function carrier_code($shipment, $serviceDetails)
    {

        $result = true;

        if ($shipment['carrier_code'] > '' && $shipment['carrier_code'] != 'cost' && $shipment['carrier_code'] != 'price') {
            $carrier_id = Carrier::where('code', $shipment['carrier_code'])->first()->id;
            if ($serviceDetails['carrier_id'] != $carrier_id) {
                $result = false;
            }
        }

        return $result;
    }

    /*
     * ************************************
     * Check to see if EU shipments allowed
     * ************************************
     */

    private function code($shipment, $serviceDetails)
    {

        $result = true;

        if (isset($shipment['service_code']) && $shipment['service_code'] > '') {

            if ($this->debug) {
                echo "Checking Code : |", $shipment['service_code'], "| ServiceDetails Code : |", $serviceDetails['code'] . "|" . $this->eol;
            }
            $result = false;
            if (strcasecmp($shipment['service_code'], $serviceDetails['code']) == 0) {
                if ($this->debug) {
                    echo "Result : True" . $this->eol;
                }
                $result = true;
            }
        }

        return $result;
    }

    /*
     * ****************************************
     * Check to see if NON EU shipments allowed
     * ****************************************
     */

    private function eu($shipment, $serviceDetails)
    {

        // print_r($serviceDetails);

        if ($serviceDetails['eu'] == '0' && $this->isEuShipment($shipment)) {

            // EU shipment but not allowed so fail
            if ($this->debug) {
                echo "$test - Failed" . $this->eol;
            }

            return false;
        } else {

            return true;
        }
    }

    private function isEuShipment($shipment)
    {
        $senderInEu = $this->isEuCountry($shipment, 'sender');
        $recipientInEu = $this->isEuCountry($shipment, 'recipient');

        if ($this->debug) {
            echo "Sender : $senderInEu Recipient : $recipientInEu" . $this->eol;
        }

        // Check sender and recipient are both in the EU
        if (($senderInEu == $recipientInEu) && ($senderInEu)) {
            if ($this->debug) {
                echo "fn isEuShipment - passed" . $this->eol;
            }
            return true;
        } else {
            if ($this->debug) {
                echo "fn isEuShipment - failed" . $this->eol;
            }
            return false;
        }
    }

    private function isEuCountry($shipment, $addressType)
    {

        $country = Country::where('country_code', $shipment[$addressType . '_country_code'])->first();

        if ($country && isset($country->eu)) {

            return $country->eu;
        } else {

            $msg = "Fn isEuCountry : CountryCode - " . $shipment[$addressType . '_country_code'] . "\n\n";
            $msg .= "Unable to determine if $addressType Country is part of the EU\n\n";
            $msg .= "Shipment Details below\n\n";
            $msg .= json_encode($shipment);
            mail('it@antrim.ifsgroup.com', "Error in CarrierAPI\ServiceRules", $msg);
            return false;
        }
    }

    private function non_eu($shipment, $serviceDetails)
    {
        if ($serviceDetails['non_eu'] == '0' && $this->isEuShipment($shipment)) {

            // Not defined or does not matter
            if ($this->debug) {
                echo "Non EU - Not Required" . $this->eol;
            }

            return false;
        } else {

            return true;
        }
    }

    private function checkOption($options, $option_code, $supported)
    {

        // Check Option is required and if so, is it supported
        foreach ($options as $myOption) {
            if ($myOption == $option_code && !$supported) {

                // option is required but not supported
                if ($this->debug) {
                    echo "Option $option_code - failed" . $this->eol;
                }
                return false;
            }
        }

        // Either not required or required and supported
        if ($this->debug) {
            echo "Option $option_code - passed" . $this->eol;
        }
        return true;
    }

    private function sender_country_codes($shipment, $serviceDetails)
    {
        return $this->checkCountry($shipment, $serviceDetails['sender_country_codes'], 'sender_country_code');
    }

    /*
     * **********************************************
     * Note: functions below here have function names
     * which must be identical to table field names
     * **********************************************
     */

    private function checkCountry($shipment, $test, $addressType)
    {
        if (substr($test, 0, 1) == '!') {
            $negativeCondition = true;
            $test = substr($test, 1, 999);
        } else {
            $negativeCondition = false;
        }

        // Check for comma separated list of countries
        $countries = explode(',', $test);

        if ($negativeCondition) {

            // Fail on first match
            $result = true;
            foreach ($countries as $country) {

                // Check for Types not allowed
                if (strcasecmp($shipment[$addressType], $country) == 0) {
                    $result = false;
                }
            }
        } else {

            // Fail if no match
            $result = false;
            foreach ($countries as $country) {

                // Check for Types allowed
                if (strcasecmp($shipment[$addressType], $country) == 0) {
                    $result = true;
                    break;
                }
            }
        }

        // Return result
        if ($this->debug) {
            if ($result) {
                echo "$test - passed" . $this->eol;
            } else {
                echo "$test - failed" . $this->eol;
            }
        }

        return $result;
    }

    private function recipient_country_codes($shipment, $serviceDetails)
    {
        return $this->checkCountry($shipment, $serviceDetails['recipient_country_codes'], 'recipient_country_code');
    }

    private function sender_postcode_regex($shipment, $serviceDetails)
    {
        return preg_match($serviceDetails['sender_postcode_regex'], $shipment['sender_postcode']);
    }

    private function recipient_postcode_regex($shipment, $serviceDetails)
    {
        return preg_match($serviceDetails['recipient_postcode_regex'], $shipment['recipient_postcode']);
    }

    private function account_number_regex($shipment, $serviceDetails)
    {
        return true;
    }


    private function packaging_types($shipment, $serviceDetails)
    {

        /*
         * Note packaging_code may be the customers own
         * package type, so we need to identify what the 
         * IFS equivilant is.
         * 
         */
        if (substr($serviceDetails['packaging_types'], 0, 1) == '!') {
            $negativeCondition = true;
            $serviceDetails['packaging_types'] = substr($serviceDetails['packaging_types'], 1, 999);
        } else {
            $negativeCondition = false;
        }

        // Check for comma separated list of countries
        $listedCodes = explode(',', $serviceDetails['packaging_types']);
        foreach ($shipment['packages'] as $package) {

            // Check to see if this is a company specific packaging
            $companyPackaging = CompanyPackagingType::where('company_id', $shipment['company_id'])
                ->where('code', $package['packaging_code'])
                ->where('mode_id', $shipment['mode_id'])
                ->first();

            // Identify the IFS packaging type
            if ($companyPackaging) {
                $myPackaging = PackagingType::find($companyPackaging->packaging_type_id)->code;
            } else {
                $myPackaging = $package['packaging_code'];
            }

            if ($negativeCondition) {

                if ($this->debug) {
                    echo "Negative Condition" . $this->eol;
                }

                // Assume result to be true
                $result = true;

                // Fail on first match
                foreach ($listedCodes as $code) {

                    // Check for Types not allowed
                    if (strcasecmp($myPackaging, $code) == 0) {
                        if ($this->debug) {
                            echo "$myPackaging = $code - Failed" . $this->eol;
                        }

                        $result = false;
                    }
                }
            } else {

                if ($this->debug) {
                    echo "Positive Condition" . $this->eol;
                }
                // Fail if no match
                $result = false;
                foreach ($listedCodes as $code) {

                    // Check for Types not allowed
                    if (strcasecmp($myPackaging, $code) == 0) {
                        if ($this->debug) {
                            echo "$myPackaging = $code - Passed" . $this->eol;
                        }
                        $result = true;
                    }
                }
            }
        }

        if ($this->debug) {
            if ($result) {
                echo "Passed - $myPackaging : $code" . $this->eol;
            } else {
                echo "Failed - $myPackaging : $code" . $this->eol;
            }
        }

        return $result;
    }

    /**
     * Check min weight per PACKAGE
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return boolean
     */
    private function min_weight($shipment, $serviceDetails)
    {

        // If Service is IPF and Girth > 330, ignore min weight calc
        if ($serviceDetails['code'] == 'ipf') {

            if (count($shipment['packages']) == $shipment['pieces']) {
                for ($i = 0; $i < $shipment['pieces']; ++$i) {
                    $girth = girth($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                    if ($girth > 330) {

                        return true;
                    }
                }
            }
        }

        // If Even one piece is greater than or equal to min, then return true
        foreach ($shipment['packages'] as $package) {

            if ($package['weight'] >= $serviceDetails['min_weight']) {

                return true;
            }
        }

        return false;
    }

    /**
     * Check Maximum weight per PACKAGE
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return boolean
     */
    private function max_weight($shipment, $serviceDetails)
    {
        foreach ($shipment['packages'] as $package) {

            if ($package['weight'] > $serviceDetails['max_weight']) {

                return false;
            }
        }
        return true;
    }

    /**
     * Check Package girth for each package
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return boolean
     */
    private function max_girth($shipment, $serviceDetails)
    {

        // Check no single piece is greater than allowed girth
        if ($serviceDetails['max_girth'] > 0) {

            if (count($shipment['packages']) == $shipment['pieces']) {

                for ($i = 0; $i < $shipment['pieces']; ++$i) {

                    $girth = girth($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                    if ($girth > $serviceDetails['max_girth']) {

                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check have not exceeded no of pieces allowed
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return type
     */
    private function max_pieces($shipment, $serviceDetails)
    {
        return (($shipment['pieces'] <= $serviceDetails['max_pieces'])) ? true : false;
    }

    /**
     * Check Dimension of any package not Greater then max allowed
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return type
     */
    private function max_dimension($shipment, $serviceDetails)
    {

        foreach ($shipment['packages'] as $package) {

            $maxDim = max($package['length'], $package['width'], $package['height']);
            if (($maxDim > $serviceDetails['max_dimension'])) {

                return false;
            }
        }

        return true;
    }

    private function max_customs_value($shipment, $serviceDetails)
    {
        return (($shipment['customs_value'] <= $serviceDetails['max_customs_value'])) ? true : false;
    }

    private function hazardous($shipment, $serviceDetails)
    {
        if (!isset($shipment['hazardous']) || $shipment['hazardous'] == '' || strcasecmp($shipment['hazardous'], 'n') == 0) {

            // Non Hazardous goods so does not matter
            return true;
        } else {

            // Return whether supported
            return $serviceDetails['hazardous'];
        }
    }

    private function dry_ice($shipment, $serviceDetails)
    {
        if (!isset($shipment['dry_ice']['flag']) || $shipment['dry_ice']['flag'] == '0') {

            // Not dry ice shpment
            return true;
        } else {

            // Return whether supported
            return $serviceDetails['dry_ice'];
        }
    }

    private function alcohol($shipment, $serviceDetails)
    {
        if (!isset($shipment['alcohol_type']) || $shipment['alcohol_type'] == '' || strcasecmp($shipment['alcohol_type'], 'n') == 0) {

            // No alcohol
            return true;
        } else {

            // Return whether supported
            return $serviceDetails['alcohol'];
        }
    }

    private function broker($shipment, $serviceDetails)
    {
        if (!isset($shipment['broker_name']) || $shipment['broker_name'] == '' && $shipment['broker_company_name'] == '') {

            // Not a Broker select shipment
            return true;
        } else {

            // Return whether supported
            return $serviceDetails['broker'];
        }
    }

    private function category($shipment, $serviceDetails)
    {

        if (isset($shipment['ship_reason']) && (strcasecmp($shipment['ship_reason'], 'documents') == 0)) {
            $category = 'doc';
        } else if ($this->isEuShipment($shipment)) {
            $category = 'eu';
        } else {
            $category = 'nondoc';
        }

        if ($this->debug) {
            echo "Ship Reason : " . $shipment['ship_reason'] . "" . $this->eol;
            echo "Shipment is Category : $category" . $this->eol;
        }
        return $serviceDetails[$category];
    }

    private function options($shipment, $serviceDetails)
    {
        // Should allow multiple options to be passed
        $supported = false;

        // Is "options" defined
        if (isset($shipment['options'])) {

            // Is it an array
            if (is_array($shipment['options'])) {

                foreach ($shipment['options'] as $option) {

                    switch ($option) {
                        case '0900':
                            if ($serviceDetails['9am']) {
                                if ($this->debug) {
                                    echo "9am Supported" . $this->eol;
                                }
                                $supported = true;
                            }
                            break;

                        case '1030':
                            if ($serviceDetails['1030am']) {
                                if ($this->debug) {
                                    echo "1030 Supported" . $this->eol;
                                }
                                $supported = true;
                            }
                            break;

                        case '1200':
                            if ($serviceDetails['12pm']) {
                                if ($this->debug) {
                                    echo "12pm Supported" . $this->eol;
                                }
                                $supported = true;
                            }
                            break;

                        case 'APITEST':
                            $supported = true;
                            break;

                        default:
                            $supported = false;
                            break;
                    }
                }
            } else {

                if ($shipment['options'] == '') {

                    $supported = true;
                }
            }
        } else {

            $supported = true;
        }

        return $supported;
    }

}
