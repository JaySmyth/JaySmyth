<?php

/**
 * Page-level DocBlock
 * @package unfinished
 * @todo Complete the following functions
 *
 * function getServiceByDest($shipment, $possibleServices);

 *
 */

namespace App\CarrierAPI;

use App;
use App\Jobs\CreateEasypostTracker;
use DB;
use App\Company;
use App\Service;
use App\Department;
use App\Shipment;
use App\State;
use App\Mode;
use App\Country;
use App\CarrierAPI\ServiceRules;
use App\CarrierAPI\APIShipment;
use App\CarrierAPI\Fedex\FedexAPI;
use App\CarrierAPI\DHL\DHLAPI;
use App\CarrierAPI\UPS\UPSAPI;
use App\CarrierAPI\TNT\TNTAPI;
use App\CarrierAPI\ExpressFreight\ExpressFreightAPI;
use App\CarrierAPI\ExpressFreightNI\ExpressFreightNIAPI;
use App\CarrierAPI\PrimaryFreight\PrimaryFreightAPI;
use App\CarrierAPI\IFS\IFSAPI;
use App\CarrierAPI\CWide\CWideAPI;
use App\CarrierAPI\DHLGlobalMail\DHLGlobalMailAPI;
use App\CarrierAPI\Pdf;
use App\Pricing\Facades\Pricing;
use TCPDI;
use Carbon\Carbon;
use Exception;

/**
 * Description of CarrierWebServices.
 *
 * @author gmcbroom
 */
class CarrierAPI
{

    private $company;
    private $carrier;
    private $mode;
    private $nonPricedServices = ["ipf", "air", "usg"];

    /*
     * ************************************
     * Generic Functions
     * ************************************
     */

    public function __construct()
    {
        
    }

    public function buildCarrier($carrier_code = 'fedex')
    {

        $this->carrier = null;
        switch (strtolower($carrier_code)) {
            case 'fedex':
                $this->carrier = new FedexAPI($this->mode);
                break;

            case 'cwide':
                $this->carrier = new CWideAPI($this->mode);
                break;

            case 'ups':
                $this->carrier = new UPSAPI($this->mode);
                break;

            case 'dhl':
                $this->carrier = new DHLAPI($this->mode);
                break;

            case 'tnt':
                $this->carrier = new TNTAPI($this->mode);
                break;

            case 'exp':
                $this->carrier = new ExpressFreightAPI($this->mode);
                break;

            case 'expni':
                $this->carrier = new ExpressFreightNIAPI($this->mode);
                break;

            case 'pri':
                $this->carrier = new PrimaryFreightAPI($this->mode);
                break;

            case 'easypost':
                $this->carrier = new EasyPostAPI($this->mode);
                break;

            case 'ifs':
                $this->carrier = new IFSAPI($this->mode);
                break;

            case 'dhlmail':
                $this->carrier = new DHLGlobalMailAPI($this->mode);
                break;

            default:
                // Do Nothing
                dd('Unable to build carrier for : ' . strtolower($carrier_code));
                break;
        }
    }

    /**
     * Accepts Shipment details and returns all services
     * that are available and appropriate for the shipment
     *
     * @param type $shipment
     * @param string $mode Used by APIController to overide mode
     *
     * @return array of available services
     */
    public function getAvailableServices($shipment, $mode = '')
    {

        // Check Company is enabled
        $this->company = Company::find($shipment['company_id']);
        if ($this->company->enabled) {

            // Check addresses and perform any necessary Overrides
            $shipment = $this->checkAddresses($shipment);

            $collect = $this->isCollect($shipment);

            $this->setEnvironment($mode);

            $companyServices = $this->company->getServicesForMode($shipment['mode_id'])->toArray();

            $suitableServices = $this->getAllSuitableServices($shipment, $companyServices);

            // Reduce list of services depending select criteria
            switch ($this->company->carrier_choice) {

                case 'price':
                case 'cost':
                    $availableServices = $this->getCheapestService($suitableServices, $this->company->carrier_choice,
                        $collect);
                    break;

                case 'dest':
                    $availableServices = $this->getServiceByDest($shipment, $suitableServices); // Not yet handled
                    break;

                default:
                    $availableServices = $suitableServices;
                    break;
            }

            /*
             * ***************************************************
             * If this is a collect shipment then remove pricing
             * info even for those shipments we were able to price
             * since recipient will be billed according to
             * recipients rates
             * ***************************************************
             */
            if ($collect) {

                foreach ($availableServices as $key => $availableService) {
                    $availableServices[$key]['cost'] = [];
                    $availableServices[$key]['cost_currency'] = '';
                    $availableServices[$key]['cost_detail'] = [];
                    $availableServices[$key]['price'] = [];
                    $availableServices[$key]['price_currency'] = '';
                    $availableServices[$key]['price_detail'] = [];
                }
            }


            if (isset($availableServices) && count($availableServices) > 1) {
                usort($availableServices, function ($item1, $item2) {
                    return $item1['price'] <=> $item2['price'];
                });
            }
        } else {
            $availableServices = [];
        }

        return $availableServices;
    }

    /**
     * Accepts Shipment details and array of services
     * and returns the services appropriate to the
     * shipment including total cost and price
     *
     * @param array Shipment
     * @param array Services available to the Customer
     *
     * @return array Appropriate services
     */
    private function getAllSuitableServices($shipment, $carrierServiceArray)
    {

        $cnt = 0;
        $possibleServices = array();
        $serviceRules = new ServiceRules();

        /*
         * *********************************************
         * Loop through all services configured for this 
         * Customer/ mode.
         * *********************************************
         */
        foreach ($carrierServiceArray as $serviceDetails) {

            // Check if service is applicable for this shipment
            if ($serviceRules->isSuitable($shipment, $serviceDetails)) {

                // Price Shipment for this service
                $prices = Pricing::price($shipment, $serviceDetails['id']);

                /*
                 * *********************************
                 * If service is allowed then add it
                 * to the list of possible services.
                 * *********************************
                 */
                if ($this->serviceAllowed($shipment, $prices, $serviceDetails)) {
                    $possibleServices[$cnt] = $this->formatService($cnt, $serviceDetails, $prices);
                    $cnt++;
                }
            }
        }

        return $possibleServices;
    }

    public function serviceAllowed($shipment, $prices, $serviceDetails)
    {
        /**
         * If a monthly limit has been defined on company_service, check that it has not been exceeded
         */
        if (isset($serviceDetails['pivot']['monthly_limit']) && $serviceDetails['pivot']['monthly_limit'] > 0) {

            // Count the shipments for the month
            $count = \App\Shipment::whereCompanyId($this->company->id)->whereBetween('ship_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->whereNotIn('status_id', [1, 7])->count();

            // Limit has been exceeded, remove from array
            if ($count >= $serviceDetails['pivot']['monthly_limit']) {
                return false;
            }
        }

        /**
         * If a max weight limit has been defined on company_service, check that it has not been exceeded
         */
        if (isset($serviceDetails['pivot']['max_weight_limit']) && $serviceDetails['pivot']['max_weight_limit'] > 0) {
            if ($shipment['weight'] > $serviceDetails['pivot']['max_weight_limit']) {
                return false;
            }
        }

        /*
         * ******************************************
         * Check to see if this service is acceptable
         * ******************************************
         */

        // If this is a collect shipment
        if ($this->isCollect($shipment)) {

            if ($serviceDetails['carrier_id'] == 2) {

                // Fedex Collect Shipments are allowed
                return true;
            } else {

                // Non Fedex Collect Shipments are not allowed
                return false;
            }
        }

        // If Customer allowed to choose Carrier
        if (strtolower($this->company->carrier_choice) == 'user') {
            return true;
        }

        // If this is one of the non pricing services (eg air, ipf, ...)
        if (in_array($serviceDetails['code'], $this->nonPricedServices)) {
            return true;
        }


        $frtSales = 0;

        if (isset($prices['sales'])) {

            foreach ($prices['sales'] as $key => $val) {
                if ($val['code'] == 'FRT' && $val['value'] > 0) {
                    $frtSales = $val['value'];
                }
            }
        }

        // If we can successfully cost & price shipment
        if ($prices['shipping_cost'] > 0 && $prices['shipping_charge'] > 0 && $frtSales > 0) {
            return true;
        }

        // If unable to cost service
        if (!isset($prices['shipping_cost']) || $prices['shipping_cost'] == 0 || $frtSales == 0) {

            // If Zero costs are allowed
            if ($serviceDetails['allow_zero_cost']) {

                // Can price shipment
                if (isset($prices['shipping_charge']) && $prices['shipping_charge'] > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isCollect($shipment)
    {

        /*
         * ********************************************
         * To be a collect shipment, bill_shipping must
         * be "Recipient" and the carrier must be Fedex
         * (Other Carriers not supported)
         * ********************************************
         */

        if (isset($shipment['bill_shipping']) && $shipment['bill_shipping'] == 'recipient') {

            return TRUE;
        }

        return FALSE;
    }

    public function zeroPrices($prices)
    {

        $prices['shipping_cost'] = 0;
        $prices['shipping_charge'] = 0;
        $prices['cost_currency'] = '';
        $prices['sales_currency'] = '';
        $prices['sales'] = [];
        $prices['costs'] = [];

        return $prices;
    }

    public function formatService($cnt, $serviceDetails, $prices)
    {

        $service = $serviceDetails;
        $service['cost'] = $prices['shipping_cost'];
        $service['cost_currency'] = $prices['cost_currency'];
        $service['cost_detail'] = $prices['costs'];
        $service['price'] = $prices['shipping_charge'];
        $service['price_currency'] = $prices['sales_currency'];
        $service['price_detail'] = $prices['sales'];

        // If Company specific name exists for this service then use it.
        if (isset($service['pivot']["name"]) && $service['pivot']["name"] > "")
            $service['name'] = $service['pivot']["name"];

        return $service;
    }

    /**
     * Accepts a list of priced services and returns
     * the cheapest service based on the carrier_choice
     * setting on the company table - cost/price
     *
     * @param array Possible Services
     * @param string Carrier choice criteria - cost/ price
     *
     * @return array Chosen Service
     */
    public function getCheapestService($possibleServices, $carrier_choice, $collect = FALSE)
    {

        $chosenService = [];

        // Definition of cheapest defined by $carrier_choice - price or cost
        foreach ($possibleServices as $serviceDetails) {

            // Show unpriced option if Collect using Fedex
            if ($collect && $serviceDetails['carrier_id'] == 2) {

                $chosenService[] = $serviceDetails;
            } else {

                // Only use if we can price it
                if ($serviceDetails['price'] > 0) {

                    if (isset($chosenService[$serviceDetails['code']])) {

                        if ($serviceDetails[$carrier_choice] < $chosenService[$serviceDetails['code']][$carrier_choice]) {

                            // This Service cheaper than Previous ones
                            $chosenService[$serviceDetails['code']] = $serviceDetails;
                        }
                    } else {

                        // First Record for this service
                        $chosenService[$serviceDetails['code']] = $serviceDetails;
                    }
                } else {

                    // Show unpriced option if IPF or AIR shipment
                    if (in_array($serviceDetails['code'], $this->nonPricedServices)) {

                        $chosenService[$serviceDetails['code']] = $serviceDetails;
                    }
                }
            }
        }

        return $chosenService;
    }

    private function getServiceByDest($shipment, $possibleServices)
    {

        return $possibleServices;
    }

    /**
     * Check to see if shipping account is set
     * If not then attempts to set it
     * Shipment held in  $this->input
     *
     * @return none Updates $this->input
     */
    private function setBillToAcct($shipment, $account_type)
    {

        if (isset($shipment[$account_type . "_account"])) {
            $account = $shipment[$account_type . "_account"];
        } else {
            $account = '';
        }

        // If account not defined or blank and payment is "Bill to Sender" - use service default
        if (!isset($account) || $account == '') {
            if (!isset($shipment[$account_type]) || $shipment[$account_type] == 'sender') {

                $service = $this->company
                        ->getServicesForMode($shipment['mode_id'])
                        ->where('code', $shipment['service_code'])
                        ->where('carrier_id', (string) $shipment['carrier_id']) // Carrier_id needs to be typecast to string
                        ->first();

                if (!empty($service)) {

                    if (!empty($service->pivot->account)) {

                        // Use Companies own account if defined
                        $account = $service->pivot->account;
                    } else {

                        // Use Default Service Account no.
                        $account = $service->account;
                    }
                }
            }
        }

        return $account;
    }

    /**
     * Identies the correct Supplier Account
     * Number to use
     *
     * @param  integer companyID
     * @param  integer carrierID
     * @param  integer serviceID
     * @return string Account number
     */
    private function getServiceAcct($companyId, $carrierId, $serviceId)
    {

        $account = $this->company->services()
                        ->where('carrier_id', $carrierId)
                        ->where('service_id', $serviceId)
                        ->first()
                ->pivot
                ->account;

        // Returns incorrect field
        // return Carrier::find($carrierId)->services()->where('service_id', $serviceId)->first()->pivot->account;

        return $account;
    }

    /**
     * Accepts Shipment array and if consignment_number
     * is blank fn creates it
     *
     * @param type $data
     *
     * @return array Shipment Details
     */
    private function preProcessAddShipment($data)
    {

        if (!empty($data['alcohol'])) {
            $data['alcohol_type'] = (isset($data['alcohol']['type'])) ? $data['alcohol']['type'] : '';
            $data['alcohol_packaging'] = (isset($data['alcohol']['packaging'])) ? $data['alcohol']['packaging'] : '';
            $data['alcohol_volume'] = (isset($data['alcohol']['volume'])) ? $data['alcohol']['volume'] : '';
            $data['alcohol_quantity'] = (isset($data['alcohol']['quantity'])) ? $data['alcohol']['quantity'] : '';
        }

        if (!empty($data['dry_ice'])) {
            $data['dry_ice_flag'] = (isset($data['dry_ice']['flag'])) ? $data['dry_ice']['flag'] : '';
            $data['dry_ice_weight_per_package'] = (isset($data['dry_ice']['weight_per_package'])) ? $data['dry_ice']['weight_per_package'] : '';
            $data['dry_ice_total_weight'] = (isset($data['dry_ice']['total_weight'])) ? $data['dry_ice']['total_weight'] : '';
        }

        if (!isset($data['collection_route']) || $data['collection_route'] == '') {
            $data['collection_route'] = 'ADHOC';
        }

        /*
         * Save the serialized form values
         */
        if (isset($data['form_values'])) {

            // Convert serialized form string to json string
            parse_str($data['form_values'], $values);

            // Flatten the multi-dimensional array into 1D array using dot notation
            $values = array_dot($values);

            $data['form_values'] = json_encode($values);
        }


        return $data;
    }

    /**
     * Update Shipment tables with Shipment data
     *
     * @param array $data
     * @return string IFS Consignment number
     */
    public function addShipment($data)
    {
        // Any preprocessing necessary before saving shipment
        $data = $this->preProcessAddShipment($data);

        /*
         * ******************************************************************
         * Transaction bracket updates so that all complete or none complete
         * ******************************************************************
         */
        DB::beginTransaction();

        try {

            if (isset($data['shipment_id']) && is_numeric($data['shipment_id'])) {
                $shipment = Shipment::find($data['shipment_id']);
                $data['consignment_number'] = $shipment->consignment_number; // hack
                $shipment->update($data);
            } else {
                // Shipment does not exist so create it
                $shipment = Shipment::create($data);
            }

            // Set status
            $shipment->setStatus('pre_transit', $data['user_id'], false, true, 'shipper');

            /*
             * *****************************************
             * Save Shipment content (commodity details)
             * *****************************************
             */
            if (isset($data['contents']) && !empty($data['contents'])) {
                foreach ($data['contents'] as $content) {
                    $shipment->contents()->create($content);
                }
            }

            /*
             * *****************************************
             * Save Shipment content (package details)
             * *****************************************
             */
            if (isset($data['packages']) && !empty($data['packages'])) {
                foreach ($data['packages'] as $package) {
                    $shipment->packages()->create($package);
                }
            }

            /*
             * *****************************************
             * Save PDF document (original base64 from carrier - 6x4)
             * *****************************************
             */
            foreach ($data['label_base64'] as $label) {
                $shipment->label()->create([
                    'base64' => $label['base64'],
                    'shipment_id' => $shipment->id
                ]);
            }

            /*
             * *****************************************
             * Save Shipment alerts
             * *****************************************
             */

            /**
             * If we have a valid sender address and sender alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($data['alerts']['sender']) && filter_var($data['sender_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $data['sender_email'], 'type' => 's'] + $data['alerts']['sender'];
                $shipment->alerts()->create($alert);
            }

            /**
             * If we have a valid recipient address and recipient alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($data['alerts']['recipient']) && filter_var($data['recipient_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $data['recipient_email'], 'type' => 'r'] + $data['alerts']['recipient'];
                $shipment->alerts()->create($alert);
            }

            /**
             * If we have a valid broker address and broker alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($data['alerts']['broker']) && isset($data['broker_email']) && filter_var($data['broker_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $data['broker_email'], 'type' => 'b'] + $data['alerts']['broker'];
                $shipment->alerts()->create($alert);
            }

            /**
             * If we have a valid other address and other alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($data['alerts']['other']) && filter_var($data['other_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $data['other_email'], 'type' => 'o'] + $data['alerts']['other'];
                $shipment->alerts()->create($alert);
            }

            /**
             * Create an alert request for the department associated with the shipment (problems only)
             */
            $shipment->setDepartmentAlerts();

            /*
             * Notify IFS staff (if parameters met)
             */
            $shipment->sendIfsNotifications();

            /*
             * Create a collection request for the transport department
             */
            $shipment->createCollectionRequest();

            /*
             * ******************************************
             * Successful so commit all updates
             * ******************************************
             */
            DB::Commit();
        } catch (Exception $e) {

            /*
             * ******************************************
             * Encountered error so rollback transactions
             * ******************************************
             */
            DB::rollBack();

            // Build email
            $to = config('mail.error');
            $subject = 'WebClient DB Error - ' . $to;
            $message = 'Web Client failed to insert shipment ' . "\r\n\r\n" .
                    'App\CarrierAPI\CarrierAPI.php : ' . "\r\n\r\n" .
                    'Function addShipment() : ' . "\r\n\r\n" .
                    'IFS Consignment Number : ' . $data['consignment_number'] . "\r\n\r\n" .
                    'Carrier Consignment Number : ' . $data['carrier_consignment_number'] . "\r\n\r\n" .
                    'Error : ' . $e->getMessage() . " on line " . $e->getLine() . "\r\n\r\n" .
                    json_encode($data);
            $headers = 'From: noreply@antrim.ifsgroup.com' . "\r\n" .
                    'Reply-To: it@antrim.ifsgroup.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            return NULL; // Return null to signify problem
        }


        // Create a tracker
        dispatch(new CreateEasypostTracker($data['carrier_consignment_number'], $shipment->carrier->easypost));

        return $shipment;
    }

    /**
     * Adds Carrier tracking number/ Barcode etc.
     * to Shipment array
     *
     * @param type $data
     * @param type $response
     * @return array Shipment Details
     */
    protected function addCarrierResponse($data, $response)
    {
        // Add Shipment level
        $data['route_id'] = $response['route_id'];
        $data['consignment_number'] = $response['ifs_consignment_number'];
        $data['carrier_consignment_number'] = $response['consignment_number'];
        $data['carrier_tracking_number'] = $response['consignment_number'];

        // Add Carrier Tracking number and Barcode for each package
        for ($i = 0; $i < $response['pieces']; $i++) {
            $data['packages'][$i]['carrier_tracking_number'] = $response['packages'][$i]['carrier_tracking_code'];
            $data['packages'][$i]['barcode'] = $response['packages'][$i]['barcode'];
        }

        $data['label_base64'] = $response['label_base64'];

        return $data;
    }

    /**
     * Creates a random 12 char string to use as a
     * token for a shipment
     *
     * @return string Token
     */
    public function getShipmentToken()
    {

        $getNewToken = true;

        while ($getNewToken) {
            $token = str_random(12);
            $shipment = Shipment::where('token', $token)->first();

            if (!isset($shipment)) {
                $getNewToken = false;
            }
        }

        return $token;
    }

    private function generateErrors($response, $errors)
    {
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $response['errors'][] = $error;
            }
        } else {
            $response['errors'][] = $errors;
        }

        return $response;
    }

    /**
     * Pre-Process data to add any missing data
     *
     * @param array Shipment details
     * @return array Modified Shipment details
     */
    private function preProcess($shipment)
    {
        /*
         * ********************************
         * Shipment level processing
         * ********************************
         */

        // Get company Details
        $this->company = Company::find($shipment['company_id']);

        // Check addresses and perform any necessary Overrides
        $shipment = $this->checkAddresses($shipment);

        if (isset($shipment['service_id']) && $shipment['service_id'] > "") {
            // Set Mode of transport
            $shipment['mode'] = Mode::find($shipment['mode_id'])->name;

            // Identify Department
            $department_code = identifyDepartment($shipment);
            $department = Department::where('code', $department_code)->first();
            if ($department)
                $shipment['department_id'] = $department->id;

            // Set Depot
            $shipment['depot_id'] = $this->company->depot_id;

            // Set Carrier and Service details
            $service = Service::find($shipment['service_id']);
            if ($service) {

                $shipment['carrier_id'] = $service->carrier_id;
                $shipment['carrier_code'] = $service->carrier->code;
                $shipment['service_code'] = $service->code;
                $shipment['volumetric_divisor'] = $service->volumetric_divisor;
            } else {

                $shipment['carrier_id'] = '';
                $shipment['carrier_code'] = '';
                $shipment['service_code'] = '';
                $shipment['volumetric_divisor'] = '';
            }

            // Set IncoTerms if possible
            if (!isset($shipment['terms_of_sale']) || $shipment['terms_of_sale'] == '') {
                if ($shipment['bill_tax_duty'] == 'sender') {
                    $shipment['terms_of_sale'] = 'ddp';
                }
            }

            // Temporary fix for Twinings
            if ($shipment['company_id'] == "608" && !in_array(strtoupper($shipment['recipient_country_code']), ['GB', 'JE', 'GG', 'IE'])) {
                $shipment['bill_shipping'] = 'sender';
                $shipment['bill_tax_duty'] = 'recipient';
                $shipment['terms_of_sale'] = 'dap';
            }

            // Set Bill to accounts
            $shipment['bill_shipping_account'] = $this->setBillToAcct($shipment, 'bill_shipping');
            $shipment['bill_tax_duty_account'] = $this->setBillToAcct($shipment, 'bill_tax_duty');

            // Sort out Description of contents
            if (isset($shipment['ship_reason']) && $shipment['ship_reason'] == 'documents') {

                // Documents Only shipment
                $shipment['documents_description'] = 'Documents Only';
                $shipment['goods_description'] = '';
                $shipment['contents'] = null;

                // Countries that require minimum 1 USD customs value for docs shipments
                if (in_array($shipment['recipient_country_code'], ["NZ", "AM", "AU", "AZ", "BY", "CA", "CN", "CZ", "GE", "JP", "KG", "MD", "PH", "RU", "SK", "UZ", "VE", "KR", "KW"])) {
                    $shipment['customs_value'] = 1;
                    $shipment['customs_value_currency_code'] = 'USD';
                }
            } else {

                // Clear Documents Description as not a Document Shipment
                $shipment['documents_description'] = '';

                // If Commodity set then use first commodity description
                if (isset($shipment['contents'][0]['description']) && $shipment['contents'][0]['description'] > "") {

                    $shipment['goods_description'] = $shipment['contents'][0]['description'];
                } elseif (!isset($shipment['goods_description']) || $shipment['goods_description'] == '') {

                    $shipment['goods_description'] = 'Miscellaneous Goods';
                }
            }

            // Set Weight UOM for each commodity item
            if (isset($shipment['contents'])) {
                for ($i = 0; $i < count($shipment['contents']); $i++) {
                    $shipment['contents'][$i]['weight_uom'] = $shipment['weight_uom'];
                }
            }

            /*
             * Get Ansi code for sender and recipient
             * States for any carriers that need it
             */
            if (!isset($shipment['sender_state']) || $shipment['sender_state'] == '') {
                $shipment['sender_state'] = null;
            }

            if (!isset($shipment['sender_telephone']) || $shipment['sender_telephone'] == '') {
                $shipment['sender_telephone'] = $this->company->telephone;
            }

            $shipment['sender_state_ansi_code'] = State::getAnsiStateCode($shipment['sender_country_code'], $shipment['sender_state']);
            if ($shipment['sender_state_ansi_code'] == '') {
                $shipment['sender_state_code'] = $shipment['sender_state'];
            } else {
                $shipment['sender_state_code'] = $shipment['sender_state_ansi_code'];
            }

            if (!isset($shipment['recipient_state'])) {
                $shipment['recipient_state'] = null;
            }

            $shipment['recipient_state_ansi_code'] = State::getAnsiStateCode($shipment['recipient_country_code'], $shipment['recipient_state']);
            if ($shipment['recipient_state_ansi_code'] == '') {
                $shipment['recipient_state_code'] = $shipment['recipient_state'];
            } else {
                $shipment['recipient_state_code'] = $shipment['recipient_state_ansi_code'];
            }

            // Set Country of final destination
            $shipment['country_of_destination'] = $shipment['recipient_country_code'];

            // Set Routing
            $shipment['route_id'] = 1;

            // If Currency not set then default to Currency for Senders Country
            if (!isset($shipment['customs_value_currency_code']) || $shipment['customs_value_currency_code'] == '') {
                $shipment['customs_value_currency_code'] = Country::where('country_code', $shipment['sender_country_code'])->first()->currency_code;
            }

            // If special_instructions not set then set to empty string
            if (!isset($shipment['special_instructions'])) {
                $shipment['special_instructions'] = '';
            }

            // Adjust collection date for date format and timezone
            $localisation = $this->company->localisation;

            // If date format not already set then set it
            if (!isset($shipment['date_format'])) {
                $shipment['date_format'] = $localisation->date_format;
            }

            // Convert Collection date into a known format.
            $date_format = getDateFormat($shipment['date_format']);
            $shipment['collection_date'] = Carbon::createFromformat($date_format, $shipment['collection_date'], $localisation->time_zone)->format('Y-m-d');

            /*
             * Set the ship date.
             */

            if (isset($shipment['collection_date'])) {
                $shipment['ship_date'] = $shipment['collection_date'];
            } else {
                $shipment['ship_date'] = date('Y-m-d');
            }

            /*
             * ***********************************
             * Package level preProcessing
             * ***********************************
             */
            $dims = [];
            $dryIceTotalWeight = 0;
            $cnt = 0;
            $volumetric_weight = 0;
            foreach ($shipment['packages'] as $package) {

                // Check for Dry Ice
                if (isset($package['dry_ice_weight']) && $package['dry_ice_weight'] > 0) {
                    $shipment['dry_ice_flag'] = true;
                    $dryIceTotalWeight += $package['dry_ice_weight'];
                }

                // Ensure all dims are integers
                $shipment['packages'][$cnt]['length'] = ceil($shipment['packages'][$cnt]['length']);
                $shipment['packages'][$cnt]['width'] = ceil($shipment['packages'][$cnt]['width']);
                $shipment['packages'][$cnt]['height'] = ceil($shipment['packages'][$cnt]['height']);
                $shipment['packages'][$cnt]['index'] = $cnt + 1;

                /*
                * ****************************************
                * Patch for Babocush USA - resize packages
                * Change Dims to match those used by Fedex
                * ****************************************
                */
                if ($shipment['company_id'] == '874') {
                    if ($package['length'] == 67) {
                        if ($package['width'] == 40) {
                            if ($package['height'] == 19) {
                                $shipment['packages'][$cnt]['length'] = 66;
                                $shipment['packages'][$cnt]['height'] = 20;
                            }
                        }
                    }
                }

                // Collect Dimensions
                $dims[] = $package['length'];
                $dims[] = $package['width'];
                $dims[] = $package['height'];

                // Calc Volumetric weight
                $shipment['packages'][$cnt]['volumetric_weight'] = calcVolume(
                        $shipment['packages'][$cnt]['length'], $shipment['packages'][$cnt]['width'], $shipment['packages'][$cnt]['height'], $shipment['volumetric_divisor']
                );

                $volumetric_weight += $shipment['packages'][$cnt]['volumetric_weight'];
                $cnt++;
            }

            $data['max_dimension'] = max($dims);
            if (!isset($shipment['volumetric_weight']) || $volumetric_weight > $shipment['volumetric_weight']) {
                $shipment['volumetric_weight'] = $volumetric_weight;
            }

            // Round volumetric in lbs UP to the nearest lb
            if ($shipment['weight_uom'] == 'lb') {
                $shipment['volumetric_weight'] = ceil($shipment['volumetric_weight']);
            }

            $shipment['dry_ice_total_weight'] = $dryIceTotalWeight;
            $shipment['max_dimension'] = max($dims);
        }

        return $shipment;
    }

    /**
     * Checks addresses and performs any necessary Overrides
     * 
     * @param type $shipment
     * @return string
     */
    public function checkAddresses($shipment)
    {

        if (isset($this->company->shipper_type_override) && $this->company->shipper_type_override > "") {
            $shipment['shipper_type'] = $this->company->shipper_type_override;
        }

        if (isset($this->company->recipient_type_override) && $this->company->recipient_type_override > "") {
            $shipment['recipient_type'] = $this->company->recipient_type_override;
        }

        /*
         * ***************************************************************
         *  Ensure country codes for Jersey and Guernsey are correctly set
         * ***************************************************************
         */
        if (isset($shipment['recipient_postcode'])) {

            // If country code has been set to GB then change
            if (isset($shipment['recipient_country_code']) && $shipment['recipient_country_code'] == 'GB') {

                // Take first 2 chars of the postcode
                $prefix = strtoupper(substr($shipment['recipient_postcode'], 0, 2));
                switch ($prefix) {
                    case 'GY':
                        $shipment['recipient_country_code'] = 'GG';
                        break;

                    case 'IM':
                        $shipment['recipient_country_code'] = 'IM';
                        break;

                    case 'JE':
                        $shipment['recipient_country_code'] = 'JE';
                        break;

                    default:
                        break;
                }
            }
        }

        return $shipment;
    }

    public function setEnvironment($mode = '')
    {

        // if mode is defined then use it
        $env_mode = ($mode > "") ? $mode : App::environment();

        // If Environment variable set to Production, then change mode
        switch (strtoupper($env_mode)) {

            case 'PRODUCTION':
            case 'TESTING':
                $this->mode = 'production';
                break;

            case 'LOCAL':
            case 'TEST':
                $this->mode = 'test';
                break;

            default:
                dd('Unknown Mode : *' . $env_mode . '*');
                break;
        }
    }

    /**
     * Creates Shipment with Carrier and updates tables
     *
     * @param array $data
     * @param string $mode Used by APIController to overide mode
     *
     * @return response
     */
    public function createShipment($data, $mode = '')
    {

        /*
         * ************************************
         * Set Environment, fixCase, preprocess
         * and Validate Shipment
         * ************************************
         */
        $response = [];
        $this->setEnvironment($mode);
        //$data = trimData($data);                                                 // Remove any leading/ trailing spaces etc.        
        $data = fixShipmentCase($data);                                         // Ensure all fields use correct case and Flags are boolean
        $data = $this->preProcess($data);                                       // Complete any missing fields where possible
        $apiShipment = new APIShipment();                                       // Shipment object with validation rules etc.

        $errors = $apiShipment->validateShipment($data);
        if ($errors == []) {

            // No Errors, so send and create shipment
            return $this->sendShipment($data, $mode);
        } else {

            // Return errors
            return $this->generateErrors($response, $errors);                   // Return with errors
        }
    }

    public function sendShipment($data, $mode)
    {

        /*
         * ************************************
         * Build Carrier object and send data
         * to Carrier
         * ************************************
         */
        $this->buildCarrier($data['carrier_code'], $mode);

        // Send shipment data to Carrier
        $response = $this->carrier->createShipment($data);
        if ($response['errors'] == []) {

            /*
             * *********************************
             * No Errors so Process Response
             * *********************************
             */
            if ($data['bill_shipping'] == 'recipient') {

                // Bill Freight to Recipient so don't price
                $charges = [];
                $response['pricing'] = '';
            } else {

                // Bill Freight to Shipper/ Other so Price Shipment
                $charges = Pricing::price($data);
                if (empty($charges['errors'])) {

                    // No errors so add pricing info to response
                    $response = $this->setResponsePricingFields($response, $charges);
                } else {

                    // Errors so blank pricing info
                    $response['pricing'] = '';
                }
            }

            // Write shipment to Database
            $shipmentCreated = FALSE;
            $shipment = $this->writeShipment($data, $charges, $response);
            if (isset($shipment) && $shipment) {

                $shipmentCreated = TRUE;
            }

            // Add Carrier Consignment details to response
            $response = $this->completeResponse($response, $shipment, $shipmentCreated);
        }

        return $response;
    }

    public function completeResponse($response, $data, $shipmentCreated)
    {

        if (strtolower($this->mode) == 'test' || $shipmentCreated) {

            // Everything good so return token, consignment number and tracking URL for shipment
            $response['ifs_consignment_number'] = $data['consignment_number'];
            $response['token'] = $data['token'];
            $response['tracking_url'] = config('app.url') . '/tracking/' . $data['token'];
        } else {

            // Problem saving details - so return and error
            $response['errors'][] = 'System Error (IT Support Notified)';
            $response['label_base64'][0]['base64'] = '';
        }

        return $response;
    }

    public function writeShipment($data, $charges, $response)
    {

        /*
         * *********************************
         * Update shipment with pricing info
         * *********************************
         */
        $data = $this->setPricingFields($data, $charges);
        $data = $this->addCarrierResponse($data, $response);                // Add package barcodes and tracking details etc
        $data['token'] = $this->getShipmentToken();                         // Get unique random token to identify Shipment

        /*
         * ****************************
         * Add Shipment to Database and
         * return response
         * ****************************
         */
        return $this->addShipment($data);
    }

    public function setPricingFields($data, $charges = [])
    {

        if ($charges == []) {

            $data['quoted'] = NULL;
            $data['shipping_cost'] = NULL;
            $data['shipping_charge'] = NULL;
            $data['fuel_cost'] = NULL;
            $data['fuel_charge'] = NULL;
            $data['cost_currency'] = 'GBP';
            $data['sales_currency'] = 'GBP';
        } else {

            $data['quoted'] = json_encode($charges);
            $data['shipping_cost'] = $charges['shipping_cost'];
            $data['shipping_charge'] = $charges['shipping_charge'];
            $data['fuel_cost'] = $charges['fuel_cost'];
            $data['fuel_charge'] = $charges['fuel_charge'];
            $data['cost_currency'] = $charges['cost_currency'];
            $data['sales_currency'] = $charges['sales_currency'];
        }

        return $data;
    }

    public function setResponsePricingFields($response, $charges)
    {

        $response['pricing']['charges'] = $charges['sales'];
        $response['pricing']['vat_code'] = $charges['sales_vat_code'];
        $response['pricing']['vat_amount'] = $charges['sales_vat_amount'];
        $response['pricing']['total_cost'] = $charges['shipping_charge'] + $charges['sales_vat_amount'];

        return $response;
    }

    /**
     * Delete Shipment function
     *
     * @param type $data
     * @param string $mode Used by APIController to overide mode
     *
     * @return string
     */
    public function deleteShipment($data, $mode = '')
    {

        $response = [];
        $this->setEnvironment($mode);

        // Identify Shipment Carrier
        $shipment = Shipment::where('company_id', $data['company_id'])
                ->where('token', $data['shipment_token'])
                ->first();

        if ($shipment) {

            if ($shipment->isCancellable()) {

                $this->setEnvironment();
                $this->buildCarrier($shipment->carrier->code);         // Create Carrier Object
                $response = $this->carrier->deleteShipment($shipment);                  // Send Shipment to Carrier

                if ($response['errors'] == []) {
                    $shipment->setCancelled($data['user_id']);
                }
            } else {

                $response['errors'][] = 'Shipment cannot be cancelled';
            }
        } else {

            $response['errors'][] = 'Shipment not found';
        }

        return $response;
    }

    /**
     * Takes an unaltered PDF from a carrier and returns it in the size requested
     * with the addition of printing/folding instructions for A4/LETTER sizes.
     *
     * @param   mixed   $shipment   Loaded shipment model or shipment identifier.
     * @param   string  $size       Size of the PDF document required (accepts codes defined in print formats table).
     * @param   string  $output     Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    public function getLabel($shipment, $size = 'A4', $output = 'S', $encoded = true, $labelType = '')
    {
        $pdf = new Pdf($size, $output);
        return $pdf->createLabel($shipment, $encoded, $labelType);
    }

    /**
     * Get a batch of labels
     *
     * @param   mixed   $shipment_id   Loaded shipment model or shipment identifier.
     * @param   string  $size       Size of the PDF document required (accepts codes defined in print formats table).
     * @param   string  $output     Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    public function getLabels($shipments, $size = 'A4', $output = 'S', $labelType = '')
    {

        if ($shipments) {

            $doc = new TCPDI();
            $doc->setPrintHeader(FALSE);
            $doc->setPrintFooter(FALSE);
            $hasContent = false;
            foreach ($shipments as $shipment) {

                // Get PDF string for this shipment
                $originalPdf = $this->getLabel($shipment, $size, 'S', false, $labelType);

                if ($originalPdf != 'not found') {

                    $hasContent = true;
                    $pageCount = $doc->setSourceData($originalPdf);

                    // Import Page by Page
                    for ($page = 0; $page < $pageCount; $page++) {

                        // Import PDF page to working area as Image and get size
                        $tpl = $doc->importPage($page + 1);
                        $originalPdfSize = $doc->getTemplateSize($tpl);

                        // Add a blank page to the document, then add content as a Template
                        $doc->AddPage('P', [$originalPdfSize['w'], $originalPdfSize['h']]);
                        $doc->useTemplate($tpl);
                    }
                }
            }

            if ($hasContent) {
                return $doc->Output(date('Ymdhis') . '.pdf', $output);
            } else {
                abort(404);
            }
        }
    }

    /**
     * Generates a commercial invoice.
     *
     * @param   string  $token      Shipment identifier.
     * @param   array   $parameters An array of options for customising invoice.
     * @param   string  $size       Size of the PDF document required (accepts codes defined in print formats table).
     * @param   string  $output     Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    public function getCommercialInvoice($token, $parameters = [], $size = 'A4', $output = 'S')
    {
        $pdf = new Pdf($size, $output);
        return $pdf->createCommercialInvoice($token, $parameters);
    }

    /**
     * Generates a CN22.
     *
     * @param   string  $token      Shipment identifier.
     * @param   string  $output     Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    public function getCN22($token, $output = 'S')
    {
        $pdf = new Pdf('6X4', $output);
        return $pdf->createCN22($token);
    }

    /**
     * Create a despatch note.
     * 
     * @param type $token
     * @param type $size
     * @param type $output
     * @return type
     */
    public function getDespatchNote($token, $size = 'A4', $output = 'S')
    {
        $pdf = new Pdf($size, $output);
        return $pdf->createDespatchNote($token);
    }

}
