<?php

/**
 * Page-level DocBlock.
 * @todo Complete the following functions
 *
 *      setDepartmentID
 *      setModeID
 *
 * Fields not updating Shipment tables
 *
 *      pallet_flag     - Not Required
 *
 * Clean up fields in data array
 */

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\CarrierAPI\APIShipment;
use App\CarrierAPI\Facades\APIResponse;
use App\CarrierAPI\Facades\CarrierAPI;
use App\Models\CarrierService;
use App\Models\Company;
use App\Models\CompanyPackagingType;
use App\Models\CompanyService;
use App\Models\CompanyUser;
use App\Models\Postcode;
use App\Models\Shipment;
use App\Models\TransactionLog;
use App\Pricing\Facades\Pricing;
use App\Scan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as Input;

class APIController extends Controller
{
    private $transactionHeader;
    private $dummyPrices = ['FRT' => 25.00, 'OOA' => 3.50, 'Fuel' => 1.11];
    private $input;

    /**
     * Returns a new controller instance.
     *
     * @return
     */
    public function __construct()
    {
        // Declare variable used to define Environment settings
        global $appMode;
        $appMode = config('app.env');

        // generate a psuedo random id for transaction header
        $this->transactionHeader = bin2hex(openssl_random_pseudo_bytes(14));
    }

    public function notSupported()
    {
        return APIResponse::respondNotSupported('Method/ URI not supported');
    }

    /**
     * Accepts a POST object, validates it
     * and creates a shipment and documentation.
     *
     * @param Request
     * @return Response
     */
    public function createShipment($version)
    {

        // Decode data and insert user_id of Authenticated User
        $this->decodeInput('createShipment', $version);

        if ($this->input['data']['user_id'] == 'UnAuthorized' || $this->input['data']['company_id'] == 'UnAuthorized') {
            // Authentication Failed
            return APIResponse::respondUnauthorized();
        }

        if ($this->input['data']['user_id'] == 'NotAvailable') {
            // Mode not available
            return APIResponse::respondNotAvailable();
        }

        // Authenticated so do initial processing
        $this->preProcessShipment();

        if ($this->input['data']['errors'] == []) {
            $isAPITest = $this->checkIfApiTest();

            // If this is an APITEST then return dummy transaction
            if ($isAPITest) {
                $data = json_decode('{"errors":[],"route_id":1,"carrier":"APITest Carrier","ifs_consignment_number":"10009999999","consignment_number":"10009999999","volumetric_divisor":5000,"pieces":1,"packages":[{"index":1,"carrier_tracking_number":"1000100099999999999999","barcode":"1000638144501525076895","carrier_tracking_code":"1000100099999999999999"}],"label_format_type":"PDF","label_size":"6X4","label_base64":[{"carrier_tracking_number":"10009999999","base64":""}],"pricing":{"charges":[{"code":"FRT","description":"Some Package(s) to Area 1","value":"22.50"}],"vat_code":"Z","vat_amount":0,"total_cost":22.5},"token":"DUMMYjzo7ekO","tracking_url":"https://ship.ifsgroup.com/tracking/DUMMYjzo7ekO"}', true);
                $response = APIResponse::respondCreatedShipment($data, $version);        // Reformat response for API user
            } else {

                // Check we found an appropriate carrier
                if (isset($this->input['data']['carrier_id']) && $this->input['data']['carrier_id'] > '') {

                    // Authenticated so try to create shipment
                    $reply = CarrierAPI::createShipment($this->input['data'], $this->mode);

                    if ($reply['errors'] == []) {
                        if (isset($reply['carrier_code'])) {
                            $reply['carrier'] = $reply['carrier_code'];
                            unset($reply['carrier_code']);
                        }
                    }

                    $response = APIResponse::respondCreatedShipment($reply, $version);        // Reformat response for API user
                } else {
                    $response = APIResponse::respondNoCarrierAvailable();
                }
            }
        } else {
            $response = APIResponse::respondInvalid($this->input['data']);      // Errors in data
        }

        return $response;
    }

    /**
     * Retrieves data from the Request object
     * Decodes the JSON data element and adds
     * User_id and Company_id.
     *
     * @param Request
     * @return none Updates $this->input
     */
    private function decodeInput($command, $version, $ifsConsignmentNumber = '', $companyCode = '')
    {

        // Check Valid URL & set DB connection depending on the URL
        $this->setMode($version);

        if ($this->input['data']['user_id'] != 'UnAuthorized') {

            // Perform Actions
            switch ($command) {

                case 'priceShipment':
                case 'createShipment':
                    $this->decodeShipmentDetails();
                    break;

                case 'deleteShipment':
                    $this->decodeDeleteShipment($ifsConsignmentNumber, $companyCode);
                    break;

                default:
                    $this->returnUnauthorized('Invalid URL');
                    break;
            }
        }
    }

    public function setMode($version)
    {
        switch ($version) {
            case 'test':
            case 'testv1':

                // Set mode to Test
                $this->mode = 'test';

                // Change Database to test
                config(['database.default' => 'apitest']);
                break;

            case 'v1':
            case 'v1.1':
            case 'v2':
                $this->mode = 'production';
                break;

            default:
                $this->returnUnauthorized('UnAuthorized');
                break;
        }
    }

    public function returnUnauthorized($message = '')
    {

        // Missing data - Return error
        $this->input['data']['user_id'] = 'UnAuthorized';
        $this->input['data']['errors'][] = $message;
    }

    public function decodeShipmentDetails()
    {

        // Fetch API input as an Array
        $data = $this->getInput();
        if (empty($data)) {

            // Missing data - Return error
            $this->returnUnauthorized('Invalid Data or Characterset incorrect');
        } else {

            // log Transaction
            $jsonData = $this->convertToJsonAndLog($data);

            $this->APIShipment = new APIShipment();
            $this->APIShipment->loadFromJSON($jsonData);                        // Read JSON from input into APIShipment Object
            $this->APIShipment->translate();                                    // Translate into Internal API format

            $this->input['data'] = $this->APIShipment->output;
            $this->input['api_token'] = $this->getApiKey();
            $this->input['data']['errors'] = [];
            $this->input['data']['user_id'] = $this->getUserID();               // Retrieve authenticated User
            $this->input['data']['company_id'] = $this->getCompanyID($this->input['data']['company_code']);
            $this->input['data'] = fixShipmentCase($this->input['data']);       // Ensure all fields use correct case and Flags are boolean
        }
    }

    public function getInput()
    {
        if (Input::get('data') != null) {
            $msg = json_decode(Input::get('data'), true);
        } else {
            $msg = Input::all();
        }

        return $msg;
    }

    public function convertToJsonAndLog($data)
    {

        // Make sure using correct characterset
        $temp = convertToUTF8($data);

        // Decode json. Invalid json or characterset will produce a null string
        $jsonData = json_encode($temp);

        // If API TEST then do Not Log
        if (isset($data['options']) && is_array($data['options'])) {
            if (! in_array('APITEST', array_map('strtolower', $data['options']))) {
                return $jsonData;
            }
        }

        // Save Transaction
        $log = TransactionLog::create([
            'type' => 'API',
            'carrier' => '',
            'direction' => 'O',
            'msg' => $jsonData,
            'mode' => $this->mode,
        ]);

        return $jsonData;
    }

    /**
     * Get the api_token used whether in body
     * of data or in http headers.
     *
     * @param
     * @return
     */
    private function getApiKey()
    {
        $apiToken = '';

        // Check for api_key sent as Bearer in Header
        $data = Input::getFacadeRoot()->headers->get('authorization');
        if (substr($data, 0, 6) == 'Bearer') {
            $apiToken = trim(substr($data, 7));
        }

        return $apiToken;
    }

    /**
     * Checks user is authenticated and if so
     * returns the user id.
     *
     * @return string user id or "UnAuthorized"
     */
    private function getUserID()
    {

        // Try authenticating using Token
        $user = Auth::guard('api')->user();

        if (Auth::guard('api')->check()) {
            return $user->id;
        } else {
            return 'UnAuthorized';
        }
    }

    /**
     * Using the Authenticated user and the
     * supplied company_code fn checks user
     * is authenticated for the requested
     * company and if so returns the company id.
     *
     * @param array Shipment data
     * @return string company id or "UnAuthorized"
     */
    private function getCompanyID($companyCode)
    {

        // Try authenticating using Token
        $user = Auth::guard('api')->user();

        $companyId = 'UnAuthorized';
        if (Auth::guard('api')->check()) {
            if ($user->hasIfsRole()) {

                // Allow IFS employees to access all companies
                $company = Company::where('company_code', $companyCode)->first();
            } else {

                // Non IFS users to access only their own company
                $company = $user->companies->where('company_code', $companyCode)->first();
            }
            if (isset($company)) {
                $companyId = $company->id;
            }
        }

        return $companyId;
    }

    public function decodeDeleteShipment($ifsConsignmentNumber, $companyCode)
    {
        if ($ifsConsignmentNumber > '' && $companyCode > '') {

            //Get Users id and shipment details
            $userId = $this->getUserID();

            if ($this->mode == 'test') {
                $authorized = true;
                $company = Company::where('company_code', $companyCode)->first();
                if ($company) {
                    $this->input['api_token'] = $this->getApiKey();
                    $this->input['data']['errors'] = [];
                    $this->input['data']['user_id'] = $userId;                   // Retrieve authenticated User
                    $this->input['data']['company_id'] = $company->id;
                    $this->input['data']['ifs_consignment_number'] = '10004556445';

                    // Return Success
                    return;
                }
            } else {
                $shipment = Shipment::where('consignment_number', $ifsConsignmentNumber)->first();
                if ($shipment) {
                    $code = Company::find($shipment->company_id)->company_code;
                    if ($code == $companyCode) {
                        $authorized = true;
                        $this->input['api_token'] = $this->getApiKey();
                        $this->input['data']['errors'] = [];
                        $this->input['data']['user_id'] = $userId;                   // Retrieve authenticated User
                        $this->input['data']['company_id'] = $shipment->company_id;
                        $this->input['data']['ifs_consignment_number'] = $ifsConsignmentNumber;

                        // Return Success
                        return;
                    }
                }
            }
        }

        $this->returnUnauthorized('Invalid Credentials');
    }

    /**
     * Function to PreProcess the shipment details
     * It calculates any additional fields required.
     *
     * @param type $shipment
     * @return array $shipment
     */
    private function preProcessShipment($mode = 'create')
    {
        if ($mode == 'create') {

            // Get Company Setting
            $carrierChoice = strtolower(Company::find($this->input['data']['company_id'])->carrier_choice);

            // If no carrier defined or company not set to user - set to auto
            if ($carrierChoice == 'cost' || ! isset($this->input['data']['carrier_code']) || empty($this->input['data']['carrier_code'])) {
                $this->input['data']['carrier_choice'] = 'cost';
            }
        } else {
        }

        // Temporary Patch for Twinings
        if (in_array($this->input['data']['company_id'], ['608', '807'])) {
            $this->input['data']['sender_address3'] = 'Mallusk';
            $this->input['data']['sender_city'] = 'Newtownabbey';
            $this->input['data']['sender_county'] = 'Antrim';
        }

        // Shipment Depot
        $this->input['data']['depot_id'] = Company::find($this->input['data']['company_id'])->depot_id;

        // Set Department id
        $this->input['data']['department_id'] = 1;

        // Set Mode ID
        $this->input['data']['mode_id'] = 1;

        // Identify Carrier
        if ($mode == 'create' || empty($this->input['data']['carrier_code'])) {
            $this->chooseCarrier();
        }

        // Do Package level preProcessing
        $this->preProcessPackages();

        // Set Collection Date format if not supplied
        if (! isset($this->input['data']['date_format'])) {
            $this->input['data']['date_format'] = 'yyyy-mm-dd';
        }

        // Set Collection Date if not supplied or set to today
        if (! isset($this->input['data']['collection_date']) || empty($this->input['data']['collection_date']) || $this->input['data']['collection_date'] <= date('Y-m-d')) {
            $timeZone = Company::find($this->input['data']['company_id'])->localisation->time_zone;
            $pickUpTimes = new Postcode();
            $this->input['data']['collection_date'] = $pickUpTimes->getPickUpDate(
                $this->input['data']['sender_country_code'],
                $this->input['data']['sender_postcode'],
                $timeZone
            );
        }

        // If no errors Set Account details
        if ($this->input['data']['errors'] == []) {
            // Set accounts
            // $this->setShippingAcct();
            // $this->setDutyTaxAcct();
            // If hazard flag defined then overide hazard_code
            if (isset($this->input['data']['hazard_flag']) && $this->input['data']['hazard_flag'] > '') {
                switch ($this->input['data']['hazard_flag']) {
                    case 'Y':
                    case 'A':
                    case 'I':
                        if (isset($this->input['data']['hazard_class'])) {
                            $this->input['data']['hazard_code'] = $this->input['data']['hazard_class'];
                        }
                        break;

                    default:
                        $this->input['data']['hazard_code'] = $this->input['data']['hazard_flag'];
                        break;
                }
            }
        }

        // Set recipient name if not specified
        if (! isset($this->input['data']['recipient_name'])) {
            $this->input['data']['recipient_name'] = '';
        }

        // Set recipient company name if not specified
        if (! isset($this->input['data']['recipient_company_name'])) {
            $this->input['data']['recipient_company_name'] = '';
        }

        // Add any Alerts based on user preferences
        $preferences = json_decode(Auth::guard('api')->user()->getPreferences($this->input['data']['company_id'], '1'), true);
        if (! isset($this->input['data']['alerts']) || empty($this->input['data']['alerts'])) {

            // Get sender_email if not specified
            if (! isset($this->input['data']['sender_email']) || empty($this->input['data']['sender_email'])) {
                if (isset($preferences['sender_email']) && $preferences['sender_email'] > '') {
                    $this->input['data']['sender_email'] = $preferences['sender_email'];
                }
            }

            // Get other_email if not specified
            if (! isset($this->input['data']['other_email']) || empty($this->input['data']['other_email'])) {
                if (isset($preferences['other_email']) && $preferences['other_email'] > '') {
                    $this->input['data']['other_email'] = $preferences['other_email'];
                }
            }

            // If Sender Email defined, set alert to preference
            if (isset($this->input['data']['sender_email']) && $this->input['data']['sender_email'] > '') {
                $this->input['data']['alerts']['sender'] = $this->extractAlertPreferences('sender', $preferences);
            }

            // If Recipient Email defined, set alert to preference
            if (isset($this->input['data']['recipient_email']) && $this->input['data']['recipient_email'] > '') {
                $this->input['data']['alerts']['recipient'] = $this->extractAlertPreferences('recipient', $preferences);
            }

            // If Broker Email defined, set alert to preference
            if (isset($this->input['data']['broker_email']) && $this->input['data']['broker_email'] > '') {
                $this->input['data']['alerts']['broker'] = $this->extractAlertPreferences('broker', $preferences);
            }

            // If Other Email defined, set alert to preference
            if (isset($this->input['data']['other_email']) && $this->input['data']['other_email'] > '') {
                $this->input['data']['alerts']['other'] = $this->extractAlertPreferences('other', $preferences);
            }
        }
    }

    /**
     * Identifies the correct carrier\ service to use for the
     * Shipment held in $this->input.
     * @param array $possibleCarriers
     * @return string carrierCode
     */
    public function chooseCarrier()
    {

        // Set the Companies method of choosing Carriers
        $this->setCarrierChoiceMethod();

        // Get a list of all possible carrier/ services
        $possibleCarriers = CarrierAPI::getAvailableServices($this->input['data'], $this->mode);

        $this->input['data']['service_id'] = '';

        // Action determined by the number of responses
        $numberOfCarriers = count($possibleCarriers);
        switch ($numberOfCarriers) {

            case '0':
                // No Carrier\ Services found so throw error
                $this->input['data']['errors'][] = 'No Carrier Available';
                break;

            case '1':

                // One possible Carrier Service so use it
                $key = key($possibleCarriers);
                $this->input['data']['carrier_code'] = $possibleCarriers[$key]['carrier_code'];
                $this->input['data']['carrier_id'] = $possibleCarriers[$key]['carrier_id'];
                $this->input['data']['service_id'] = $possibleCarriers[$key]['id'];
                break;

            default:

                // More than one returned. Need to decide which to use, or fail
                $this->chooseCarrierService($possibleCarriers, $this->input['data']['carrier_choice']);
                break;
        }
    }

    private function setCarrierChoiceMethod()
    {

        // If company_id defined then check Customers setting for choosing carrier
        if (isset($this->input['data']['company_id']) && $this->input['data']['company_id'] > 0) {

            // If not set identify customers default setting
            if (! isset($this->input['data']['carrier_choice']) || empty($this->input['data']['carrier_choice'])) {
                $company = Company::find($this->input['data']['company_id']);
                if ($company) {
                    $this->input['data']['carrier_choice'] = $company->carrier_choice;
                } else {
                    $this->input['data']['carrier_choice'] = 'cost';
                }
            }
        }
    }

    private function chooseCarrierService($possibleCarriers, $carrierChoice)
    {
        $notMatched = true;
        $services = [];
        $defaultService = '';
        $thisValue = 0;

        foreach ($possibleCarriers as $possibleCarrier) {

            // Identify what services have been offered
            if (! in_array($possibleCarrier['code'], $services)) {
                $services[] = $possibleCarrier['code'];
            }

            // If choice is based on Cost to IFS
            if ($carrierChoice == 'cost') {
                $thisValue = $possibleCarrier['cost'];
            }

            // If choice is based on Cost to Customer
            if ($carrierChoice == 'price') {
                $thisValue = $possibleCarrier['price'];
            }

            // If first service or this is the cheapest provisionally choose this Carrier\ Service
            if (empty($defaultService) || $thisValue < $cheapestValue) {
                $defaultService = $possibleCarrier['code'];
                $cheapestValue = $thisValue;
            }
        }

        // If User has not specified a service then use the default service just calculated
        if (! isset($this->input['data']['service_code']) || empty($this->input['data']['service_code'])) {
            if ($carrierChoice != 'user') {
                $this->input['data']['service_code'] = $defaultService;
            }
        }

        foreach ($possibleCarriers as $possibleCarrier) {

            // Find the first Service to match shipment then break out of loop
            if (strcasecmp($possibleCarrier['code'], $this->input['data']['service_code']) == 0) {
                $this->input['data']['carrier_code'] = Carrier::find($possibleCarrier['carrier_id'])->code;
                $this->input['data']['carrier_id'] = $possibleCarrier['carrier_id'];
                $this->input['data']['service_id'] = $possibleCarrier['id'];
                $notMatched = false;
                break;
            }
        }

        if ($notMatched) {
            $this->input['data']['errors'][] = 'Please select service : '.strtoupper(implode(',', $services));
        }
    }

    /**
     * Do Package level preprocessing eg
     * Check for Dry Ice, add dims/ weight
     * for Customer defined packaging and
     * determine the largest dimension.
     */
    private function preProcessPackages()
    {

        // Do Package level checks
        $cnt = 0;
        $dryIceTotalWeight = 0;
        $this->input['data']['dry_ice_flag'] = false;
        $cnt = count($this->input['data']['packages']);

        for ($i = 0; $i < $cnt; $i++) {
            $this->input['data']['packages'][$i]['index'] = $i + 1;

            // Check if Customers defined packaging
            $pkg = CompanyPackagingType::where('code', $this->input['data']['packages'][$i]['packaging_code'])
                ->where('company_id', $this->input['data']['company_id'])->first();

            if ($pkg) {
                // if no length supplied but defined for package use definition
                if (! isset($this->input['data']['packages'][$i]['length']) || $this->input['data']['packages'][$i]['length'] == 0) {
                    $this->input['data']['packages'][$i]['length'] = $pkg->length;
                }
                // if no width supplied but defined for package use definition
                if (! isset($this->input['data']['packages'][$i]['width']) || $this->input['data']['packages'][$i]['width'] == 0) {
                    $this->input['data']['packages'][$i]['width'] = $pkg->width;
                }
                // if no height supplied but defined for package use definition
                if (! isset($this->input['data']['packages'][$i]['height']) || $this->input['data']['packages'][$i]['height'] == 0) {
                    $this->input['data']['packages'][$i]['height'] = $pkg->height;
                }
                // if no weight supplied but defined for package use definition
                if (! isset($this->input['data']['packages'][$i]['weight']) || $this->input['data']['packages'][$i]['weight'] == 0) {
                    $this->input['data']['packages'][$i]['weight'] = $pkg->weight;
                }
            }
        }
    }

    private function extractAlertPreferences($preference, $preferences)
    {
        $prefs = [];
        if (isset($preferences['alerts.'.$preference.'.despatched'])) {
            $prefs['despatched'] = $preferences['alerts.'.$preference.'.despatched'];
        }

        if (isset($preferences['alerts.'.$preference.'.out_for_delivery'])) {
            $prefs['out_for_delivery'] = $preferences['alerts.'.$preference.'.out_for_delivery'];
        }

        if (isset($preferences['alerts.'.$preference.'.delivered'])) {
            $prefs['delivered'] = $preferences['alerts.'.$preference.'.delivered'];
        }

        if (isset($preferences['alerts.'.$preference.'.cancelled'])) {
            $prefs['cancelled'] = $preferences['alerts.'.$preference.'.cancelled'];
        }

        if (isset($preferences['alerts.'.$preference.'.problems'])) {
            $prefs['problems'] = $preferences['alerts.'.$preference.'.problems'];
        }

        return $prefs;
    }

    public function checkIfApitest()
    {
        if (isset($this->input['data']['options'])) {

            // If Array check APITEST is one of its values
            if (is_array($this->input['data']['options'])) {

                // Convert everything to uppercase and return true if APITEST
                $search_array = array_map('strtoupper', $this->input['data']['options']);
                if (in_array('APITEST', $search_array)) {
                    return true;
                }
            } else {

                // If a string check to see if it matches
                if (strtoupper($this->input['data']['options']) == 'APITEST') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Accepts a DELETE object, validates it
     * and deletes the requested shipment.
     *
     * @param string Shipment Token
     * @param string Company Token
     *
     * @return Response
     */
    public function deleteShipment($version, $ifsConsignmentNumber = '', $companyCode = '')
    {

        // Decode data and insert user_id of Authenticated User etc.
        $this->decodeInput('deleteShipment', $version, $ifsConsignmentNumber, $companyCode);

        if ($this->input['data']['user_id'] == 'UnAuthorized' || $this->input['data']['company_id'] == 'UnAuthorized') {

            // Authentication Failed
            $response = APIResponse::respondUnauthorized();
        } else {
            if ($this->mode == 'test') {
                $reply = [
                    'errors' => '',
                    'consignment_number' => $ifsConsignmentNumber,
                    'carrier' => 'IFS',
                ];

                $response = APIResponse::respondDeletedShipment($reply, $version); // Reformat response for API user
            } else {

                // Check User is authorized to cancel this shipment
                $isAuthorized = false;
                $shipment = Shipment::where('consignment_number', $ifsConsignmentNumber)->first();
                if ($shipment) {

                    // Shipment exists but is the user a member of the company
                    $isAuthorized = Company::find($shipment->company_id)->hasUser($this->input['data']['user_id']);
                }
                if ($isAuthorized) {
                    $this->input['data']['shipment_token'] = $shipment->token;
                    $reply = CarrierAPI::deleteShipment($this->input['data'], $this->mode);

                    $reply['consignment_number'] = $ifsConsignmentNumber;
                    if (isset($reply['carrier_code'])) {
                        $reply['carrier'] = $reply['carrier_code'];
                        unset($reply['carrier_code']);
                    } else {
                        $reply['carrier'] = '';
                    }
                    $response = APIResponse::respondDeletedShipment($reply, $version); // Reformat response for API user
                } else {
                    $response = APIResponse::respondNotFound();                     // Reformat response for API user
                }
            }
        }

        return $response;
    }

    public function labelTest()
    {
        $awbs = ['654896508720'];
        $labelLoc = 'http://192.168.10.34/';

        $pdf = new \TCPDI();

        // remove the default head/footers
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setImageScale(1);

        $labelNumber = 0;
        foreach ($awbs as $awb) {
            $labelNumber++;
            if ($labelNumber == 1 && $this->checkRemoteFile($labelLoc.$awb.'.PNG')) {

                // If Master Label exists add it.
                $pdf->AddPage('P', [102.00, 153.00]);
                $pdf->Image($labelLoc.$awb.'.PNG', 0, 0, 102, 153);
            }

            // Add Package Label
            $pdf->AddPage('P', [102.00, 153.00]);
            $pdf->Image($labelLoc.$awb.'AWB.PNG', 0, 0, 102, 153);
        }

        header('Content-type: application/pdf');
        echo $pdf->Output($awbs[0].'.PDF', 'S');
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

    public function printLabel($id)
    {
        $json = TransactionLog::find($id)->msg;
        $data = json_decode($json, true);

        $upsLabel = new \App\CarrierAPI\UPS\UPSLabel('', $data);
        $response = $upsLabel->create();

        return response(base64_decode($response))->header('Content-Type', 'application/pdf');
    }

    /**
     * Receive shipment as JSON and price it
     * Returning Pricing info as JSON.
     *
     * @param type $version
     * @return type
     */
    public function priceShipment($version)
    {

        // Decode data and insert user_id of Authenticated User
        $this->decodeInput('priceShipment', $version);

        if ($this->input['data']['user_id'] == 'UnAuthorized' || $this->input['data']['company_id'] == 'UnAuthorized') {
            // Authentication Failed
            return APIResponse::respondUnauthorized();
        }

        if ($this->input['data']['user_id'] == 'NotAvailable') {
            // Mode not available
            return APIResponse::respondNotAvailable();
        }

        // Authenticated so do initial processing
        $this->preProcessShipment();

        if ($this->input['data']['errors'] == []) {
            if (isset($this->input['data']['carrier_id']) && $this->input['data']['carrier_id'] > '') {
                if ($shipment['bill_shipping'] == 'recipient' && $shipment['bill_tax_duty'] == 'recipient') {

                    // Recipient pays freight so no costs/ sales
                    $response['errors'] = [];
                    $response['zone'] = '';
                    $response['model'] = '';
                    $response['rate_id'] = '';
                    $response['packaging'] = '';
                    $response['charges'] = [];
                } else {

                    // Shipper/ Other pays Freight so try to Price shipment
                    $reply = Pricing::price($this->input['data'], $this->input['data']['service_id']);
                    if ($reply['errors'] == []) {
                        if (isset($reply['carrier_code'])) {
                            $reply['carrier'] = $reply['carrier_code'];
                            unset($reply['carrier_code']);
                        }
                    }
                }

                $response = APIResponse::respondPricedShipment($reply, $version);        // Reformat response for API user
            } else {
                $response = APIResponse::respondNoCarrierAvailable();
            }
        } else {
            $response = APIResponse::respondInvalid($this->input['data']);      // Errors in data
        }

        return $response;
    }

    public function reprice(Shipment $shipment)
    {
        if ($shipment) {
            $packages = [];
            foreach ($shipment->packages as $package) {
                $packages[] = $package->toArray();
            }

            // Build Shipment array for repricing
            $shipmentArray = $shipment->toArray();
            $shipmentArray['packages'] = $packages;

            // Reprice Shipment with new dims etc.
            $price = Pricing::price($shipmentArray, $shipmentArray['service_id']);
            $shipment->quoted = json_encode($price);
            $shipment->shipping_charge = $price['shipping_charge'];
            $shipment->shipping_cost = $price['shipping_cost'];
            $shipment->cost_currency = $price['cost_currency'];
            $shipment->sales_currency = $price['sales_currency'];

            $shipment->save();
        }
    }

    public function test1()
    {
        $quotedRate['min_discount'] = 0;
        $quotedRate['max_discount'] = 0;
        $test = ! ($quotedRate['min_discount'] == 0 && $quotedRate['max_discount'] == 0);

        dd($test);
    }

    /**
     * Function accepts a Carrier code
     * and returns the Carrier object.
     *
     * @param string Carrier Code
     * @return Carrier
     */
    private function getCarrier($carrierCode)
    {
        $carrierCode = Carrier::where('code', $carrierCode)->first();

        return $carrierCode;
    }

    /**
     * Formats data to be returned to the user
     * in the correct format.
     *
     * @param array $data
     * @return Response
     */
    private function formatResponse($data, $format)
    {
        switch ($format) {
            case 'JSON':
                $response = json_encode($data);
                break;
            default:
                break;
        }

        return $response;
    }

    /**
     * Returns the user/company details for a valid api_token/company_code.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function validateUser(Request $request)
    {
        $this->validate($request, ['company_code' => 'required|size:6']);

        $company = Company::where('company_code', $request->get('company_code'))->where('enabled', 1)->first();

        if ($company) {

            // Ensure user has permissions to this company record
            $this->authorize('view', $company);

            $user = $request->user();

            return response()->json([
                'name' => $user->name,
                'company_name' => $company->company_name,
                'address1' => $company->address1,
                'address2' => $company->address2,
                'address3' => $company->address3,
                'city' => $company->city,
                'state' => $company->state,
                'postcode' => $company->postcode,
                'country_code' => $company->country_code,
                'email' => $user->email,
                'telephone' => $user->telephone,
            ]);
        }

        return response()->json([
            'error' => 'Unauthenticated.',
        ], 401);
    }

    /**
     * Cancel shipment endpoint using order number (required for linnworks).
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function cancelShipment(Request $request)
    {
        $this->validate($request, ['company_code' => 'required|size:6', 'order_number' => 'required']);

        $company = Company::where('company_code', $request->company_code)->first();

        $shipment = Shipment::where('order_number', $request->order_number)->where('company_id', $company->id)->orderBy('id', 'desc')->first();

        if ($shipment) {
            // Ensure user has permissions to this shipment
            $this->authorize('cancel', $shipment);

            $shipment->setCancelled($request->user()->id);

            return response()->json([
                'message' => 'Shipment '.$shipment->consignment_number.' cancelled',
            ], 200);
        }

        return response()->json([
            'error' => 'Unable to cancel shipment.',
        ], 422);
    }

    /**
     * Get shipment labels as base64 PNGs (required for linnworks).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function labelPng(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        $shipment = Shipment::whereToken($request->token)->first();

        if ($shipment) {
            return response()->json($shipment->getPngLabels(), 200);
        }

        return response()->json([
            'error' => 'Labels unavailable.',
        ], 422);
    }
}
