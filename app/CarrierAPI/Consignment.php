<?php

namespace App\CarrierAPI;

use App;
use App\Mail\GenericError;
use App\Models\Company;
use App\Models\Country;
use App\Models\Department;
use App\Models\Mode;
use App\Models\Service;
use App\Models\Shipment;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Consignment - Handles the preprocessing,
 * and manipulation of the users keyed data
 *
 * @author gmcbroom
 */
class Consignment
{
    public $data;
    private $company;
    private $carrier;

    /**
     * Loads shipment data and preprocesses it
     * to ensure corect and complete.
     *
     * @param  type array shipment details
     */
    public function __construct($shipment)
    {
        $this->data = $shipment;
        $this->company = Company::find($this->data['company_id']);
        $this->preProcess();
    }

    /**
     * Pre-Process data to add any missing data.
     *
     * @param  array Shipment details
     */
    private function preProcess()
    {
        $this->data = fixShipmentCase($this->data);                             // Ensure all fields use correct case and Flags are boolean

        // Check addresses and perform any necessary Overrides
        $this->checkAddresses();
        if (isset($this->data['service_id']) && $this->data['service_id'] > '') {
            $this->setMissingIndexes();
            $this->data['mode'] = Mode::find($this->data['mode_id'])->name;
            $this->setDepartmentId();
            $this->data['depot_id'] = $this->company->depot_id;
            $this->setCarrierAndService();
            $this->setIncoTerms();
            $this->setDescOfContents();
            $this->setCommodityUOM();
            $this->setSenderTelephone();
            $this->setAnsiStateCode('sender');
            $this->setAnsiStateCode('recipient');
            $this->data['country_of_destination'] = $this->data['recipient_country_code'];
            $this->data['route_id'] = 1;
            $this->setCustomsCurrency();
            $this->setSpecialInstructions();
            $this->setShipmentDates();
            $this->doPackageLevelProcessing();
        }
    }

    /**
     * Checks addresses and performs any necessary Overrides.
     *
     */
    public function checkAddresses()
    {
        if (isset($this->company->shipper_type_override) && $this->company->shipper_type_override > '') {
            $this->data['sender_type'] = $this->company->shipper_type_override;
        }

        if (isset($this->company->recipient_type_override) && $this->company->recipient_type_override > '') {
            $this->data['recipient_type'] = $this->company->recipient_type_override;
        }

        /*
         * ***************************************************************
         *  Ensure country codes for Jersey and Guernsey are correctly set
         * ***************************************************************
         */
        if (isset($this->data['recipient_postcode'])) {
            // If country code has been set to GB incorrectly then change
            if (isset($this->data['recipient_country_code']) && $this->data['recipient_country_code'] == 'GB') {
                $countryCodes = ['GY' => 'GG', 'IM' => 'IM', 'JE' => 'JE'];

                // Format UK postcode
                $this->data['recipient_postcode'] = formatUkPostcode($this->data['recipient_postcode']);

                // Take first 2 chars of the postcode
                $prefix = strtoupper(substr($this->data['recipient_postcode'], 0, 2));
                if (isset($countryCodes[$prefix])) {
                    $this->data['recipient_country_code'] = $countryCodes[$prefix];
                }
            }
        }
    }

    private function setMissingIndexes()
    {
        // Add any missing but required indexes
        $requiredKeys = ['sender_address2', 'recipient_address2'];
        foreach ($requiredKeys as $key) {
            if (! array_key_exists($key, $this->data)) {
                $this->data[$key] = null;
            }
        }
    }

    private function setDepartmentId()
    {
        // Identify Department
        $department_code = identifyDepartment($this->data);
        $department = Department::where('code', $department_code)->first();
        $this->data['department_id'] = ($department) ? $department->id : null;
    }

    private function setCarrierAndService()
    {
        $service = Service::find($this->data['service_id']);
        if ($service) {
            $this->data['carrier_id'] = $service->carrier_id;
            $this->data['carrier_code'] = $service->carrier->code;
            $this->data['service_code'] = $service->code;
            $this->data['volumetric_divisor'] = $service->volumetric_divisor;
        } else {
            $this->data['carrier_id'] = '';
            $this->data['carrier_code'] = '';
            $this->data['service_code'] = '';
            $this->data['volumetric_divisor'] = '';
        }
    }

    private function setIncoTerms()
    {
        // Set IncoTerms if possible
        if (! isset($this->data['terms_of_sale']) || empty($this->data['terms_of_sale'])) {
            if ($this->data['bill_tax_duty'] == 'sender') {
                $this->data['terms_of_sale'] = 'ddp';
            }
        }

        // Temporary fix for Twinings
        if ($this->data['company_id'] == '608' && ! in_array(strtoupper($this->data['recipient_country_code']), ['GB', 'JE', 'GG', 'IE'])) {
            $this->data['bill_shipping'] = 'sender';
            $this->data['bill_tax_duty'] = 'recipient';
            $this->data['terms_of_sale'] = 'dap';
        }

        // Set Bill to accounts
        if (isset($this->data['service_id'])) {
            $this->setBillToAcct('bill_shipping');
            $this->setBillToAcct('bill_tax_duty');
        }
    }

    /**
     * Check to see if shipping account is set
     * If not then attempts to set it
     * Shipment held in  $this->input.
     */
    private function setBillToAcct($account_type)
    {
        $account = '';
        $service = $this->identifyService();
        if ($service) {
            // If Account number specified and in wrong format then remove
            $accountSpecified = ! empty($this->data[$account_type.'_account']);
            if ($accountSpecified) {
                $invalidFormat = ! preg_match($service->account_number_regex, $this->data[$account_type.'_account']);
                if ($invalidFormat) {
                    $this->data[$account_type.'_account'] = '';
                    $accountSpecified = false;
                }
            }

            // If Bill Sender and account empty then complete from service
            if ($this->data[$account_type] == 'sender' && ! $accountSpecified) {
                if (! empty($service->pivot->account)) {
                    // Use Customers own account if defined
                    $this->data[$account_type.'_account'] = $service->pivot->account;
                } else {
                    // Use Carrier Default Service Account no.
                    $this->data[$account_type.'_account'] = $service->account;
                }
            }

            if ($this->data[$account_type] == 'recipient' && ! $accountSpecified) {
                if (!isset($this->data[$account_type.'_account'])) {
                    $this->data[$account_type.'_account'] = '';
                }
            }
        }
    }

    /**
     * Identify and return Service
     *
     * @return object Service
     */
    private function identifyService()
    {
        // Identify Service
        if (isset($this->data['service_id']) && $this->data['service_id'] > "") {
            $service = Service::find($this->data['service_id']);
        } else {
            // If Service not known yet use the first service defined for that Carrier - Review as not safe.
            Mail::to('it@antrim.ifsgroup.com')->send(
                new GenericError(
                    'Warning - Please review code/shipment',
                    'CarrierApi->setBillToAcct mode_id: '.$this->data['mode_id']
                    .' company_id: '.$this->data['company_id']
                    .' carrier_id: '.$this->data['carrier_id']
                )
            );
            $service = $this->company
                ->getServicesForMode($this->data['mode_id'])
                ->where('code', $this->data['service_code'])
                ->where('carrier_id', (string) $this->data['carrier_id']) // Carrier_id needs to be typecast to string
                ->first();
        }

        return $service;
    }

    private function setDescOfContents()
    {
        // Set Description of contents
        if (isset($this->data['ship_reason']) && $this->data['ship_reason'] == 'documents') {
            // Documents Only shipment
            $this->data['documents_description'] = 'Documents Only';
            $this->data['goods_description'] = '';
            $this->data['contents'] = null;

            // Countries that require minimum 1 USD customs value for docs shipments
            if (in_array($this->data['recipient_country_code'], ['NZ', 'AM', 'AU', 'AZ', 'BY', 'CA', 'CN', 'CZ', 'GE', 'JP', 'KG', 'MD', 'PH', 'RU', 'SK', 'UZ', 'VE', 'KR', 'KW', 'RO'])) {
                $this->data['customs_value'] = 1;
                $this->data['customs_value_currency_code'] = 'USD';
            }
        } else {
            // Clear Documents Description as not a Document Shipment
            $this->data['documents_description'] = '';

            // If Commodity set then use first commodity description
            if (isset($this->data['contents'][0]['description']) && $this->data['contents'][0]['description'] > '') {
                $this->data['goods_description'] = $this->data['contents'][0]['description'];
            } elseif (! isset($this->data['goods_description']) || empty($this->data['goods_description'])) {
                $this->data['goods_description'] = 'Miscellaneous Goods';
            }
        }
    }

    private function setCommodityUOM()
    {
        // Set Weight UOM for each commodity item
        if (isset($this->data['contents'])) {
            for ($i = 0; $i < count($this->data['contents']); $i++) {
                $this->data['contents'][$i]['weight_uom'] = $this->data['weight_uom'];
            }
        }
    }

    private function setSenderTelephone()
    {
        if (! isset($this->data['sender_telephone']) || empty($this->data['sender_telephone'])) {
            $this->data['sender_telephone'] = $this->company->telephone;
        }
    }

    private function setAnsiStateCode($type)
    {
        if (! isset($this->data[$type.'_state'])) {
            $this->data[$type.'_state'] = null;
        }

        $this->data[$type.'_state_ansi_code'] = State::getAnsiStateCode($this->data[$type.'_country_code'], $this->data[$type.'_state']);
        if (empty($this->data[$type.'_state_ansi_code'])) {
            $this->data[$type.'_state_code'] = $this->data[$type.'_state'];
        } else {
            $this->data[$type.'_state_code'] = $this->data[$type.'_state_ansi_code'];
        }

        return $this->data;
    }

    private function setCustomsCurrency()
    {
        // If Currency not set then default to Currency for Senders Country
        if (! isset($this->data['customs_value_currency_code']) || empty($this->data['customs_value_currency_code'])) {
            $this->data['customs_value_currency_code'] = Country::where('country_code', $this->data['sender_country_code'])->first()->currency_code;
        }
    }

    private function setSpecialInstructions()
    {
        // If special_instructions not set then set to empty string
        if (! isset($this->data['special_instructions'])) {
            $this->data['special_instructions'] = '';
        }
    }

    private function setShipmentDates()
    {
        // Retrieve localisation details
        $localisation = $this->company->localisation;

        // If date format not already set then set it
        if (! isset($this->data['date_format'])) {
            $this->data['date_format'] = $localisation->date_format;
        }

        // Convert Collection date into a known format.
        $date_format = getDateFormat($this->data['date_format']);
        $this->data['collection_date'] = Carbon::createFromformat($date_format, $this->data['collection_date'], $localisation->time_zone)->format('Y-m-d');

        // Set the ship date.
        if (isset($this->data['collection_date'])) {
            $this->data['ship_date'] = $this->data['collection_date'];
        } else {
            $this->data['ship_date'] = date('Y-m-d');
        }
    }

    /**
     * Package level Processing
     */
    private function doPackageLevelProcessing()
    {
        $dims = [];
        $dryIceTotalWeight = 0;
        $cnt = 0;
        $volumetric_weight = 0;
        foreach ($this->data['packages'] as $package) {
            $this->doPackageProcessing($cnt);

            $this->checkDryIceShipment($cnt);
            if ($this->data['dry_ice_flag']) {
                $dryIceTotalWeight += $package['dry_ice_weight'];
            } else {
                $this->data['packages'][$cnt]['dry_ice_weight'] = 0;
            }

            $volumetric_weight += $this->data['packages'][$cnt]['volumetric_weight'];

            // Collect Dimensions
            $dims[] = $package['length'];
            $dims[] = $package['width'];
            $dims[] = $package['height'];

            $cnt++;
        }

        // Set Max Dimension
        $this->data['max_dimension'] = (count($dims) > 0) ? max($dims) : 0;

        // Set Total Volumetric weight
        if (! isset($this->data['volumetric_weight']) || $volumetric_weight > $this->data['volumetric_weight']) {
            $this->data['volumetric_weight'] = $volumetric_weight;
        }

        // Round volumetric in lbs UP to the nearest lb
        if ($this->data['weight_uom'] == 'lb') {
            $this->data['volumetric_weight'] = ceil($this->data['volumetric_weight']);
        }

        // Set Dry Ice Weight
        $this->data['dry_ice_total_weight'] = $dryIceTotalWeight;
    }

    /*
     * Set ANSI State code for US & CA
     */

    private function doPackageProcessing($cnt)
    {
        // Ensure all dims are integers
        $this->data['packages'][$cnt]['length'] = ceil($this->data['packages'][$cnt]['length']);
        $this->data['packages'][$cnt]['width'] = ceil($this->data['packages'][$cnt]['width']);
        $this->data['packages'][$cnt]['height'] = ceil($this->data['packages'][$cnt]['height']);
        $this->data['packages'][$cnt]['index'] = $cnt + 1;

        // Calc Volumetric weight
        $this->data['packages'][$cnt]['volumetric_weight'] = calcVolume(
            $this->data['packages'][$cnt]['length'],
            $this->data['packages'][$cnt]['width'],
            $this->data['packages'][$cnt]['height'],
            $this->data['volumetric_divisor']
        );
    }

    /**
     * Check package to see if it contains DryIce
     */
    private function checkDryIceShipment($cnt)
    {
        // Check for Dry Ice
        if (isset($shipment['packages'][$cnt]['dry_ice_weight']) && $shipment['packages'][$cnt]['dry_ice_weight'] > 0) {
            $this->data['dry_ice_flag'] = true;
        } else {
            $this->data['dry_ice_flag'] = false;
        }
    }

    /**
     * Checks if shipment is Collect
     * to Shipment array.
     *
     * @return boolean true/ false
     */
    public function isCollect()
    {
        if (isset($this->data['bill_shipping']) && $this->data['bill_shipping'] == 'recipient') {
            return true;
        }

        return false;
    }

    /**
     * Adds Carrier tracking number/ Barcode etc.
     * to Shipment array.
     *
     * @param  type  $response
     */
    public function addCarrierResponse($response)
    {
        // Add Shipment level
        $this->data['route_id'] = $response['route_id'];
        $this->data['consignment_number'] = $response['ifs_consignment_number'];
        $this->data['carrier_consignment_number'] = $response['consignment_number'];
        $this->data['carrier_tracking_number'] = $response['consignment_number'];

        // Add Carrier Tracking number and Barcode for each package
        for ($i = 0; $i < $response['pieces']; $i++) {
            $this->data['packages'][$i]['carrier_tracking_number'] = $response['packages'][$i]['carrier_tracking_code'];
            $this->data['packages'][$i]['barcode'] = $response['packages'][$i]['barcode'];
        }

        $this->data['label_base64'] = $response['label_base64'];
    }

    /**
     * Creates a random 12 char string to use as a
     * token for a shipment.
     *
     */
    public function setShipmentToken()
    {
        $getNewToken = true;

        while ($getNewToken) {
            $token = Str::random(12);
            $shipment = Shipment::where('token', $token)->first();
            if (! isset($shipment)) {
                $this->data['token'] = $token;
                $getNewToken = false;
            }
        }
    }

    /**
     * Sets Shipment fields related to pricing
     *
     * @param  type array Charges
     */
    public function setPricingFields($charges = [])
    {
        if ($charges == []) {
            $this->data['quoted'] = null;
            $this->data['shipping_cost'] = null;
            $this->data['shipping_charge'] = null;
            $this->data['fuel_cost'] = null;
            $this->data['fuel_charge'] = null;
            $this->data['cost_currency'] = 'GBP';
            $this->data['sales_currency'] = 'GBP';
        } else {
            $this->data['quoted'] = json_encode($charges);
            $this->data['shipping_cost'] = $charges['shipping_cost'];
            $this->data['shipping_charge'] = $charges['shipping_charge'];
            $this->data['fuel_cost'] = $charges['fuel_cost'];
            $this->data['fuel_charge'] = $charges['fuel_charge'];
            $this->data['cost_currency'] = $charges['cost_currency'];
            $this->data['sales_currency'] = $charges['sales_currency'];
        }
    }

    /**
     * Additional processing before writing to Database
     *
     */
    public function preProcessAddShipment()
    {
        if (! empty($this->data['alcohol'])) {
            $this->data['alcohol_type'] = (isset($this->data['alcohol']['type'])) ? $this->data['alcohol']['type'] : '';
            $this->data['alcohol_packaging'] = (isset($this->data['alcohol']['packaging'])) ? $this->data['alcohol']['packaging'] : '';
            $this->data['alcohol_volume'] = (isset($this->data['alcohol']['volume'])) ? $this->data['alcohol']['volume'] : '';
            $this->data['alcohol_quantity'] = (isset($this->data['alcohol']['quantity'])) ? $this->data['alcohol']['quantity'] : '';
        }

        if (! empty($this->data['dry_ice'])) {
            $this->data['dry_ice_flag'] = (isset($this->data['dry_ice']['flag'])) ? $this->data['dry_ice']['flag'] : '';
            $this->data['dry_ice_weight_per_package'] = (isset($this->data['dry_ice']['weight_per_package'])) ? $this->data['dry_ice']['weight_per_package'] : '';
            $this->data['dry_ice_total_weight'] = (isset($this->data['dry_ice']['total_weight'])) ? $this->data['dry_ice']['total_weight'] : '';
        }

        if (! isset($this->data['collection_route']) || empty($this->data['collection_route'])) {
            $this->data['collection_route'] = 'ADHOC';
        }


        /*
         * Save the serialized form values
         */
        if (isset($this->data['form_values'])) {
            // Convert serialized form string to json string
            parse_str($this->data['form_values'], $values);

            // Flatten the multi-dimensional array into 1D array using dot notation
            $values = Arr::dot($values);

            $this->data['form_values'] = json_encode($values);
        }
    }
}
