<?php

namespace app\CarrierAPI\Fedex;

use App\Carrier;
use App\Company;
use App\CarrierPackagingType;
use App\TransactionLog;
use App\CarrierAPI\Fedex\FedexLabel;
use App\Sequence;
use Carbon\Carbon;
use App\CarrierAPI\Fedex\FedexSettings;
use Illuminate\Support\Facades\Validator;

/**
 * Description of DHLWebAPI.
 *
 * @author gmcbroom
 */
class FedexAPI extends \App\CarrierAPI\CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    protected $carrierPackaging;
    protected $fedex;

    public function __construct($mode)
    {
        parent::__construct($mode);

        /*
         * *****************************************
         * Define fields for Production/ Development
         * *****************************************
         */

        $this->connection['url'] = config('services.fxrs.url');
        $this->connection['port'] = config('services.fxrs.port');

        switch (strtoupper($this->mode)) {
            case 'TEST':
                $this->accounts['meterNumbers'] = array(
                    205691588 => 978666,
                    327423851 => 526564,
                    631510906 => 526938,
                    342638775 => 180565,
                );

                break;

            default:
                $this->accounts['meterNumbers'] = array(
                    205691588 => 482731, // IFS
                    327423851 => 482746, // TEREX
                    631510906 => 482768, // CMASS
                    342638775 => 482764, // ALMAC
                    811732648 => 564790, // DOMESTIC
                    811250724 => 564800  // Glen Dimplex
                );
                break;
        }

        /*
         * OLD METERS FOR 63.34.42.84
         *
        205691588 => 611030, // IFS
        327423851 => 958196, // TEREX
        631510906 => 477484, // CMASS
        342638775 => 991058, // ALMAC
        811732648 => 418725, // DOMESTIC
        811250724 => 418727  // Glen Dimplex
        */


        /*
         * *****************************************
         * Define Carrier Specific field values
         *
         * i.e. IFS => Carrier conversion
         * *****************************************
         */

        $this->fedex = new FedexSettings();
    }

    public function validateDeleteShipment($shipment)
    {

        /*
         * ****************************************
         * Carrier/ Service Specific validation
         * ****************************************
         */

        $errors = [];

        return $errors;
    }

    /**
     *
     * @param array $data containing company_id, user_id, shipment_token
     *
     * @return type
     */
    public function deleteShipment($shipment)
    {
        $response = [];

        $message = $this->buildDeleteShipmentMsg($shipment);

        $reply = $this->sendMessageToCarrier($message, 'delete_shipment');

        // Check for errors
        if (isset($reply['3']) && $reply['3'] > '') {
            // Request unsuccessful - return errors
            $response = $this->generateErrorResponse($response, $reply[3]);
        } else {
            // Request succesful - Prepare Response
            $response = $this->deleteShipmentResponse($reply);
        }

        return $response;
    }

    public function buildDeleteShipmentMsg($shipment)
    {

        // Encode the Message
        $msg = '0,"023"1,"cancel shipment"29,"' . $shipment->carrier_consignment_number . '"99,""';

        return $msg;
    }

    private function sendMessageToCarrier($message, $msgType)
    {
        $this->transactionHeader = $this->getData($message, 'transaction_id');

        $replyMsg = $this->transmitMessage($message);

        $reply = $this->decode($replyMsg);

        return $reply;
    }

    public function transmitMessage($msg)
    {
        $seconds = 5;
        $milliseconds = 0;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $seconds, 'usec' => $milliseconds));

        $connected = socket_connect($socket, $this->connection['url'], $this->connection['port']);


        if ($connected) {
            socket_write($socket, $msg, strlen($msg));

            $msgType = substr($msg, 3, 3);
            switch ($msgType) {
                case '020':
                    TransactionLog::create(['carrier' => 'fedex', 'type' => 'MSG', 'direction' => 'O', 'msg' => $msg, 'mode' => $this->mode]);
                    break;

                case '120':
                    TransactionLog::create(['carrier' => 'fedex', 'type' => 'REPLY', 'direction' => 'I', 'msg' => $msg, 'mode' => $this->mode]);
                    break;

                case '023':
                    TransactionLog::create(['carrier' => 'fedex', 'type' => 'CANX', 'direction' => 'O', 'msg' => $msg, 'mode' => $this->mode]);
                    break;

                default:
                    TransactionLog::create(['carrier' => 'fedex', 'type' => 'Unknown', 'direction' => 'O', 'msg' => $msg, 'mode' => $this->mode]);
                    break;
            }

            $string = '';
            $i = 0;
            while (!preg_match('/"99,""/', $string)) {
                $char = socket_read($socket, 1);
                $string .= $char;
            }

            socket_close($socket);
        } else {
            // Create a dummy response with error to pass back
            $string = '0,"120"3,"Unable to connect to Carrier System"99,""';

            // Build email
            $to = 'it@antrim.ifsgroup.com';
            $subject = 'Courier Intl FXRS Server Error';
            $message = 'Web Client unable to communicate with the FXRS Server';
            $headers = 'From: noreply@antrim.ifsgroup.com' . "\r\n" .
                'Reply-To: it@antrim.ifsgroup.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);
        }

        $this->reply = $string;
        TransactionLog::create(['carrier' => 'fedex', 'type' => 'REPLY', 'direction' => 'I', 'msg' => $string, 'mode' => $this->mode]);

        return $string;
    }

    public function decode($data, $log = '')
    {
        if ($log == true) { //Log the reply to database
            $this->logMsg('', $data, 'REPLY');
        }

        $reply = [];
        $notFinished = true;
        $offSet = 0;

        while ($notFinished) {
            if (ord(substr($data, 0, 1)) == 0) {
                $data = substr($data, 1, 999);                                  // Remove starting null string if it exists
            }

            if (strlen($data) > 3) {
                $t = strpos($data, ',', $offSet);                               // Find position of comma
                $q1 = strpos($data, '"', $offSet + 1);                          // Find Position of first Quote
                $q2 = strpos($data, '"', $q1 + 1);                              // Find Position of Second Quote
                $fieldNo = substr($data, $offSet, $t - $offSet);                // Extract Field No
                $mvp = strpos($fieldNo, '-');                                   // Check to see if a multivalue field
                $fieldNoAbs = $this->getFldNo($fieldNo);
                $fieldData = substr($data, $q1 + 1, $q2 - $q1 - 1);             // Extract Data portion
                $offSet = $q2 + 1;

                // If Valid Field no - PreProcess Data
                if ($fieldNoAbs > 0) {
                    if (!isset($reply[$fieldNoAbs])) {
                        $reply[$fieldNoAbs] = '';
                    }
                    $fieldData = $this->multiplier($fieldNoAbs, $fieldData, 'decode');
                    $reply[$fieldNoAbs] = $this->addValue($reply[$fieldNoAbs], $fieldNoAbs, $fieldData);
                }
                /*
                 * Finish Single/ Multivalues
                 */
            } else {
                $reply = [];                                         // Invalid response - so return empty string.
                $notFinished = true;
            }
            if ($fieldNo == '99') {
                $notFinished = false;
            }
        }

        return $reply;
    }

    private function getFldNo($fieldNo)
    {
        $mvp = strpos($fieldNo, '-');                                          // Check to see if a multivalue field
        if ($mvp > 0) {
            $fieldNo = substr($fieldNo, 0, $mvp);                             // Field No minus any mv additions eg 79-1 becomes 79
        }

        return $fieldNo;
    }

    public function multiplier($key, $value, $mode)
    {
        #######################################################################
        #          Fields Requiring PreProcessing of Data
        #######################################################################
        $mult = 0;

        $key = $this->getFldNo($key);

        // If Field requires preprocessing
        if (isset($this->fedex->mult[$key]) && $this->fedex->mult[$key] > 0) {
            switch ($mode) {

                case 'decode':
                    $value = $value / $this->fedex->mult[$uom][$key];
                    break;

                default:
                    break;
            }
        }

        // Round to remove remaining dec. places
        switch ($key) {
            case '77':
            case '78':
            case '112':
            case '119':
            case '1030':
            case '1086':
            case '1670':
            case '1684':
                $value = round($value);
                break;
        }

        return $value;
    }

    private function addValue($existing = '', $fieldNoAbs, $fieldData)
    {

        /*
         * Code to handle single/ multivalue values
         */
        if (in_array($fieldNoAbs, $this->fedex->mvfields)) {
            // Not Single value so change to array
            $mvArr = []; //clear

            /*
             * Values may already exist so add
             * any existing values into the mvArr
             */
            if (is_array($existing)) {
                foreach ($existing as $v) {
                    $mvArr[] = $v;
                }
            }

            // Now add any new values
            $mvArr[] = $fieldData; // Store this value
            // Replace previous values
            $reply[$fieldNoAbs] = $mvArr;
        } else {
            // Single Value
            $mvArr = $fieldData;
        }

        return $mvArr;
    }

    private function deleteShipmentResponse($reply)
    {
        $response = $this->generateSuccess();

        // Add additional data to be returned
        $response['carrier_code'] = 'FEDEX';
        $response['consignment_number'] = $reply['29'];

        return $response;
    }

    public function createShipment($shipment)
    {
        $response = [];
        $shipment = $this->preProcess($shipment);

        $errors = $this->validateShipment($shipment);
        if ($errors != []) {
            return $this->generateErrorResponse($response, $errors);
        } else {

            // Build Message
            $message = $this->buildShipmentMsg($shipment);

            $reply = $this->sendMessageToCarrier($message, 'create_shipment');

            // Check for errors
            if (isset($reply['3']) && $reply['3'] != []) {

                // Request unsuccessful - return errors
                return $this->generateErrorResponse($response, $reply[3]);
            } else {
                $fedexRoute = new \App\FedexRoute();
                $route_id = $fedexRoute->getRouteId($shipment);

                // Set splitServiceBox if Rughouse Economy UK48
                $splitServiceBox = false;
                if (($shipment['service_id'] == '19' && $shipment['company_id'] == '808') || ($shipment['service_id'] == '19' && $shipment['company_id'] == '993')) {
                    $splitServiceBox = true;
                }

                // Prepare Response
                $response = $this->createShipmentResponse($reply, $shipment['service_code'], $route_id, $splitServiceBox);

                return $response;
            }
        }
    }

    private function preProcess($shipment)
    {

        // Find Senders Fedex Account
        $service = Company::find($shipment['company_id'])->services()->where("code", $shipment['service_code'])->where("carrier_id", "2")->first();

        if (!empty($service)) {
            if ($service->pivot->account > "") {
                $shipment['sender_account'] = $service->pivot->account;
            } else {
                $shipment['sender_account'] = $service->account;
            }
        }

        // Catch instance where above code not setting account - needs looked at
        if (!isset($shipment['sender_account'])) {
            if (strtoupper($shipment['service_code']) == 'UK48') {
                $shipment['sender_account'] = 811732648;
            } else {
                $shipment['sender_account'] = 205691588;
            }
        }

        // If bill to recipient set Recipient Fedex Account
        if (strtoupper($shipment['bill_shipping']) == "RECIPIENT") {
            $shipment['recipient_account'] = $shipment['bill_shipping_account'];
        }

        // Fudge for Guernsey & Jersey
        if (in_array(strtolower($shipment['recipient_country_code']), ['gg', 'je', 'im'])) {
            $shipment['recipient_country_code'] = 'gb';
        }

        // Fudge to set correct routing for Fedex UK shipments
        if (strtoupper($shipment['sender_country_code']) == "GB" && strtoupper($shipment['recipient_country_code']) == "GB") {
            $shipment['sender_postcode'] = 'XY35';
        }

        // Setup Package Types
        $this->fedex->packageTypes = Company::find($shipment['company_id'])
            ->buildPackageTypesArray($shipment['mode_id'], 'FEDEX');

        return $shipment;
    }

    /**
     * Perform Carrier Specific Validation
     *
     * @param array Shipment details
     * @return array Array of Errors or empty string if none
     */
    public function validateShipment($shipment)
    {

        /*
         * ****************************************
         * Carrier/ Service Specific validation
         * ****************************************
         */

        $errors = [];
        $rules = [];

        // Check Service is valid for Carrier
        $rules['service_code'] = $this->addServiceRules($shipment);

        // Add rules for max Package weight
        // $rules = $this->addRulesMaxWeight($rules, $shipment['service_code']);
        // Validate Shipment using the rules
        $errors = $this->applyRules($rules, $shipment);

        /*
         * ******************************
         * Manual Validation routines
         * ******************************
         */
        $errors = $this->validatePackageTypes($errors, $this->getData($shipment, 'packages'));

        $errors = $this->validateOptions($errors, $shipment);

        return $errors;
    }

    /**
     * Check Packaging codes are correct for carrier
     * @param array $errors
     * @param array $packages
     *
     * @return array $errors
     */
    private function validatePackageTypes($errors, $packages)
    {

        // Check each Package is valid for this Carrier
        $cnt = 1;
        foreach ($packages as $package) {
            if (!isset($this->fedex->packageTypes[$package['packaging_code']])) {
                $errors[] = "packages.$cnt.packaging_code is invalid for carrier.";
            }
            $cnt++;
        }

        return $errors;
    }

    private function validateOptions($errors, $shipment)
    {
        $cnt = 1;
        $options = $this->getData($shipment, 'options');
        if ($options != '') {
            foreach ($options as $option) {
                $errors = $this->validateOption($errors, $option, $shipment);
            }
        }

        return $errors;
    }

    private function validateOption($errors, $option, $shipment)
    {
        if (in_array($option, $this->fedex->options)) {
            // Supported option - now check valid for service etc.
            switch ($option) {
                case 'CARGO':
                    $errors = $this->validateCargoOption($errors, $shipment);
                    break;

                default:
                    break;
            }
        } else {
            $errors[] = "Option : $option not supported";
        }

        return $errors;
    }

    private function validateCargoOption($errors, $shipment)
    {
        $hazardCode = $this->getData($shipment, 'hazardous');
        if (!($hazardCode == 'E' || ((intval($hazardCode) >= 1) && (intval($hazardCode) <= 9)))) {
            $errors[] = "CARGO option for DG Shipments only";
        }

        return $errors;
    }

    public function buildShipmentMsg($data)
    {

        /*
         * Set the meter number
         */
        if (isset($this->accounts['meterNumbers'][$data['bill_shipping']])) {
            $data['meterNumber'] = $this->accounts['meterNumbers'][$data['bill_shipping']];
        }

        // Identify which Groups we wish to output
        $msgGroups = [];
        $msgGroups[] = 'GENERAL';  // Always output General
        $msgGroups[] = 'SHIPMENT'; // Always output SHIPMENT
        $msgGroups[] = 'PACKAGE';  // Always output SHIPMENT
        $msgGroups[] = 'PAYMENT';  // Always output PAYMENT
        $msgGroups[] = 'ALERT';    // Always output ALERT
        $msgGroups[] = 'OPTION';   // Always output OPTION
        $msgGroups[] = 'PRINTER';  // Output Package level details

        if ($this->getData($data, 'broker.city') != '') {
            $msgGroups[] = 'BROKER'; // Broker Select Option enabled
            $data = $this->setElement($data, 'broker_select', 'Y');
        }

        if ($this->getData($data, 'ship_reason') == 'documents' || $this->getData($data, 'documents_flag') == 'Y') {
            $msgGroups[] = 'DOCUMENTS'; // Document Shipment
            $data['documents_flag'] = 'Y';
        } else {
            $msgGroups[] = 'COMMODITY'; // Commodity details
        }

        if ($this->getData($data, 'hazardous') != 'N' && $this->getData($data, 'hazardous') != '') {
            $msgGroups[] = 'DGOODS'; // Dangerous Goods Flag Set
            $data = $this->setElement($data, 'hazard_flag', 'Y');
        }

        if ($this->getData($data, 'alcohol.quantity') > 0) {
            $msgGroups[] = 'ALCOHOL'; // Alcohol Flag Set
            $data = $this->setElement($data, 'alcohol.flag', 'Y');
        }

        $msgData = $this->buildGroup($data, $msgGroups);

        // Encode the result
        $msg = '0,"020"' . $this->encode($msgData) . '99,""';

        return $msg;
    }

    public function buildGROUP($data, $msgGroups)
    {
        $msgData = [];

        // Replace field names with correct "020 field numbers"
        foreach ($msgGroups as $requestedGroup) {

            // Loop through all the variables the user may submit
            foreach ($this->fedex->fldno as $key => $value) {

                // Extract only the fields within the current "Requested Group"
                if ($this->fedex->group[$key] == $requestedGroup) {

                    /*
                     * ******************************************
                     * Process Fields
                     *
                     * Named fields require special processing
                     * Unnamed fields are processed by "default"
                     * ******************************************
                     */
                    $value = $this->getData($data, $key);
                    $uom = $this->getData($data, 'weight_uom');

                    switch ($key) {

                        case 'signature_required':
                            if (strtoupper($data['service_code']) == 'UK48') {
                                $msgData[$this->fedex->fldno['signature_required']] = 2;
                            }
                            break;

                        case 'documents_flag':

                            $msgData[$this->fedex->fldno['documents_flag']] = 'Y';

                            if ($data['documents_flag'] && $data['customs_value'] == 1) {
                                $msgData[$this->fedex->fldno['documents_description']] = '9';
                            } else {
                                $msgData[$this->fedex->fldno['documents_description']] = '0';
                            }

                            if (!isset($msgData['79-1']) || $msgData['79-1'] == '') {
                                $msgData['79-1'] = 'Documentation/ No Commercial Value';
                            }
                            break;

                        case 'hazardous':

                            if ($value == 'E' || ((intval($value) >= 1) && (intval($value) <= 9))) {

                                // If hazardous = "E"
                                if (strtoupper($value) == 'E') {
                                    $msgData[$this->fedex->fldno['hazard_excepted_qty']] = 'Y';
                                } else {
                                    // Must be 1-9
                                    $msgData[$this->fedex->fldno['hazard_class']] = $value;
                                    $msgData[$this->fedex->fldno['hazard_excepted_qty']] = 'N';
                                }

                                $msgData[$this->fedex->fldno['hazard_flag']] = $this->fedex->hazardFlags[$value];
                                // Preset Values
                                $msgData[$this->fedex->fldno['hazard_commodity_count']] = '1';
                                $msgData[$this->fedex->fldno['hazard_name_of_signatory']] = 'DG NAME';
                                $msgData[$this->fedex->fldno['hazard_place_of_signatory']] = 'DG PLACE';
                                $msgData[$this->fedex->fldno['hazard_title_of_signatory']] = 'DG TITLE';
                            }
                            break;

                        case 'lithium_batteries':

                            if (is_numeric($value)) {
                                $packages = $this->getData($data, 'packages');
                                $numberOfPackages = count($packages);

                                for ($i = 1; $i <= $numberOfPackages; $i++) {
                                    $msgData[$this->fedex->fldno['lithium_batteries'] . '-' . $i] = $value;
                                }
                            }

                            break;

                        case 'volumetric_weight':
                            $msgData [$this->fedex->fldno[$key]] = $this->fedex->multiplier($uom, $key, $value);
                            break;

                        case 'weight':
                            $msgData [$this->fedex->fldno[$key]] = $this->fedex->multiplier($uom, $key, $value);
                            break;

                        case 'recipient_type':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = $this->fedex->addressType[$value];
                            }
                            break;

                        case 'special_services':
                            // $msg_data['0'] = $this->special_services($data, $key, $msgData);
                            break;

                        case 'dims_uom':
                            $msgData[$this->fedex->fldno['dims_uom']] = $this->fedex->shortDimensionUnits[$value];
                            break;

                        case 'bill_shipping':
                            if ($value > '') { // sender_account
                                $msgData[$this->fedex->fldno['sender_account']] = $data['sender_account'];
                                $msgData[$this->fedex->fldno['bill_shipping']] = $this->fedex->paymentType[$value];
                                $msgData[$this->fedex->fldno['bill_shipping_account']] = $data['bill_shipping_account'];
                                $msgData[$this->fedex->fldno['bill_shipping_to_country']] = $this->getPayorCountry($data, $value);
                            }
                            break;

                        case 'bill_tax_duty':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno['bill_tax_duty']] = $this->fedex->paymentType[$value];
                                $msgData[$this->fedex->fldno['bill_tax_duty_account']] = $data['bill_tax_duty_account'];
                                $msgData[$this->fedex->fldno['bill_tax_duty_to_country']] = $this->getPayorCountry($data, $value);
                            }
                            break;

                        case 'terms_of_sale':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = $this->fedex->terms[$value];
                            }
                            break;

                        case 'weight_uom':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = $this->fedex->weightUnits[$value];
                            }
                            break;

                        case 'dimmension_uom':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = $this->fedex->shortDimensionUnits[$value];
                            }
                            break;

                        case 'service_code':
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = $this->fedex->svc[$value];
                            }

                            break;

                        case 'label_specification.label_size':
                            // Always create 4x6 PDF label
                            $msgData[$this->fedex->fldno['label_specification.label_size']] = $this->fedex->labelStockType['PNG']['6X4'];
                            $msgData[$this->fedex->fldno['label_specification.label_path']] = 'C:\\FedEx\\FedEx_Temp\\';
                            $msgData[$this->fedex->fldno['label_specification.printer_type']] = 'S';
                            $msgData[$this->fedex->fldno['custom_label_flag']] = 'N';
                            break;

                        case 'customs_value_currency_code':

                            if ($value > '') {
                                switch ($value) {
                                    case 'GBP':
                                        $msgData[$this->fedex->fldno[$key]] = 'UKL';
                                        $msgData[$this->fedex->fldno['insurance_currency']] = 'UKL';
                                        break;

                                    default:
                                        $msgData[$this->fedex->fldno[$key]] = $value;
                                        $msgData[$this->fedex->fldno['insurance_currency']] = $value;
                                        break;
                                }
                            }

                            break;

                        case 'insurance_value':
                            if (is_numeric($value) && $value > 0) {
                                $msgData [$this->fedex->fldno[$key]] = $this->fedex->multiplier($uom, $key, $value);
                            }
                            break;

                        case 'collection_date':
                            // Change format
                            if ($value > '') {
                                $msgData[$this->fedex->fldno[$key]] = Carbon::createFromFormat('Y-m-d', $this->getData($data, 'collection_date'))->format('Ymd');
                            }
                            break;

                        case 'packages.*.weight':
                            $cnt = 1;
                            $packages = $this->getData($data, 'packages');
                            if ($packages > '') {
                                foreach ($packages as $package) {
                                    $msgData[$this->fedex->fldno['packages.*.sequence_number'] . '-' . $cnt] = $cnt;
                                    $msgData[$this->fedex->fldno['packages.*.packaging_code'] . '-' . $cnt] = $this->fedex->packageTypes[$packages[$cnt - 1]['packaging_code']];
                                    $msgData[$this->fedex->fldno['packages.*.weight'] . '-' . $cnt] = $this->fedex->multiplier($uom, 'packages.*.weight', $packages[$cnt - 1]['weight']);
                                    $msgData[$this->fedex->fldno['packages.*.length'] . '-' . $cnt] = $packages[$cnt - 1]['length'];
                                    $msgData[$this->fedex->fldno['packages.*.width'] . '-' . $cnt] = $packages[$cnt - 1]['width'];
                                    $msgData[$this->fedex->fldno['packages.*.height'] . '-' . $cnt] = $packages[$cnt - 1]['height'];

                                    if (isset($packages[$cnt - 1]['dry_ice_weight']) && $packages[$cnt - 1]['dry_ice_weight'] > 0) {
                                        // Dry Ice Shipment
                                        $msgData[$this->fedex->fldno['packages.*.dry_ice_weight'] . '-' . $cnt] = $this->fedex->multiplier($uom, 'packages.*.dry_ice_weight', $packages[$cnt - 1]['dry_ice_weight']);
                                        $msgData[$this->fedex->fldno['dry_ice_flag']] = 'Y';
                                    }

                                    if ($this->fedex->packageTypes[$packages[$cnt - 1]['packaging_code']] == '01') {
                                        // Customer using own packaging
                                        $msgData[$this->fedex->fldno['adm_type']] = 'BOX';
                                    }

                                    $cnt++;
                                }
                            }
                            break;

                        case 'commercial_invoice_comments':

                            $comments = $this->getData($data, 'commercial_invoice_comments');
                            if ($comments > '') {
                                $msgData[$this->fedex->fldno ['commercial_invoice_comments']] = $comments;
                            }

                            $cnt = 1;
                            $contents = $this->getData($data, 'contents');
                            if ($contents > '') {
                                foreach ($contents as $content) {
                                    $uom = $this->getData($content, 'weight_uom');
                                    // Add multiplier if unit weight is in KG (Commodity level)
                                    $msgData[$this->fedex->fldno['contents.*.description'] . '-' . $cnt] = $this->getElement($content, 'description');
                                    $msgData[$this->fedex->fldno['contents.*.quantity'] . '-' . $cnt] = $this->getElement($content, 'quantity');
                                    $msgData[$this->fedex->fldno['contents.*.uom'] . '-' . $cnt] = $this->getElement($content, 'uom');
                                    $msgData[$this->fedex->fldno['contents.*.unit_value'] . '-' . $cnt] = $this->getElement($content, 'unit_value');
                                    $msgData[$this->fedex->fldno['contents.*.country_of_manufacture'] . '-' . $cnt] = $this->getElement($content, 'country_of_manufacture');
                                    $msgData[$this->fedex->fldno['contents.*.unit_weight'] . '-' . $cnt] = $this->fedex->multiplier($uom, 'contents.*.unit_weight', $this->getElement($content, 'unit_weight'));
                                    // $msgData[$this->fldno['contents.*.weight_uom'] . '-' . $cnt] = $this->getData($data, 'weight_uom');
                                    $msgData[$this->fedex->fldno['contents.*.total_value'] . '-' . $cnt] = $this->getElement($content, 'quantity') * $this->getElement($content, 'unit_value');
                                    $msgData[$this->fedex->fldno['contents.*.harmonized_code'] . '-' . $cnt] = $this->getElement($content, 'harmonized_code');
                                    $msgData[$this->fedex->fldno['contents.*.part_number'] . '-' . $cnt] = $this->getElement($content, 'part_number');
                                    $msgData[$this->fedex->fldno['contents.*.export_license'] . '-' . $cnt] = $this->getElement($content, 'export_license');
                                    if ($this->getElement($content, 'export_license_date') > '') {
                                        $msgData[$this->fedex->fldno['contents.*.export_license_date'] . '-' . $cnt] = date('Ymd', strtotime($this->getElement($content, 'export_license_date')));
                                    }
                                    $cnt++;
                                }
                            }
                            break;

                        default:
                            $msgData [$this->fedex->fldno[$key]] = $this->fedex->multiplier($uom, $key, $value);
                    }
                }
            }
        }

        // Finally add any options
        if ($this->getData($data, 'options') != '') {
            $msgData = $this->addOptions($msgData, $this->getData($data, 'options'));
        }

        return $msgData;
    }

    private function getPayorCountry($data, $value)
    {
        $reply = '';
        switch (strtolower($value)) {
            case 'shipper':
                $reply = $this->getData($data, 'sender_country_code');
                break;

            case 'recipient':
                $reply = $this->getData($data, 'recipient_country_code');
                break;

            default:
                break;
        }

        return $reply;
    }

    public function addOptions($msgData, $options)
    {

        /*
         * SPECIAL OPTIONS Field found
         */
        foreach ($options as $option) {
            switch (strtoupper($option)) {
                case 'HOLD':
                    $msgData [1200] = 'Y';
                    break;

                case 'DROPOFF':
                    $msgData [1333] = 'Y';
                    break;

                case 'BOOK':
                    $msgData [1272] = 'Y';
                    break;

                case 'SATDELIV':
                    $msgData [1266] = 'Y';
                    break;

                case 'CARGO':
                    $msgData [488] = 'Y';
                    break;

                default:
                    break;
            }
        }

        return $msgData;
    }

    public function encode($arrData)
    {
        // Receives an array of field numbers and values
        $finished = false;
        $msg = '';
        foreach ($arrData as $arrKey => $arrValue) {
            if ($arrValue != '' && $arrValue != '!') {
                $fldNoAbs = $this->getFldNo($arrKey);
                $value = $this->multiplier($fldNoAbs, $arrValue, 'encode');
                $msg = $msg . $arrKey . ',"' . $value . '"';
            }
        }

        return $msg;
    }

    private function createShipmentResponse($reply, $serviceCode, $route_id, $splitServiceBox = false)
    {
        $response = $this->generateSuccess();

        if (!isset($reply['664'])) {
            $response['errors'][] = 'Failed to generate barcode: please verify recipient address and postcode';
            return $response;
        }

        $response['route_id'] = $route_id;
        $response['carrier'] = 'fedex';
        $response['ifs_consignment_number'] = nextAvailable('CONSIGNMENT');
        $response['consignment_number'] = $reply['29'][0];
        $response['volumetric_divisor'] = getVolumetricDivisor($response['carrier'], $serviceCode);       // From Helper functions

        if (is_array($reply['664'])) {

            // Multiple Pieces
            $cnt = count($reply['664']);
            $response['pieces'] = $cnt;
            for ($i = 0; $i < $cnt; ++$i) {
                $response['packages'][$i]['sequence_number'] = $i + 1;
                $response['packages'][$i]['carrier_tracking_code'] = $reply['29'][$i];
                $response['packages'][$i]['barcode'] = $reply['664'][$i];
            }
        } else {

            // Single Piece
            $response['pieces'] = 1;
            $response['packages'][0]['sequence_number'] = 1;
            $response['packages'][0]['carrier_tracking_code'] = $reply['29'];
            $response['packages'][0]['barcode'] = $reply['664'];
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';

        $response['label_base64'][0]['carrier_tracking_number'] = $reply['29'][0]; // Master AWB no
        $response['label_base64'][0]['base64'] = $this->generatePDF($reply['29'], $serviceCode, $route_id, $splitServiceBox);

        return $response;
    }

    private function generatePdf($data, $serviceCode = '', $route_id, $splitServiceBox = false)
    {
        $label = new FedexLabel($data, $serviceCode, $this->connection['url'], $route_id, $splitServiceBox);
        return $label->create();
    }

    /**
     * Function to generate rules for Max Package weight
     *
     * @param array Rules
     * @param string Service_code
     *
     * @return array Rules
     */
    private function addRulesMaxWeight($rules, $serviceCode)
    {

        // Check Max Package Weight
        switch ($serviceCode) {

            case 'ipf':
            case 'frt':
                // Replace existing rule
                $rules['packages.*.weight'] = 'required_if:Service,ip|numeric|min:60.00';
                break;

            case 'ip':
            case 'exp':
                // Replace existing rule
                $rules['packages.*.weight'] = 'required_if:Service,ip|numeric|max:49.50';
                break;

            case 'uk48':
                // Replace existing rule
                $rules['packages.*.weight'] = 'required_if:Service,uk48|numeric|max:49.50';
                break;

            default:
                break;
        }

        return $rules;
    }

    /*
     * *********************************************
     * *********************************************
     * Start of Interface Calls
     * *********************************************
     * *********************************************
     */

    private function getFldId($fieldNo)
    {
        $fieldId = 1;                                                           // Set default ref to 1 (only applies to mv items)
        $mvp = strpos($fieldNo, '-');                                           // Check to see if a multivalue field
        if ($mvp > 0) {
            $fieldId = substr($fieldNo, $mvp + 1, strlen($fieldNo) - $mvp);
        }

        return $fieldId;
    }

    private function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (curl_exec($ch) !== false) {
            return true;
        } else {
            return false;
        }
    }
}
