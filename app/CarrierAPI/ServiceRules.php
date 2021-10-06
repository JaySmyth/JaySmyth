<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CarrierAPI;

use App\Models\Carrier;
use App\Models\CompanyPackagingType;
use App\Models\Country;
use App\Models\IfsNdPostcode;
use App\Models\PackagingType;
use App\Models\Service;

/**
 * Description of ServiceRules.
 *
 * @author gmcbroom
 */
class ServiceRules
{
    public $debug = false;
    public $eol = "\n";
    public $checks = [];
    public $returnOnFail = true;

    public function isSuitable($shipment, $serviceDetails)
    {
        // Do we need to set the debug flag
        if (isset($shipment['debug']) && $shipment['debug'] == 'true') {
            $this->debug = true;
        }

        // Check Sender country code present
        if (! isset($shipment['sender_country_code']) || empty($shipment['sender_country_code'])) {
            $this->checks[] = "Failed - The Sender country code is not present";
            return false;
        } else {
            $this->checks[] = "Success - The Sender country code was provided";
        }

        // Check Recipient country code present
        if (! isset($shipment['recipient_country_code']) || empty($shipment['recipient_country_code'])) {
            $this->checks[] = "Failed - The Recipient country code is not present";
            return false;
        } else {
            $this->checks[] = "Success - The Recipient country code was provided";
        }

        // Do any PreProcessing Required
        $shipment = $this->preprocess($shipment);

        // Check Service is valid for this shipment
        $result = $this->doChecks($shipment, $serviceDetails);

        // Output Debug messages if required
        if ($this->debug) {
            $msg = ($result) ? ' suitable' : ' not suitable';
            echo 'Service '.$serviceDetails['code'].$msg.$this->eol;
        }
        return $result;
    }

    private function preprocess($shipment)
    {
        if (! isset($shipment['carrier_code'])) {
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

        // Check Account number known if billed to shipper
        //if (! $this->accountNumberKnown($shipment, $serviceDetails)) {
        //    return false;
        //}

        // Check Service valid for this customer to this country
        if (! $this->country_filter($shipment, $serviceDetails)) {
            $this->checks[] = "Failed - The service selected is not able to ship to this country";
            return false;
        } else {
            $this->checks[] = "Success - The service selected is able to ship to this country";
        }

        // Check service rules
        if (! $this->checkServiceRules($shipment, $serviceDetails)) {
            $this->checks[] = "Failed - There is an issue with the service rules";
            return false;
        }  else {
            $this->checks[] = "Success - Service rules passed";
        }

        // Check Grouped tests
        if (! $this->checkGroupedTests($shipment, $serviceDetails)) {
            $this->checks[] = "Failed - Check grouped tests";
            return false;
        }  else {
            $this->checks[] = "Success - Grouped tests passed";
        }

        // Check Service Specific tests
        if (! $this->serviceSpecificChecks($shipment, $serviceDetails)) {
            $this->checks[] = "Failed - Service specific tests failed";
            return false;
        }  else {
            $this->checks[] = "Success - Service specific tests passed";
        }

        return true;
    }

    private function serviceSpecificChecks($shipment, $serviceDetails)
    {

        // Service specific tests
        switch (strtolower($serviceDetails['code'])) {

            case 'ni24':
            case 'ni48':
                    return $this->checkNi($shipment, $serviceDetails);
                break;

            case 'uk24':
                    return $this->checkUk24($shipment, $serviceDetails);
                break;

            case 'ie48':
                    return $this->checkIe48($shipment, $serviceDetails);
                break;

            case 'uk48':
                return $this->checkUk48($shipment, $serviceDetails);
                break;

            /*
                * Reverted as per Anna 2021-09-10 14:30
            case 'uknc':
                return $this->checkUknc($shipment, $serviceDetails);
                break;
            */

        }

        // Lithium battery check
        if (! empty($shipment['lithium_batteries']) && $shipment['lithium_batteries'] > 0) {
            if (! $serviceDetails['lithium_batteries']) {
                return false;
            }
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
     * To the destination country.
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return bool
     */
    private function country_filter($shipment, $serviceDetails)
    {
        if (isset($serviceDetails['pivot']['country_filter']) && $serviceDetails['pivot']['country_filter'] > '') {
            if (substr($serviceDetails['pivot']['country_filter'], 0, 1) == '!') {
                if (stripos($serviceDetails['pivot']['country_filter'], $shipment['recipient_country_code']) !== false) {
                    return false;
                } else {
                    return true;
                }
            } else {
                if (stripos($serviceDetails['pivot']['country_filter'], $shipment['recipient_country_code']) !== false) {
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
        
        $passed = true;

        // Output Debuging info if required
        if ($this->debug) {
            if (isset($shipment['service_code'])) {
                echo 'Checking if '.$shipment['service_code'].'suitable'.$this->eol;
            } else {
                echo 'No Service Code Selected'.$this->eol;
            }
        }

        // Fields to check
        $checklist = ['carrier_code', 'code', 'sender_country_codes', 'recipient_country_codes', 'sender_postcode_regex', 'recipient_postcode_regex', 'packaging_types',
            'min_weight', 'max_weight', 'max_pieces', 'max_dimension', 'max_girth', 'max_customs_value', 'hazardous',
            'dry_ice', 'alcohol', 'broker', 'eu', 'non_eu', 'account_number_regex'];

        foreach ($checklist as $test) {
            if ($this->debug) {
                echo '**************************************'.$this->eol;
                echo "Test : $test ".$serviceDetails['id'].' - '.$serviceDetails['code'].$this->eol;
                echo '**************************************'.$this->eol;
            }
            if ($serviceDetails[$test] > '' && $serviceDetails[$test] != '0') {
                $result = $this->$test($shipment, $serviceDetails);
                if (! $result) {

                    // Test Failed - Return false - no need to do any more tests
                    if ($this->debug) {
                        echo "$test - failed".$this->eol;
                        echo 'Not Suitable'.$this->eol;
                    }

                    $passed  = false;
                    if ($this->returnOnFail) {
                        return $passed;
                    }
                }
            }
        }

        $serviceHeld = $this->isServiceHeld($shipment, $serviceDetails);
        if ($serviceHeld) {
            return false;
        }

        return $passed;
    }

    private function isServiceHeld($shipment, $serviceDetails)
    {

        // Patch to disable specific services between dates (Inclusive)
        $today = date('Y-m-d');
        $heldService = [
         //   '22' => ['startHold' => '2020-12-21', 'endHold' => '2021-01-07'],
         //   '24' => ['startHold' => '2020-12-21', 'endHold' => '2021-01-07'],
         //   '26' => ['startHold' => '2021-01-01', 'endHold' => '2021-01-07'],   // Release after DHL Service upgrade
         //   '57' => ['startHold' => '2021-01-01', 'endHold' => '2021-01-07'],   // Release after DHL Service upgrade
        ];
        if (isset($heldService[$serviceDetails['id']])) {
            if ($today >= $heldService[$serviceDetails['id']]['startHold'] && $today <= $heldService[$serviceDetails['id']]['endHold']) {
                return true;
            }

            if (isset($shipment['collection_date'])) {
                if ($shipment['collection_date'] >= $heldService[$serviceDetails['id']]['startHold'] && $shipment['collection_date'] <= $heldService[$serviceDetails['id']]['endHold']) {
                    return true;
                }
            }
        }

        return false;
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
            if (! $result) {

                // Test Failed - Return false - no need to do any more tests
                if ($this->debug) {
                    echo "$test - failed".$this->eol;
                    echo 'Not Suitable'.$this->eol;
                }

                return false;
            }
        }

        return true;
    }

    private function checkNi($shipment, $serviceDetails)
    {

        // For Glen Dimplex rules are different - use IFS regardless
        if ($shipment['company_id'] == 550) {
            if ($serviceDetails['carrier_id'] == 1) {
                return true;
            }

            return false;
        }

        // Exp Freight deliver to postcodes in ifs_nd_postcodes table, IFS deliver to the rest
        $ifsPostCodes = new IfsNdPostcode();
        $ifsArea = $ifsPostCodes->isServed($shipment['recipient_postcode']);
        if ($ifsArea && $serviceDetails['carrier_id'] == 1) {
            // Area served by IFS and Carrier is IFS
            return true;
        }
        if (! $ifsArea && $serviceDetails['carrier_id'] == 15) {
            // Area not served by IFS and Carrier is ExpressFreight
            return true;
        }

        return false;
    }

    private function checkUk24($shipment, $serviceDetails)
    {

        // Fail if service is uk24 and both origin & destination postcodes are in NI
        if (substr($shipment['sender_postcode'], 0, 2) == substr($shipment['recipient_postcode'], 0, 2)) {
            if (substr($shipment['sender_postcode'], 0, 2) == 'BT') {
                if ($this->debug) {
                    echo 'UK24 service not suitable for local NI movements'.$this->eol;
                }

                return false;
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
        return true;
    }

    private function checkUk48($shipment, $serviceDetails)
    {
        /*
         * **************************************************
         * Applies to DX - UK48 only
         *
         * Email From: Anna Platt Sent: 20-08-2021 11:14
         *       Recipient added 2021-09-01 - Anna Platt
         * **************************************************
         */
        if (isset($shipment['recipient_name']) && stripos($shipment['recipient_name'], 'Amazon') !== false) {
            return false;
        }

        if (isset($shipment['recipient_company_name']) && stripos($shipment['recipient_company_name'], 'Amazon') !== false) {
            return false;
        }

        return true;
    }

    private function checkUknc($shipment, $serviceDetails)
    {
        /*
         * **************************************************
         * Applies to XDP
         *
         * Email From: Anna Platt Sent: 10-09-2021 12:59
         *       Recipient added 2021-09-01 - Anna Platt
         * **************************************************
         */
        if (isset($shipment['recipient_name']) && stripos($shipment['recipient_name'], 'Amazon') !== false) {
            return false;
        }

        if (isset($shipment['recipient_company_name']) && stripos($shipment['recipient_company_name'], 'Amazon') !== false) {
            return false;
        }

        return true;
    }

    private function carrier_code($shipment, $serviceDetails)
    {
        $result = true;

        if ($shipment['carrier_code'] > '' && $shipment['carrier_code'] != 'cost' && $shipment['carrier_code'] != 'price') {
            //dd($shipment['carrier_code']);
            $carrier_id = Carrier::where('code', $shipment['carrier_code'])->first()->id;
            
            if ($serviceDetails['carrier_id'] != $carrier_id) {
                $this->checks[] = "Failed - The carrier selected does not match the customers selection!";
                $result = false;
            } else {
                $this->checks[] = "Success - Carrier ID has matched to the customers selection";
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
                echo 'Checking Code : |', $shipment['service_code'], '| ServiceDetails Code : |', $serviceDetails['code'].'|'.$this->eol;
                }
            $result = false;
            if (strcasecmp($shipment['service_code'], $serviceDetails['code']) == 0) {
                if ($this->debug) {
                    echo 'Result : True'.$this->eol;
                }
                $this->checks[] = "Success - Shipment service code and the Service code provided match";
                $result = true;
            } else {
                $this->checks[] = "Failed - Shipment service code and the service code provided do not match";
                echo $shipment['service_code'] . $serviceDetails['code'];
            }
        } else {
            $this->checks[] = "Failed - Shipment service code is not present";
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
                echo 'Failed'.$this->eol;
            }
            $this->checks[] = "Failed - This shipment has been designated for the EU and further checks should be carried out";
            return false;
        } else {
            $this->checks[] = "Success - This shipment has been designated for the EU";
            return true;
        }
    }

    private function isEuShipment($shipment)
    {
        $senderInEu = $this->isEuCountry($shipment, 'sender');
        $recipientInEu = $this->isEuCountry($shipment, 'recipient');

        if ($this->debug) {
            echo "Sender : $senderInEu Recipient : $recipientInEu".$this->eol;
        }

        // Check sender and recipient are both in the EU
        if (($senderInEu == $recipientInEu) && ($senderInEu)) {
            if ($this->debug) {
                echo 'fn isEuShipment - passed'.$this->eol;
            }

            return true;
        } else {
            if ($this->debug) {
                echo 'fn isEuShipment - failed'.$this->eol;
            }
            return false;
        }
    }

    private function isNonEuShipment($shipment)
    {
        return ! $this->isEuShipment($shipment);
    }

    private function isEuCountry($shipment, $addressType)
    {
        $country = Country::where('country_code', $shipment[$addressType.'_country_code'])->first();

        if ($country && isset($country->eu)) {
            return $country->eu;
        } else {
            $msg = 'Fn isEuCountry : CountryCode - '.$shipment[$addressType.'_country_code']."\n\n";
            $msg .= "Unable to determine if $addressType Country is part of the EU\n\n";
            $msg .= "Shipment Details below\n\n";
            $msg .= json_encode($shipment);
            mail('it@antrim.ifsgroup.com', "Error in CarrierAPI\ServiceRules", $msg);

            return false;
        }
    }

    private function non_eu($shipment, $serviceDetails)
    {
        if ($serviceDetails['non_eu'] == '0' && $this->isNonEuShipment($shipment)) {

            // Non EU shipments not allowed and shipment is for non EU
            if ($this->debug) {
                echo 'fn non_eu - failed'.$this->eol;
            }
            $this->checks[] = "Failed - This Service has been designated Non-EU and should be investigated further"; 
            return false;
        } else {
            $this->checks[] = "Success - This shipment has passed Non-EU checks"; 
            return true;
        }
    }

    private function checkOption($options, $option_code, $supported)
    {

        // Check Option is required and if so, is it supported
        foreach ($options as $myOption) {
            if ($myOption == $option_code && ! $supported) {

                // option is required but not supported
                if ($this->debug) {
                    echo "Option $option_code - failed".$this->eol;
                }

                return false;
            }
        }

        // Either not required or required and supported
        if ($this->debug) {
            echo "Option $option_code - passed".$this->eol;
        }

        return true;
    }

    private function sender_country_codes($shipment, $serviceDetails)
    {
        $result = $this->checkCountry($shipment['sender_country_code'], $serviceDetails['sender_country_codes']);
        if ($result) {
            $this->checks[] = "Success - Sender country code against Shipment code check is OK";
            return $result;
        } else {
            $this->checks[] = "Failed - Sender country code and service country code do not match";
            return $result;
        }
    }

    /*
     * **********************************************
     * Note: functions below here have function names
     * which must be identical to table field names
     * **********************************************
     */

    private function checkCountry($countryCode, $criteria)
    {
        // If criteria empty then return true
        if (empty($criteria)) {
            return true;
        }

        // Do I require a match or not
        $requiredResult = true;
        if (substr($criteria, 0, 1) == '!') {
            $requiredResult = false;
            $criteria = substr($criteria, 1, 999);
        }

        // Check for a match
        $result = false;
        $countries = explode(',', $criteria);
        foreach ($countries as $country) {
            if (strcasecmp($countryCode, $country) == 0) {
                $result = true;
            }
        }

        return ($requiredResult == $result);
    }

    private function recipient_country_codes($shipment, $serviceDetails)
    {
        $result = $this->checkCountry($shipment['recipient_country_code'], $serviceDetails['recipient_country_codes']);
        if ($result) {
            $this->checks[] = "Success - The Recipient country code checks returned correct";
            return $result;
        } else {
            $this->checks[] = "Failed - Recipient country code and service country code do not match";
            return $result;
        }
    }

    private function sender_postcode_regex($shipment, $serviceDetails)
    {
        $result = preg_match($serviceDetails['sender_postcode_regex'], $shipment['sender_postcode']);
        if ($result) {
            $this->checks[] = "Success - The senders postcode matches to the service provided";
            return $result;
        } else {
            $this->checks[] = "Failed - The senders postcode is not valid for the service provided";
            return $result;
        }
    }

    private function recipient_postcode_regex($shipment, $serviceDetails)
    {
        $result = preg_match($serviceDetails['recipient_postcode_regex'], $shipment['recipient_postcode'] ?? '');
        if ($result) {
            $this->checks[] = "Success - The recipients postcode matches to the service provided";
            return $result;
        } else {
            $this->checks[] = "Failed - The recipients postcode is not valid for the service provided";
            return $result;
        }
    }

    private function account_number_regex($shipment, $serviceDetails)
    {
        // If No Account numbers provided
        if (empty($shipment['bill_shipping_account']) || empty($shipment['bill_tax_duty_account'])) {
            return true;
        }

        // If only shipping account number provided
        if (strlen($shipment['bill_shipping_account']) > 0 && strlen($shipment['bill_tax_duty_account']) == 0) {
            $shipAcctOk = preg_match($serviceDetails['account_number_regex'], $shipment['bill_shipping_account']);
            if ($shipAcctOk) {
                $this->checks[] = "Success - Bill shipping is a valid format";
                // If Fedex and Sender pays Freight make sure it is one of our account numbers
                if ($shipment['bill_shipping'] == 'shipper' && $shipment['carrier_id'] == '2') {
                    $service = Service::where('account', $shipment['bill_shipping_account'])->first();
                    if (empty($service)) {
                        $this->checks[] = "Failed - Must be a known Fedex account";
                        $shipAcctOk = false;
                    }
                }
            }

            return $shipAcctOk;
        }

        // If only Duty/ Vat account number provided
        if (strlen($shipment['bill_tax_duty_account']) > 0 && strlen($shipment['bill_shipping_account']) == 0) {
            $result = preg_match($serviceDetails['account_number_regex'], $shipment['bill_tax_duty_account']);
            if ($result){
                $this->checks[] = "Success - Duty VAT account is correct format";
            } else {
                $this->checks[] = "Failed - Duty VAT account is incorrect format";
            }
            return $result;
        }

        // If Both Shipping and Duty accounts provided
        $result = preg_match($serviceDetails['account_number_regex'], $shipment['bill_shipping_account']) && preg_match($serviceDetails['account_number_regex'], $shipment['bill_tax_duty_account']);
        if($result){
            $this->checks[] = "Success - Shipping and Duty account is correct format";
        } else {
            $this->checks[] = "Failed - Shipping and Duty account is in an incorrect format";
        }
        return $result;
    }

    private function accountNumberKnown($shipment, $serviceDetails)
    {
        // If Sender pays Freight make sure it is one of our account numbers
        if ($shipment['bill_shipping'] == 'sender') {
            $service = Service::where('account', $shipment['bill_shipping_account'])->first();
            if (empty($service)) {
                return false;
            }
        }

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
                    echo 'Negative Condition'.$this->eol;
                }

                // Assume result to be true
                $result = true;

                // Fail on first match
                foreach ($listedCodes as $code) {

                    // Check for Types not allowed
                    if (strcasecmp($myPackaging, $code) == 0) {
                        if ($this->debug) {
                            echo "$myPackaging = $code - Failed".$this->eol;
                        }
                        $this->checks[] = "Failed - This packaging type is not suitable for this service";
                        $result = false;
                    }
                }
            } else {
                if ($this->debug) {
                    echo 'Positive Condition'.$this->eol;
                }
                // Fail if no match
                $this->checks[] = "Failed - This packaging type is not suitable for this service";
                $result = false;
                foreach ($listedCodes as $code) {

                    // Check for Types not allowed
                    if (strcasecmp($myPackaging, $code) == 0) {
                        if ($this->debug) {
                            echo "$myPackaging = $code - Passed".$this->eol;
                        }
                        $result = true;
                    }
                }
            }
        }

        if ($this->debug) {
            if ($result) {
                echo "Passed - $myPackaging : $code".$this->eol;
            } else {
                echo "Failed - $myPackaging : $code".$this->eol;
            }
        }

        return $result;
    }

    /**
     * Check min weight per PACKAGE.
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return bool
     */
    private function min_weight($shipment, $serviceDetails)
    {

        // If Service is IPF and Girth > 330, ignore min weight calc
        if ($serviceDetails['code'] == 'ipf') {
            if (count($shipment['packages']) == $shipment['pieces']) {
                for ($i = 0; $i < $shipment['pieces']; $i++) {
                    $girth = girth($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                    if ($girth > 330) {
                        $this->checks[] = "Success - the minimum weight for this service has been reached";
                        return true;
                    }
                }
            }
        }

        // If Even one piece is greater than or equal to min, then return true
        foreach ($shipment['packages'] as $package) {
            if ($package['weight'] >= $serviceDetails['min_weight']) {
                $this->checks[] = "Success - the minimum weight for this service has been reached";
                return true;
            }
        }
        $this->checks[] = "Failed - the minimum weight for this service has not been reached";
        return false;
    }

    /**
     * Check Maximum weight per PACKAGE.
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return bool
     */
    private function max_weight($shipment, $serviceDetails)
    {

        foreach ($shipment['packages'] as $package) {
            if ($package['weight'] > $serviceDetails['max_weight']) {
                $this->checks[] = "Failed - The max weight for this service has been exceeded" ;
                return false;
            } else {
                $this->checks[] = "Success - The max weight for this service has not been exceeded";
            }

            // Do not use volumetric weight for XDP
            // if ($serviceDetails['carrier_id'] != 16) {
            //    if (isset($package['volumetric_weight']) && $package['volumetric_weight'] > $serviceDetails['max_weight']) {
            //        return false;
            //    }
            // }
        }

        return true;
    }

    /**
     * Check Package girth for each package.
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return bool
     */
    private function max_girth($shipment, $serviceDetails)
    {

        // Check no single piece is greater than allowed girth
        if ($serviceDetails['max_girth'] > 0) {
            if (count($shipment['packages']) == $shipment['pieces']) {
                for ($i = 0; $i < $shipment['pieces']; $i++) {
                    $girth = girth($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                    if ($girth > $serviceDetails['max_girth']) {
                        $this->checks[] = "Failed - The max girth has been exceeded for this service";
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check have not exceeded no of pieces allowed.
     *
     * @param type $shipment
     * @param type $serviceDetails
     * @return type
     */
    private function max_pieces($shipment, $serviceDetails)
    {
        $result = (($shipment['pieces'] <= $serviceDetails['max_pieces'])) ? true : false;
        if($result){
            $this->checks[] = "Success - The maximum number of pieces has not been exceeded";
            return $result;
        } else {
            $this->checks[] = "Failed - The maximum number of pieces has been exceeded";
            return $result;
        }

    }

    /**
     * Check Dimension of any package not Greater then max allowed.
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
                $this->checks[] = " Failed - The max dimension limit of " . $maxDim . " has been exceeded for this service";
                return false;
            }
        }

        return true;
    }

    private function max_customs_value($shipment, $serviceDetails)
    {
        $result = (($shipment['customs_value'] <= $serviceDetails['max_customs_value'])) ? true : false;
        if($result){
            $this->checks[] = "Success - The max customs value has not been exceeded for this service";
            return $result;
        } else {
            $this->checks[] = "Failed - The max customs value has been exceeded for this service";
            return $result;
        }
    }

    private function hazardous($shipment, $serviceDetails)
    {
        if (! isset($shipment['hazardous']) || empty($shipment['hazardous']) || strcasecmp($shipment['hazardous'], 'n') == 0) {
            // Non Hazardous goods so does not matter
            $this->checks[] = "Success - No hazardous substances supplied with this shipment";
            return true;
        } else {
            // Return whether supported
            $this->checks[] = "Failed - Please note a hazardous substance has been noted and should be checked against the carrier";
            return $serviceDetails['hazardous'];
        }
    }

    private function dry_ice($shipment, $serviceDetails)
    {
        if (! isset($shipment['dry_ice']['flag']) || $shipment['dry_ice']['flag'] == '0') {
            // Not dry ice shpment
            $this->checks[] = "Success - No Dry Ice substances supplied with this shipment";
            return true;
        } else {
            // Return whether supported
            $this->checks[] = "Failed - Please note a Dry Ice content has been noted and should be checked against the carrier";
            return $serviceDetails['dry_ice'];
        }
    }

    private function alcohol($shipment, $serviceDetails)
    {
        if (! isset($shipment['alcohol_type']) || empty($shipment['alcohol_type']) || strcasecmp($shipment['alcohol_type'], 'n') == 0) {
            // No alcohol
            $this->checks[] = "Success - Alcohol substances supplied with this shipment";
            return true;
        } else {
            // Return whether supported
            $this->checks[] = "Failed - Please note a Dry Ice content has been noted and should be checked against the carrier";
            return $serviceDetails['alcohol'];
        }
    }

    private function broker($shipment, $serviceDetails)
    {
        if (! isset($shipment['broker_name']) || empty($shipment['broker_name']) && empty($shipment['broker_company_name'])) {
            // Not a Broker select shipment
            $this->checks[] = "Success - This is not a broker shipment";
            return true;
        } else {
            // Return whether supported
            $this->checks[] = "Failed - Please note this shipment has been flagged with Broker status and should be checked against the carrier";
            return $serviceDetails['broker'];
        }
    }

    private function category($shipment, $serviceDetails)
    {
        if (isset($shipment['ship_reason']) && (strcasecmp($shipment['ship_reason'], 'documents') == 0)) {
            $category = 'doc';
        } elseif ($this->isEuShipment($shipment)) {
            $category = 'eu';
        } else {
            $category = 'nondoc';
        }

        if ($this->debug) {
            echo 'Ship Reason : '.$shipment['ship_reason'].''.$this->eol;
            echo "Shipment is Category : $category".$this->eol;
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
                                    echo '9am Supported'.$this->eol;
                                }
                                $supported = true;
                            }
                            break;

                        case '1030':
                            if ($serviceDetails['1030am']) {
                                if ($this->debug) {
                                    echo '1030 Supported'.$this->eol;
                                }
                                $supported = true;
                            }
                            break;

                        case '1200':
                            if ($serviceDetails['12pm']) {
                                if ($this->debug) {
                                    echo '12pm Supported'.$this->eol;
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
                if (empty($shipment['options'])) {
                    $supported = true;
                }
            }
        } else {
            $supported = true;
        }

        return $supported;
    }
}
