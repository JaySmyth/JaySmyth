<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CarrierAPI;

use App\Models\Company;
use App\Models\CompanyPackagingType;
use App\Models\Country;
use App\Models\Postcode;
use App\Models\Service;
use App\Rules\DoesNotExist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

/**
 * APIShipment Class.
 *
 * This class contains an object orientated view of
 * the shipment. This is best suited to the external
 * API view. The class provides a function translate
 * which translates this object into the appropriate
 * Array for the CarrierAPI interface. It also provides
 * generic validation for the shipment
 *
 * @author gmcbroom
 */
class APIShipment
{
    public $data = [];
    public $output = [];
    private $fields;
    private $addressPrefix = [];
    private $txlt = [];
    private $errors = [];
    private $divisor = 5000;
    private $minAllowedWeight = 0;
    private $maxAllowedWeight = 2000;
    private $maxAllowedPieces = 9999;
    private $maxAllowedDimension = 9999;
    private $maxAllowedGirth = 9999;
    private $maxAllowedValue = 9999999;

    public function __construct()
    {
        // Initialize Object (Base level fields)
        $this->fields = 'transaction_id,company_code,collection_date,carrier,carrier_pickup_required,service_code,country_of_destination,shipment_reference,order_number,ship_reason,special_instructions';
        $this->fields .= 'pieces,weight,volumetric_weight,weight_uom,bill_shipping,bill_tax_duty,bill_shipping_account,bill_tax_duty_account';
        $this->fields .= 'customs_value,currency_code,goods_description,insurance_value,lithium_batteries,terms_of_sale,commercial_invoice_contents';
        $this->fields .= 'shipper,recipient,broker,packages,options,alerts,hazardous,alcohol,commodities,label_specification';
        $fieldArray = explode(',', $this->fields);
        foreach ($fieldArray as $field) {
            $this->data[$field] = '';
        }

        // Build Translation matrix
        $this->txlt['transaction_id'] = 'transaction_id';
        $this->txlt['company_code'] = 'company_code';
        $this->txlt['collection_date'] = 'collection_date';
        $this->txlt['carrier'] = 'carrier_code';
        $this->txlt['carrier_pickup_required'] = 'carrier_pickup_required';
        $this->txlt['service_code'] = 'service_code';
        $this->txlt['pieces'] = 'pieces';
        $this->txlt['weight'] = 'weight';
        $this->txlt['volumetric_weight'] = 'volumetric_weight';
        $this->txlt['weight_uom'] = 'weight_uom';
        $this->txlt['dimension_uom'] = 'dims_uom';
        $this->txlt['country_of_destination'] = 'country_of_destination';
        $this->txlt['shipment_reference'] = 'shipment_reference';
        $this->txlt['order_number'] = 'order_number';
        $this->txlt['ship_reason'] = 'ship_reason';
        $this->txlt['special_instructions'] = 'special_instructions';
        $this->txlt['currency_code'] = 'customs_value_currency_code';
        $this->txlt['bill_shipping'] = 'bill_shipping';
        $this->txlt['bill_shipping_account'] = 'bill_shipping_account';
        $this->txlt['bill_tax_duty'] = 'bill_tax_duty';
        $this->txlt['bill_tax_duty_account'] = 'bill_tax_duty_account';
        $this->txlt['shipper.contact'] = 'name';
        $this->txlt['shipper.company_name'] = 'company_name';
        $this->txlt['shipper.address1'] = 'address1';
        $this->txlt['shipper.address2'] = 'address2';
        $this->txlt['shipper.address3'] = 'address3';
        $this->txlt['shipper.city'] = 'city';
        $this->txlt['shipper.state'] = 'state';
        $this->txlt['shipper.postcode'] = 'postcode';
        $this->txlt['shipper.country_code'] = 'country_code';
        $this->txlt['shipper.email'] = 'email';
        $this->txlt['shipper.telephone'] = 'telephone';
        $this->txlt['shipper.type'] = 'type';
        $this->txlt['recipient.contact'] = 'name';
        $this->txlt['recipient.company_name'] = 'company_name';
        $this->txlt['recipient.address1'] = 'address1';
        $this->txlt['recipient.address2'] = 'address2';
        $this->txlt['recipient.address3'] = 'address3';
        $this->txlt['recipient.city'] = 'city';
        $this->txlt['recipient.state'] = 'state';
        $this->txlt['recipient.postcode'] = 'postcode';
        $this->txlt['recipient.country_code'] = 'country_code';
        $this->txlt['recipient.email'] = 'email';
        $this->txlt['recipient.telephone'] = 'telephone';
        $this->txlt['recipient.type'] = 'type';
        $this->txlt['broker.account'] = 'account';
        $this->txlt['broker.contact'] = 'name';
        $this->txlt['broker.company_name'] = 'company_name';
        $this->txlt['broker.address1'] = 'address1';
        $this->txlt['broker.address2'] = 'address2';
        $this->txlt['broker.address3'] = 'address3';
        $this->txlt['broker.city'] = 'city';
        $this->txlt['broker.state'] = 'state';
        $this->txlt['broker.postcode'] = 'postcode';
        $this->txlt['broker.country_code'] = 'country_code';
        $this->txlt['broker.email'] = 'email';
        $this->txlt['broker.identifier'] = 'id';
        $this->txlt['broker.telephone'] = 'telephone';
        $this->txlt['broker.type'] = 'type';
        $this->txlt['packages.*.package_index'] = 'index';
        $this->txlt['packages.*.packaging_code'] = 'packaging_code';
        $this->txlt['packages.*.length'] = 'length';
        $this->txlt['packages.*.width'] = 'width';
        $this->txlt['packages.*.height'] = 'height';
        $this->txlt['packages.*.weight'] = 'weight';
        $this->txlt['packages.*.volumetric_weight'] = 'volumetric_weight';
        $this->txlt['packages.*.dry_ice_weight'] = 'dry_ice_weight';
        $this->txlt['options'] = 'options';
        $this->txlt['documents_flag'] = 'documents_flag';
        $this->txlt['hazardous.code'] = 'hazardous';
        $this->txlt['hazardous.flag'] = 'hazard_flag';
        $this->txlt['hazardous.class'] = 'hazard_class';
        $this->txlt['hazardous.excepted_qty'] = 'hazard_excepted_qty';
        $this->txlt['alcohol.packaging'] = 'packaging';
        $this->txlt['alcohol.quantity'] = 'quantity';
        $this->txlt['alcohol.type'] = 'type';
        $this->txlt['alcohol.volume'] = 'volume';
        $this->txlt['customs_value'] = 'customs_value';
        $this->txlt['goods_description'] = 'goods_description';
        $this->txlt['commercial_invoice_comments'] = 'commercial_invoice_comments';
        $this->txlt['commodities.*.package_index'] = 'index';
        $this->txlt['commodities.*.manufacturer'] = 'manufacturer';
        $this->txlt['commodities.*.country_of_manufacture'] = 'country_of_manufacture';
        $this->txlt['commodities.*.description'] = 'description';
        $this->txlt['commodities.*.export_license'] = 'export_license';
        $this->txlt['commodities.*.export_license_date'] = 'export_license_date';
        $this->txlt['commodities.*.harmonized_code'] = 'harmonized_code';
        $this->txlt['commodities.*.commodity_code'] = 'commodity_code';
        $this->txlt['commodities.*.package_index'] = 'package_index';
        $this->txlt['commodities.*.part_number'] = 'product_code';
        $this->txlt['commodities.*.quantity'] = 'quantity';
        $this->txlt['commodities.*.uom'] = 'uom';
        $this->txlt['commodities.*.unit_value'] = 'unit_value';
        $this->txlt['commodities.*.currency_code'] = 'currency_code';
        $this->txlt['commodities.*.unit_weight'] = 'unit_weight';
        $this->txlt['commodities.*.weight_uom'] = 'weight_uom';
        $this->txlt['terms_of_sale'] = 'terms_of_sale';
        $this->txlt['insurance_value'] = 'insurance_value';
        $this->txlt['lithium_batteries'] = 'lithium_batteries';
        $this->txlt['label_specification.image_type'] = 'image_type';
        $this->txlt['label_specification.label_size'] = 'label_size';

        $this->txlt['alerts.*.despatched'] = 'despatched';
        $this->txlt['alerts.*.collected'] = 'collected';
        $this->txlt['alerts.*.out_for_delivery'] = 'out_for_delivery';
        $this->txlt['alerts.*.delivered'] = 'delivered';
        $this->txlt['alerts.*.cancelled'] = 'cancelled';
        $this->txlt['alerts.*.problems'] = 'problems';

        $this->addressPrefix = ['shipper' => 'sender_', 'recipient' => 'recipient_', 'broker' => 'broker_'];
    }

    /**
     * Function loads Shipment data into class variables.
     *
     * @param  array  $shipmentData
     *
     * @return none Updates $this->errors
     */
    public function load($shipmentData)
    {
        $fields = array_keys($shipmentData);
        foreach ($fields as $field) {
            if (isset($this->$field)) {
                $this->data[$field] = $shipmentData[$field];
            }
        }
    }

    /**
     * Function loads Shipment data into class variables.
     *
     * @param  array  $shipment
     *
     * @return none Updates $this->errors
     */
    public function loadFromJSON($shipmentJSON)
    {
        $shipment = json_decode($shipmentJSON, true);

        // Check for empty array (if charset not UTF-8)
        if (! empty($shipment)) {
            $fields = array_keys($shipment);

            foreach ($fields as $field) {
                if (isset($shipment[$field])) {
                    $this->data[$field] = $shipment[$field];
                }
            }
        }
    }

    /**
     * Function clears all errors caught.
     *
     * @return none Clears $this->errors
     */
    public function clearErrors()
    {
        unset($this->errors);
        $this->errors = [];
    }

    /**
     * Function to translate from External API
     * format to Internal API format.
     */
    public function translate()
    {
        $this->fixFlags();

        $billTo = ['shipper' => 'sender', 'recipient' => 'recipient', 'other' => 'other'];

        foreach ($this->txlt as $field => $txltField) {
            $fieldArray = explode('.', $field);

            switch ($fieldArray[0]) {
                case 'bill_shipping':
                case 'bill_tax_duty':
                    if (isset($billTo[strtolower($this->data[$field])])) {
                        $this->output[$txltField] = $billTo[strtolower($this->data[$field])];
                    }
                    break;

                case 'shipper':
                case 'recipient':
                case 'broker':

                    if (isset($this->data[$fieldArray[0]][$fieldArray[1]])) {
                        if ($txltField == 'type') {
                            $this->output[$this->addressPrefix[$fieldArray[0]].$txltField] = strtolower($this->data[$fieldArray[0]][$fieldArray[1]]);
                        } else {
                            $this->output[$this->addressPrefix[$fieldArray[0]].$txltField] = $this->data[$fieldArray[0]][$fieldArray[1]];
                        }
                    }

                    break;

                case 'commodities':
                    $field = ['packages' => 'packages', 'commodities' => 'contents'];

                    // Output array of values
                    $cnt = 0;
                    if (! empty($this->data[$fieldArray[0]])) {
                        foreach ($this->data[$fieldArray[0]] as $item) {
                            if (isset($item[$fieldArray[2]])) {
                                $this->output[$field[$fieldArray[0]]][$cnt][$txltField] = $item[$fieldArray[2]];
                            }
                            $cnt++;
                        }
                    }
                    break;

                case 'packages':
                    $field = ['packages' => 'packages', 'commodities' => 'contents'];

                    // Output array of values
                    $cnt = 0;
                    if (isset($this->data[$fieldArray[0]]) && is_array($this->data[$fieldArray[0]])) {
                        foreach ($this->data[$fieldArray[0]] as $item) {
                            if (isset($item[$fieldArray[2]])) {
                                $this->output[$field[$fieldArray[0]]][$cnt][$txltField] = $item[$fieldArray[2]];
                            }
                            $cnt++;
                        }
                    }
                    break;

                case 'alcohol':
                case 'label_specification':
                    // Output at same level
                    if (isset($this->data[$fieldArray[0]][$fieldArray[1]])) {
                        $this->output[$fieldArray[0]][$txltField] = $this->data[$fieldArray[0]][$fieldArray[1]];
                    }
                    break;

                case 'alerts':
                case 'hazardous':
                    // Output at base level
                    if (isset($this->data[$fieldArray[0]][$fieldArray[1]])) {
                        $this->output[$txltField] = $this->data[$fieldArray[0]][$fieldArray[1]];
                    }
                    break;

                case 'goods_description':
                    if (isset($this->data['documents_flag']) && $this->data['documents_flag']) {
                        $this->output['documents_description'] = $this->data['goods_description'];
                    } else {
                        $this->output['goods_description'] = $this->data['goods_description'];
                    }
                    break;

                default:
                    if (isset($this->data[$field])) {
                        $this->output[$txltField] = $this->data[$field];
                    }
                    break;
            }
        }
    }

    /**
     * Processes $this->input and Converts specified
     * fields flags from Y/ N to boolean in $this->input.
     *
     * @return  none  - Updates $this->input
     */
    private function fixFlags()
    {
        $fields = 'carrier_pickup_required, documents_flag';

        $opts = ['N' => '0', 'Y' => '1'];
        $fldArray = explode(',', $fields);
        foreach ($fldArray as $field) {
            if (isset($this->data[$field])) {
                if (isset($opts[$this->data[$field]])) {
                    $this->data[$field] = $opts[$this->data[$field]];
                }
            }
        }
    }

    /*
     * ************************************
     * Validator Functions
     * ************************************
     */

    public function validateDeleteShipment($data)
    {
        $errors = [];

        $rules['user_id'] = 'required|exists:users,id';
        $rules['shipment_token'] = 'required|exists:shipments,token';

        // Do Generic validation
        $dataValidation = Validator::make($data, $rules);

        if ($dataValidation->fails()) {
            // Return errors as an array
            $errors = $this->buildValidationErrors($dataValidation->errors());
        }

        return $errors;
    }

    public function buildValidationErrors($messages)
    {
        foreach ($messages->all() as $message) {
            if ($message == 'validation.not_regex') {
                $errors[] = 'Please provide a valid commodity description';
            } else {
                $errors[] = ucfirst($message);
            }
        }

        return $errors;
    }

    public function validateShipment($shipment)
    {
        $errors = [];

        $rules = $this->addShipmentRules($shipment);

        // Do Generic validation
        $shipmentValidation = Validator::make($shipment, $rules);

        if ($shipmentValidation->fails()) {
            // Return errors as an array
            $errors = $this->buildValidationErrors($shipmentValidation->errors());
        } else {
            // Do Account Validation
            $errors = $this->checkPayAccounts($shipment, $errors);

            // Do Postcode Validation
            $errors = $this->checkPostCodes($shipment, $errors);

            // Validate based on Service limits
            $errors = $this->checkPackageLimits($shipment, $errors);

            // Validate Commodity details
            // $errors = $this->checkCommodityDetails($shipment, $errors);

            // Validate based on Country Restrictions
            $errors = $this->checkCountryRestrictions($shipment, $errors);

            // Check collection date is valid
            $errors = $this->checkCollectionDate($shipment, $errors);
        }

        return $errors;
    }

    public function addShipmentRules($shipment)
    {
        $rules['user_id'] = 'required|exists:users,id';
        $rules['service_id'] = 'required|exists:services,id';
        $rules['company_id'] = 'required|exists:companies,id';
        $rules['department_id'] = 'required|exists:departments,id';
        $rules['mode_id'] = 'required|exists:modes,id';
        $rules['transaction_id'] = 'nullable|string';
        $rules['collection_date'] = 'required|date|after:yesterday';
        $rules['carrier_code'] = 'required|exists:carriers,code';
        $rules['carrier_pickup_required'] = 'nullable|in:0,1';
        $rules['service_code'] = 'required|exists:services,code';
        $rules['pieces'] = 'required|integer|min:1|max:99';
        $rules['weight'] = 'required|numeric|greater_than_value:0|max:19999';
        // $rules['volumetric_weight'] = 'nullable|numeric|greater_than_value:0|max:19999';
        $rules['weight_uom'] = 'required|in:kg,lb';
        $rules['dims_uom'] = 'required|in:cm,in';
        $rules['country_of_destination'] = 'required|exists:countries,country_code';
        $rules['shipment_reference'] = 'required|string';
        $rules['ship_reason'] = 'required|exists:ship_reasons,code';
        $rules['special_instructions'] = 'nullable|string';
        $rules['bill_shipping'] = 'nullable|in:sender,recipient,other';
        $rules['bill_tax_duty'] = 'nullable|in:sender,recipient,other';
        $rules['bill_shipping_account'] = 'nullable|string';
        $rules['bill_tax_duty_account'] = 'nullable|string';

        $rules = $this->addContactRules('sender', $rules);
        $rules = $this->addAddressRules('sender', $rules);

        $rules = $this->addContactRules('recipient', $rules);
        $rules = $this->addAddressRules('recipient', $rules);

        if (isset($shipment['other_name']) || isset($shipment['other_company_name'])) {
            $rules = $this->addContactRules('other', $rules);
            $rules = $this->addAddressRules('other', $rules);
        }
        if (isset($shipment['BrokerSelect'])) {
            $rules['BrokerSelect'] = 'in:N,Y';
            $rules = $this->addContactRules('broker', $rules);
            $rules = $this->addAddressRules('broker', $rules);
        }
        $rules['fragile_goods_flag'] = 'nullable|in:1,0';
        $rules['liquid_flag'] = 'nullable|in:1,0';

        // Check that Terms of Sale are valid incoterms
        if ($shipment['sender_country_code'] == $shipment['recipient_country_code']) {
            $rules['terms_of_sale'] = 'nullable|exists:terms,code';
        } else {
            $rules['terms_of_sale'] = 'required|exists:terms,code';
        }

        // Check Incoterm used is valid for Duty payment type
        if (isset($shipment['bill_tax_duty']) && $shipment['bill_tax_duty'] == 'sender') {
            $rules['terms_of_sale'] = 'required|in:ddp';
        }
        if (isset($shipment['bill_tax_duty']) && $shipment['bill_tax_duty'] == 'recipient') {
            $rules['terms_of_sale'] = 'required|not_in:ddp';
        }

        $rules['customs_value'] = 'required|numeric';
        $rules['customs_value_currency_code'] = 'nullable|exists:currencies,code';
        $rules['insurance_value'] = 'nullable|integer';
        $rules['lithium_batteries'] = 'nullable|integer';

        $rules = $this->addPackagingRules($shipment['company_id'], $shipment['mode_id'], $rules);

        $rules['packages.*.weight'] = 'required|numeric|greater_than_value:0|max:9999';
        $rules['packages.*.length'] = 'required|integer|min:1|max:999';
        $rules['packages.*.width'] = 'required|integer|min:1|max:999';
        $rules['packages.*.height'] = 'required|integer|min:1|max:999';

        $rules['special_services'] = 'nullable|exists:special_services,code,company_id';

        if (isset($shipment['alerts']) && $shipment['alerts'] > 0) {
            $rules['alerts.*.despatched'] = 'nullable|in:0,1';
            $rules['alerts.*.collected'] = 'nullable|in:0,1';
            $rules['alerts.*.out_for_delivery'] = 'nullable|in:0,1';
            $rules['alerts.*.delivered'] = 'nullable|in:0,1';
            $rules['alerts.*.cancelled'] = 'nullable|in:0,1';
            $rules['alerts.*.problems'] = 'nullable|in:0,1';
            $rules['other_email'] = 'nullable|email';
        }

        $rules['documents_description'] = 'nullable:|string';
        $rules['goods_description'] = 'nullable:|string';

        // Set Dry Ice rules
        switch ($shipment['weight_uom']) {
            case 'KG':
                $rules['dry_ice_weight'] = 'nullable|numeric|min:1|max:200';
                break;

            case 'LB':
                $rules['dry_ice_weight'] = 'nullable|numeric|min:1|max:440';
                break;

            default:
                $rules['dry_ice_weight'] = 'nullable|numeric|min:1|max:200';
                break;
        }

        // Set Hazardous rules
        $rules['hazard.commodity_count'] = 'nullable|integer';
        $rules['hazardous'] = 'nullable|exists:hazards,code';

        if (isset($shipment['alcohol'])) {
            $rules['alcohol.type'] = 'nullable|in:A,B,L,S,W';
            $rules['alcohol.packaging'] = 'required_with:alcohol.type|string';
            $rules['alcohol.volume'] = 'required_with:alcohol.type|numeric';
            $rules['alcohol.quantity'] = 'required_with:alcohol.type|numeric';
        }

        $rules['commercial_invoice_comments'] = 'nullable|string';
        if (isset($shipment['commodity_count']) && $shipment['commodity_count'] > 0) {
            $rules['contents.*.description'] = ['required', 'string', 'min:3', 'max:100', 'not_regex:/^[0-9]+$/', new DoesNotExist('invalid_commodity_descriptions', 'description')];
            $rules['contents.*.quantity'] = 'required|integer|greater_than_value:0';
            $rules['contents.*.uom'] = 'required|exists:uoms,code';
            $rules['contents.*.unit_value'] = 'required|numeric|greater_than_value:0';
            $rules['contents.*.weight_uom'] = 'required|in:kg,lb';
            $rules['contents.*.country_of_manufacture'] = 'required|alpha|size:2';
            $rules['contents.*.unit_weight'] = 'numeric|greater_than_value:0';

            if (strtoupper($shipment['sender_country_code']) == 'GB' && ! isUkDomestic(strtoupper($shipment['recipient_country_code']))) {
                //$rules['eori'] = 'required|string|min:12|max:14';
            }
        }

        $rules['label_specification.label_size'] = 'nullable|in:6X4,A4';
        $rules['label_specification.label_type'] = 'nullable|in:PDF';

        return $rules;
    }

    /**
     * Create validation rules for a Contact.
     *
     * @param  string Contact Type
     * @param  array Rules
     *
     * @return array Validation rules
     */
    public function addContactRules($contactType = 'recipient', $rules = '')
    {
        $required = $this->detailsRequired($contactType);

        $rules[$contactType.'_name'] = 'nullable|required_without:'.$contactType.'_company_name'.'|string|max:35';
        $rules[$contactType.'_company_name'] = 'nullable|required_without:'.$contactType.'_name'.'|string|max:35';
        $rules[$contactType.'_telephone'] = $required.'string|min:8';
        $rules[$contactType.'_email'] = 'nullable|email';
        // $rules[$contact_type . "_account"] = "string|max:12";
        // $rules[$contact_type . "_account_id"] = "string";

        return $rules;
    }

    /**
     * Returns a string indicating if the
     * details for this type of address
     * are optional or mandatory.
     *
     * @param  string  $type
     *
     * @return string Either "required" or blank
     */
    public function detailsRequired($type)
    {
        switch ($type) {
            case 'sender':
            case 'recipient':
                $required = 'required|';
                break;

            default:
                $required = '';
                break;
        }

        return $required;
    }

    /**
     * Create validation rules for an Address.
     *
     * @param  string Address Type
     * @param  array Rules
     *
     * @return array Validation rules
     */
    public function addAddressRules($addressType = 'recipient', $rules = '')
    {
        $required = $this->detailsRequired($addressType);

        $rules[$addressType.'_address1'] = $required.'string|max:35';
        $rules[$addressType.'_address2'] = 'nullable|string|max:35';
        $rules[$addressType.'_address3'] = 'nullable|string|max:35';
        $rules[$addressType.'_city'] = $required.'string|max:35';
        $rules[$addressType.'_state'] = 'nullable|string|max:35';
        $rules[$addressType.'_postcode'] = 'nullable|string|max:10';
        $rules[$addressType.'_country_code'] = $required.'exists:countries,country_code';
        $rules[$addressType.'_type'] = $required.'in:r,c';

        return $rules;
    }

    /**
     * Create validation rules for packaging.
     *
     * @param  int Company id
     * @param  int Department id
     *
     * @return array Validation rules
     */
    public function addPackagingRules($companyId, $modeId, $rules)
    {
        // Check for Company specific packaging (returns a string)
        $packagingTypes = $this->getPackagingTypes($companyId, $modeId);

        if (empty($packagingTypes)) {
            // No Customer Specific rates defined use defaults
            $packagingTypes = $this->getPackagingTypes(0, $modeId);
        }

        // Identify the acceptable Packaging Types for this company (For all services)
        $rules['packages.*.packaging_code'] = 'required|in:'.$packagingTypes;

        return $rules;
    }

    /**
     * Get Packaging Types.
     *
     * @param  int Company id
     * @param  int Department id
     *
     * @return string of comma separated packaging types
     */
    public function getPackagingTypes($companyId, $modeId)
    {
        return CompanyPackagingType::where('company_id', $companyId)->where('mode_id', $modeId)->get()->implode('code', ',');
    }

    public function checkPayAccounts($shipment, $errors = [])
    {
        // Exempt UK Domestic Shipments
        if (isUkDomestic($shipment['sender_country_code']) && isUkDomestic($shipment['recipient_country_code'])) {
            return $errors;
        }

        // Exempt International Domestic Shipments
        if ($shipment['sender_country_code'] == $shipment['recipient_country_code']) {
            return $errors;
        }

        // Check Bill shipping to Other or Recipient is not using an IFS account
        if (isset($shipment['bill_shipping_account']) && $shipment['bill_shipping_account'] > '') {
            if (isset($shipment['bill_shipping']) && in_array($shipment['bill_shipping'], ['recipient', 'other'])) {
                $service = Service::where('account', $shipment['bill_shipping_account'])->first();
                if ($service) {
                    $errors[] = '"Bill Shipping To:" must be "Sender" if using an IFS Account number on Billing Tab';
                }
            }
        }

        // Check Bill tax/ duty to Other or Recipient is not using an IFS account
        if (isset($shipment['bill_tax_duty_account']) && $shipment['bill_tax_duty_account'] > '') {
            if (isset($shipment['bill_tax_duty']) && in_array($shipment['bill_tax_duty'], ['recipient', 'other'])) {
                $service = Service::where('account', $shipment['bill_tax_duty_account'])->first();
                if ($service) {
                    $errors[] = '"Bill Tax And Duty To:" must be "Sender" if using an IFS Account number on Billing Tab';
                }
            }
        }

        // If Fedex IE service check not billed to IFS account 914974712
        if (isset($shipment['service_id']) && $shipment['service_id'] == '9') {
            if (isset($shipment['bill_shipping_account']) && ($shipment['bill_shipping_account'] == '914974712')) {
                $errors[] = 'Bill Shipping Acct No: cannot be "914974712"';
            }
            if (isset($shipment['bill_tax_duty_account']) && ($shipment['bill_tax_duty_account'] == '914974712')) {
                $errors[] = 'Bill Tax/ Duty Acct No: cannot be "914974712"';
            }
        }

        return $errors;
    }

    public function checkPostCodes($shipment, $errors = [])
    {
        $senderPostcode = (! empty($shipment['sender_postcode'])) ? $shipment['sender_postcode'] : '';
        $recipientPostcode = (! empty($shipment['recipient_postcode'])) ? $shipment['recipient_postcode'] : '';

        // Check Sender Postcode
        if (! $this->checkPostCode($shipment['sender_country_code'], $senderPostcode, $errors)) {
            $errors[] = 'Invalid Shipper postcode';
        }

        // Check Recipient Postcode
        if (! $this->checkPostCode($shipment['recipient_country_code'], $recipientPostcode, $errors)) {
            $errors[] = 'Invalid Recipient postcode';
        }

        // Check that postcode matches country
        if ($shipment['recipient_country_code'] == 'IE') {
            $areaCode = substr($shipment['recipient_postcode'], 0, 2);

            // NI, Shetlands, Orkney
            if (in_array($areaCode, ['BT','ZE','KW'])) {
                $errors[] = 'Destination should be UK';
            }

            // Jersey
            if (in_array($areaCode, ['JE'])) {
                $errors[] = 'Destination should be Jersey';
            }

            // Isle of Man
            if (in_array($areaCode, ['IM'])) {
                $errors[] = 'Destination should be Isle of Man';
            }

            // Guernsey
            if (in_array($areaCode, ['GY'])) {
                $errors[] = 'Destination should be Guernsey';
            }
        }


        return $errors;
    }

    public function checkPostCode($countryCode, $postCode)
    {
        $country = Country::where('country_code', $countryCode)->first();
        if ($country) {
            if ($country->postal_validation > '') {
                // Postal regex defined so check against it
                return preg_match($country->postal_validation, $postCode);
            } else {
                // No regex defined so return true
                return true;
            }
        } else {
            // Country not valid so fail
            return false;
        }
    }

    public function checkPackageLimits($shipment, $errors = [])
    {
        // Determine Service Min/ Max Values
        $this->getServiceLimits($shipment['service_id']);

        // Check Packaging is within Carrier defined limits.
        if (count($shipment['packages']) == $shipment['pieces']) {
            // Check Number of packages
            if (count($shipment['packages']) > $this->maxAllowedPieces) {
                $errors[] = "Number of packages exceeds Max allowed - $this->maxAllowedPieces.";
            }

            for ($i = 0; $i < $shipment['pieces']; $i++) {
                $pkg = $i + 1;
                $volWeight = $shipment['packages'][$i]['length'] * $shipment['packages'][$i]['width'] * $shipment['packages'][$i]['height'];
                $volWeight = $volWeight / $this->divisor;
                $chargeableWeight = max($shipment['packages'][$i]['weight'], $volWeight);

                // Check over Min weight
                if ($chargeableWeight < $this->minAllowedWeight) {
                    $errors[] = "Package $pkg - Weight under Min allowed ($this->minAllowedWeight Kgs)";
                }

                // Check not over Max weight
                if ($chargeableWeight > $this->maxAllowedWeight) {
                    $errors[] = "Package $pkg - Weight exceeds Max allowed ($this->maxAllowedWeight Kgs)";
                }

                // Check longest side is not too long
                $longestSide = max($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                if ($longestSide > $this->maxAllowedDimension) {
                    $errors[] = "Package $pkg - Side exceeds Max length ($this->maxAllowedDimension cms)";
                }

                // Check Girth is within permitted value
                $girth = girth($shipment['packages'][$i]['length'], $shipment['packages'][$i]['width'], $shipment['packages'][$i]['height']);
                if ($girth > $this->maxAllowedGirth) {
                    $errors[] = "Package $pkg - Girth exceeds Max allowed ($this->maxAllowedGirth cms)";
                }

                // Only Fedex/ DHL Intl allowed to use Carrier Envelope
                if ($shipment['packages'][$i]['packaging_code'] == 'ENV') {
                    if (in_array($shipment['carrier_id'], ['2', '5']) && ! in_array($shipment['recipient_country_code'], ['GB','IE'])) {
                        // All Ok
                    } else {
                        $errors[] = "Package $pkg - Carrier Envelope not applicable - Select Package instead";
                    }
                }
            }
        } else {
            $errors[] = 'Piece count incorrect';
        }

        return $errors;
    }

    public function checkCommodityDetails($shipment, $errors)
    {
        $calcCustomsVal = 0;
        foreach ($shipment['contents'] as $commodity) {
            $calcCustomsVal+=$commodity['quantity']*$commodity['unit_value'];
        }

        if ($shipment['customs_value']<>$calcCustomsVal) {
            $errors[] = 'Individual Commodity values not equal to Shipment Customs Value';
        }

        return $errors;
    }

    public function getServiceLimits($serviceId)
    {
        $service = Service::find($serviceId)->first();
        if ($service) {
            if ($service->volumetric_divisor > 0) {
                $this->divisor = $service->volumetric_divisor;
            }

            if ($service->min_weight > 0) {
                $this->minAllowedWeight = $service->min_weight;
            }

            if ($service->max_weight > 0) {
                $this->maxAllowedWeight = $service->max_weight;
            }

            if ($service->max_pieces > 0) {
                $this->maxAllowedPieces = $service->max_pieces;
            }

            if ($service->max_dimension > 0) {
                $this->maxAllowedDimension = $service->max_dimension;
            }

            if ($service->max_girth > 0) {
                $this->maxAllowedGirth = $service->max_girth;
            }

            if ($service->max_customs_value > 0) {
                $this->maxAllowedValue = $service->max_customs_value;
            }
        }
    }

    public function checkCountryRestrictions($shipment, $errors = [])
    {
        # If Shipment to Russia
        if (strtoupper($shipment['recipient_country_code']) == 'RU') {
            $value = convertCurrencies(
                $shipment['customs_value_currency_code'],
                'EUR',
                $shipment['customs_value']
            );
            if ($value > 200) {
                $errors[] = 'Customs value exceeds 200 euros';
            }
        }

        return $errors;
    }

    public function checkCollectionDate($shipment, $errors)
    {
        $tz = Company::find($shipment['company_id'])->localisation->time_zone;

        // Identify the Pickup Cutoff time
        $pickupTimes = new Postcode();
        $pickupTime = $pickupTimes->getPickupTime($shipment['sender_country_code'], $shipment['sender_postcode']);
        $cutoffTime = Carbon::createFromformat('Y-m-d H:i:s', $shipment['ship_date'].' '.$pickupTime, $tz);

        $now = Carbon::now($tz);

        if ($now->gt($cutoffTime)) {
            $errors[] = 'Past todays collection cut-off time ('.$cutoffTime->format('h:ia').')';
        }

        return $errors;
    }
}
