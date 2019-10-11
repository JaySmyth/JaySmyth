<?php

namespace App\CarrierAPI\DHL;

use App\Carrier;
use App\CarrierAPI\CarrierBase;
use App\Company;
use App\Country;
use App\PackagingType;
use App\Service;
use App\TransactionLog;
use Carbon\Carbon;
use DHL\Client\Web as WebserviceClient;
use DHL\Datatype\GB\Piece;
use DHL\Datatype\GB\SpecialService;
use DHL\Entity\GB\ShipmentRequest;
use DHL\Entity\GB\ShipmentResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Description of DHLWebAPI
 *
 * @author gmcbroom
 */
class DHLAPI extends CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    public $mode;
    private $account;
    private $client;

    public function createShipment($shipment)
    {
        $response = [];
        $shipment = $this->preProcess($shipment);

        $errors = $this->validateShipment($shipment);

        if (empty($errors)) {

            // DHL settings
            $this->initCarrier($shipment);
            $dhlShipment = $this->BuildDHLShipment($shipment);
            $reply = $this->sendMessageToCarrier($dhlShipment, 'create_shipment');

            // Check for errors
            if (isset($reply['Response']['Status']['Condition']['ConditionCode']) && $reply['Response']['Status']['Condition']['ConditionCode'] > '') {

                if ($reply['Response']['Status']['Condition']['ConditionCode'] == 'SV011a') {
                    $errorMsg = 'Area not covered by carrier. Please contact Courier department.';
                } else {
                    $errorMsg = 'Carrier Error : ' . ((string)$reply['Response']['Status']['Condition']['ConditionCode']) . ' : ' . str_replace(chr(10), ' - ', (string)$reply['Response']['Status']['Condition']['ConditionData']);
                }

                // Replace all references to DHL with IFS
                $errorMsg = str_replace("DHL", "IFS", $errorMsg);

                return $this->generateErrorResponse($response, $errorMsg);
            } else {

                // Request successful -  Calc Routing
                $route_id = $this->calc_routing($shipment);

                //                      Prepare Response
                $response = $this->createShipmentResponse($reply, $shipment['service_code'], $route_id);
                return $response;
            }
        } else {
            return $this->generateErrorResponse($response, $errors);
        }
    }

    public function preProcess($shipment)
    {
        if (!isset($shipment['recipient_company_name']) || $shipment['recipient_company_name'] == '') {
            $shipment['recipient_company_name'] = $shipment['recipient_name'];
            $shipment['recipient_name'] = '.';
        }

        if ($shipment['bill_shipping_account'] == '') {
            $shipment['bill_shipping_account'] = Service::find($shipment['service_id'])->account;
        }
        if ($shipment['bill_tax_duty_account'] == '') {
            $shipment['bill_tax_duty_account'] = Service::find($shipment['service_id'])->account;
        }
        return $shipment;
    }

    public function validateShipment($shipment)
    {
        /**
         * Don't allow residential shipments to russia
         */
        $v = Validator::make($shipment, [
            'recipient_type' => 'required',
            'recipient_country_code' => 'required|not_in:IR,KP,CU'
        ], [
            'recipient_type.not_supported' => 'Residential address not supported',
            'recipient_country_code.not_in' => 'Recipient country not supported. Please contact Courier department',
        ]);

        $v->sometimes('recipient_type', 'not_supported', function ($input) {
            return (strtolower($input->recipient_type) == 'r' && strtolower($input->recipient_country_code) == 'ru');
        });

        if ($v->fails()) {
            return $this->buildValidationErrors($v->errors());
        }

        /**
         * Standard validation resumes
         */
        $rules['bill_shipping_account'] = 'required|digits:9';
        $rules['bill_tax_duty_account'] = 'required|digits:9';
        $rules['insurance_value'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        // Validate Shipment using the rules
        $errors = $this->applyRules($rules, $shipment);

        return $errors;
    }

    function initCarrier()
    {
        /*
         * *****************************************
         * Define fields for Production/ Development
         * *****************************************
         */

        // DHL webservice client using the staging environment
        if (strtoupper($this->mode) == 'TEST') {
            $this->client = new WebserviceClient('staging');
        } else {
            $this->client = new WebserviceClient('production');
        }


        /*
         * *****************************************
         * Define Carrier Specific field values
         *
         * i.e. IFS => Carrier conversion
         * *****************************************
         */

        $this->account = [
            'test' => [
                // ID to use to connect to DHL
                'id' => 'v62_HxvnFkM8EK',
                // Password to use to connect to DHL
                'pass' => 'fZVp5yrGJB',
                // Shipper, Billing and Duty Account numbers
                'shipperAccountNumber' => '418289240',
                'billingAccountNumber' => '418289240',
                'dutyAccountNumber' => '418289240'
            ],
            'production' => [
                // ID to use to connect to DHL
                'id' => 'xmlIFSGlobal',
                // Password to use to connect to DHL
                'pass' => 'awpLTskvh0',
                // Shipper, Billing and Duty Account numbers
                'shipperAccountNumber' => '418289240',
                'billingAccountNumber' => '418289240',
                'dutyAccountNumber' => '418289240'
            ]
        ];

        // Define regions
        $this->ap = "AE,AF,AL,AM,AO,AU,BA,BD,BH,BN,BY,CI,CM,CN,CY,DJ,DZ,EG,ET,FJ,GA,GH,HK,HR,ID,IL,IN,IR,JO,JP,KE,KR,KW,KZ,LA,";
        $this->ap .= "LB,LK,MA,MD,MG,MK,MO,MT,MU,MY,NA,NG,NZ,OM,PH,PK,QA,RE,RS,RU,SA,SD,SG,SN,TH,TR,TW,TZ,UA,UG,UZ,VN,YE,ZA";
        $this->eu = "AT,BE,BG,CH,CZ,DE,DK,EE,ES,FI,FR,GB,GR,HU,IE,IS,IT,LT,LU,LV,NL,NO,PL,PT,RO,SE,SI,SK";
        $this->am = "AI,AR,AW,BB,BM,BO,BR,BS,CA,CL,CO,CR,DM,DO,EC,GP,GT,GU,GY,HN,HT,JM,KN,KY,LC,MQ,MX,NI,PA,PE,PR,PY,SV,TC,TT,US,UY,VE,VG,VI,XM,XY";
        $this->paymentTypes = ['sender' => 'S', 'recipient' => 'R', 'other' => 'O'];

        // Define Weight Units
        $this->weightUnits = ['kg' => 'K', 'lb' => 'L'];        // Get my Carrier Details
        $this->carrier = Carrier::where('code', 'dhl')->first();

        // Define DimensionUnit Units
        $this->dimensionUnits = ['CM' => 'C', 'IN' => 'I'];

        // Define available services
        $services = $this->carrier->services;
        foreach ($services as $svc) {
            $this->svc[$svc->code] = json_decode($svc->carrier_code, true);
        }

        // Define Package types supported
        $packages = Carrier::find($this->carrier->id)->packagingTypes;
        foreach ($packages as $package) {
            $ptype = strtoupper(PackagingType::find($package->packaging_type_id)->code);
            $this->packageTypes[$ptype] = strtoupper($package->code);
        }
    }

    public function buildDHLShipment($shipment)
    {
        // Test a ShipmentRequest using DHL XML API
        $dhlShipment = new ShipmentRequest();

        // Assuming there is a config array variable with id and pass to DHL XML Service
        $dhlShipment->SiteID = $this->account[$this->mode]['id'];
        $dhlShipment->Password = $this->account[$this->mode]['pass'];

        // Set values of the request
        $dhlShipment->MessageTime = Carbon::now()->toRfc3339String();
        $dhlShipment->MessageReference = '1234567890123456789012345678901';
        $dhlShipment->RegionCode = $this->getRegioncode($shipment);
        $dhlShipment->RequestedPickupTime = 'N';
        $dhlShipment->NewShipper = 'N';
        $dhlShipment->LanguageCode = 'en';
        $dhlShipment->PiecesEnabled = 'Y';
        $dhlShipment->Billing->ShippingPaymentType = $this->paymentTypes[$shipment['bill_shipping']];
        $dhlShipment->Billing->ShipperAccountNumber = $shipment['bill_shipping_account'];
        $dhlShipment->Billing->BillingAccountNumber = $shipment['bill_shipping_account'];

        // Shipper Details
        $dhlShipment->Shipper->ShipperID = '*********';
        $dhlShipment->Shipper->CompanyName = $shipment['sender_company_name'];
        // $sample->Shipper->RegisteredAccount = '751008818';
        $dhlShipment->Shipper->addAddressLine($shipment['sender_address1']);
        $dhlShipment->Shipper->addAddressLine($shipment['sender_address2']);
        $dhlShipment->Shipper->addAddressLine($shipment['sender_address3']);
        $dhlShipment->Shipper->City = $shipment['sender_city'];
        $dhlShipment->Shipper->Division = $shipment['sender_state'];
        // $dhlShipment->Shipper->DivisionCode = $shipment['sender_state'];
        $dhlShipment->Shipper->PostalCode = $shipment['sender_postcode'];
        $dhlShipment->Shipper->CountryCode = $shipment['sender_country_code'];
        $dhlShipment->Shipper->CountryName = Country::where('country_code', $shipment['sender_country_code'])->first()->country;
        $dhlShipment->Shipper->Contact->PersonName = $shipment['sender_name'];
        $dhlShipment->Shipper->Contact->PhoneNumber = $shipment['sender_telephone'];
        // $sample->Shipper->Contact->PhoneExtension = '3403';
        // $sample->Shipper->Contact->FaxNumber = '1 905 8613411';
        // $sample->Shipper->Contact->Telex = '1245';
        $dhlShipment->Shipper->Contact->Email = $shipment['sender_email'];

        // Recipient Details
        $dhlShipment->Consignee->CompanyName = $shipment['recipient_company_name'];
        $dhlShipment->Consignee->addAddressLine($shipment['recipient_address1']);
        $dhlShipment->Consignee->addAddressLine($shipment['recipient_address2']);
        $dhlShipment->Consignee->addAddressLine($shipment['recipient_address3']);
        $dhlShipment->Consignee->City = $shipment['recipient_city'];
        $dhlShipment->Consignee->Division = $shipment['recipient_state'];
        $dhlShipment->Consignee->PostalCode = $shipment['recipient_postcode'];
        $dhlShipment->Consignee->CountryCode = $shipment['recipient_country_code'];
        $dhlShipment->Consignee->CountryName = Country::where('country_code', $shipment['recipient_country_code'])->first()->country;
        $dhlShipment->Consignee->Contact->PersonName = $shipment['recipient_name'];
        $dhlShipment->Consignee->Contact->PhoneNumber = $shipment['recipient_telephone'];
        // $sample->Consignee->Contact->PhoneExtension = '123';
        // $sample->Consignee->Contact->FaxNumber = '506-851-7403';
        // $sample->Consignee->Contact->Telex = '506-851-7121';
        if (isset($shipment['recipient_email'])) {
            $dhlShipment->Consignee->Contact->Email = $shipment['recipient_email'];
        } else {
            $dhlShipment->Consignee->Contact->Email = '';
        }

        if (customsEntryRequired($shipment['sender_country_code'], $shipment['recipient_country_code'])) {

            if ($shipment['bill_tax_duty'] == 'sender') {
                $dhlShipment->Billing->DutyPaymentType = $this->paymentTypes[$shipment['bill_tax_duty']];
                $dhlShipment->Billing->DutyAccountNumber = $shipment['bill_tax_duty_account'];
                $specialService = new SpecialService();
                $specialService->SpecialServiceType = 'DD';
                $dhlShipment->addSpecialService($specialService);
            }

            if (!empty($shipment['contents'])) {
                foreach ($shipment['contents'] as $content) {

                    if ($content['commodity_code'] > "") {
                        $dhlShipment->Commodity->CommodityCode = $content['commodity_code'];
                    } else {
                        // If not defined use cc
                        $dhlShipment->Commodity->CommodityCode = 'cc';
                    }
                    if ($content['description'] > "") {
                        $dhlShipment->Commodity->CommodityName = substr(trim($content['description']), 0, 35);
                    } else {
                        // If not defined use 'cn'
                        $dhlShipment->Commodity->CommodityName = 'cn';
                    }
                }
            }
        }

        // $sample->Dutiable->ScheduleB = '3002905110';
        // $sample->Dutiable->ExportLicense = 'D123456';
        // $sample->Dutiable->ShipperEIN = '112233445566';
        // $sample->Dutiable->ShipperIDType = 'S';
        // $sample->Dutiable->ImportLicense = 'ALFAL';
        // $sample->Dutiable->ConsigneeEIN = 'ConEIN2123';
        // $sample->Reference->ReferenceType = 'St';
        // Add Package Details
        $dhlShipment->Reference->ReferenceID = $shipment['shipment_reference'];
        $dhlShipment->ShipmentDetails->NumberOfPieces = $shipment['pieces'];

        $pkg = 0;
        $dhlShipment->ShipmentDetails->PackageType = "EE";
        foreach ($shipment['packages'] as $package) {
            $pkg++;
            $piece = new Piece();
            $piece->PieceID = $pkg;
            $piece->PackageType = $this->packageTypes[strtoupper($package['packaging_code'])];
            $piece->Weight = $package['weight'];
            $piece->DimWeight = (string)round($package['volumetric_weight'], 2);
            $piece->Width = $package['width'];
            $piece->Height = $package['height'];
            $piece->Depth = $package['length'];

            // For shipment to be a document shipment all packages must be documents
            if ($dhlShipment->ShipmentDetails->PackageType == "EE" and $piece->PackageType <> "EE") {
                $dhlShipment->ShipmentDetails->PackageType = $piece->PackageType;
            }
            $dhlShipment->ShipmentDetails->addPiece($piece);
        }

        // Set description
        if (isset($shipment['goods_description']) && $shipment['goods_description'] > "") {
            $dhlShipment->ShipmentDetails->Contents = $shipment['goods_description'];
        } else {
            $dhlShipment->ShipmentDetails->Contents = $shipment['documents_description'];
        }

        // Get Service Product codes
        $productCodes = explode(',', Service::find($shipment['service_id'])->parameters);

        $dhlShipment->ShipmentDetails->Weight = $shipment['weight'];
        $dhlShipment->ShipmentDetails->WeightUnit = $this->weightUnits[$shipment['weight_uom']];
        $dhlShipment->ShipmentDetails->GlobalProductCode = $productCodes[0];
        $dhlShipment->ShipmentDetails->LocalProductCode = $productCodes[1];
        $dhlShipment->ShipmentDetails->Date = $shipment['collection_date'];

        $dhlShipment->ShipmentDetails->DoorTo = 'DD';
        $dhlShipment->ShipmentDetails->DimensionUnit = $this->dimensionUnits[strtoupper($shipment['dims_uom'])];
        // $sample->ShipmentDetails->InsuredAmount = $shipment['insurance_value'];
        $dhlShipment->ShipmentDetails->PackageType = $this->packageTypes[$shipment['packages'][0]['packaging_code']];
        $dhlShipment->ShipmentDetails->CurrencyCode = $shipment['customs_value_currency_code'];


        $dhlShipment->Dutiable->DeclaredValue = number_format($shipment['customs_value'], 2, '.', '');
        $dhlShipment->Dutiable->DeclaredCurrency = $shipment['customs_value_currency_code'];

        // Is this a document shipment?
        if ($dhlShipment->ShipmentDetails->PackageType == "EE") {
            $dhlShipment->ShipmentDetails->IsDutiable = 'N';
        } else {

            $dhlShipment->ShipmentDetails->IsDutiable = 'Y';
            $dhlShipment->Dutiable->TermsOfTrade = strtoupper($shipment['terms_of_sale']);
            $dhlShipment->Dutiable->ShipperEIN = Company::find($shipment['company_id'])->eori;

            if ($this->pltIsAvailable($shipment['recipient_country_code'], $shipment['customs_value'], $shipment['customs_value_currency_code'])) {

                $dhlShipment->UseDHLInvoice = 'Y';
                $dhlShipment->DHLInvoiceLanguageCode = 'en';
                $dhlShipment->DHLInvoiceType = (isset($shipment['invoice_type']) && strtoupper($shipment['invoice_type']) == 'P') ? 'PI' : 'CMI';
                $dhlShipment->RequestArchiveDoc == 'N';

                $specialService = new SpecialService();
                $specialService->SpecialServiceType = 'WY';
                $dhlShipment->addSpecialService($specialService);

            } else {
                // Notify courier department that manual invoice is required
            }

        }


        /*
         *  Special Service Flags
         */
        // Dry Ice
        if (isset($shipment['dry_ice_flag']) && $shipment['dry_ice_flag']) {
            $specialService = new SpecialService();
            $specialService->SpecialServiceType = 'HC';
            $dhlShipment->addSpecialService($specialService);
        }

        // Dangerous Goods
        if (isset($shipment['hazardous'])) {

            switch (strtoupper($shipment['hazardous'])) {
                case 'Y':
                case 'A':
                    $specialService = new SpecialService();
                    $specialService->SpecialServiceType = 'HE';
                    $dhlShipment->addSpecialService($specialService);
                    break;

                case 'E':
                    $specialService = new SpecialService();
                    $specialService->SpecialServiceType = 'HH';
                    $dhlShipment->addSpecialService($specialService);
                    break;

                default:
                    break;
            }
        }

        // Residential Delivery
        if (isset($shipment['recipient_type']) && strtoupper($shipment['recipient_type'] == 'R')) {
            $specialService = new SpecialService();
            $specialService->SpecialServiceType = 'TK';
            $dhlShipment->addSpecialService($specialService);
        }

        $dhlShipment->EProcShip = 'N';
        $dhlShipment->LabelImageFormat = 'PDF';
        $dhlShipment->Label->LabelTemplate = '6X4_A4_PDF';

        return $dhlShipment;
    }

    public function getRegioncode($shipment)
    {

        if (strpos($this->ap, $shipment['sender_country_code']) !== false) {
            return 'AP';
        } else {
            if (strpos($this->eu, $shipment['sender_country_code']) !== false) {
                return 'EU';
            } elseif (strpos($this->am, $shipment['sender_country_code']) !== false) {
                return 'AM';
            }
        }
    }

    /**
     * Check PLT availability.
     *
     * @return bool
     */
    private function pltIsAvailable($recipientCountryCode, $customsValue, $currency)
    {
        return false;

        $countriesNotSupportingPlt = ['PH', 'VN', 'BD', 'PK', 'ID', 'IN', 'CR', 'GT', 'CL', 'HN', 'BR', 'NI', 'PE', 'SV', 'AM', 'AZ', 'BY', 'GE', 'KZ', 'KG', 'MD', 'RU', 'TJ', 'UA', 'UZ', 'EG', 'KW', 'YE', 'QA', 'LB', 'IQ', 'IR', 'SY', 'MA', 'TN', 'AF', 'DZ', 'LY'];

        if (in_array(strtoupper($recipientCountryCode), $countriesNotSupportingPlt)) {
            return false;
        }

        $countriesSupportingPltWithLimit = ['RS' => ['limit' => 50, 'currency_code' => 'EUR'], 'SA' => ['limit' => 200, 'currency_code' => 'USD'], 'BH' => ['limit' => 1300, 'currency_code' => 'USD'], 'OM' => ['limit' => 2590, 'currency_code' => 'USD'], 'JO' => ['limit' => 1000, 'currency_code' => 'USD'], 'AE' => ['limit' => 13623, 'currency_code' => 'USD'],];

        if (array_key_exists($recipientCountryCode, $countriesSupportingPltWithLimit)) {
            $limit = $countriesSupportingPltWithLimit[$recipientCountryCode]['limit'];
            if ($customsValue > $limit) {
                return false;
            }
        }

        return true;
    }

    private function sendMessageToCarrier($dhlShipment)
    {

        $response = [];
        $msgType = 'MSG';

        // Call the DHL service and display the XML result
        $sentXML = $dhlShipment->toXML();

        // dd($sentXML);

        $sentXML = str_replace('9.880000000000001', '9.88', $sentXML);

        $dhlResponse = new ShipmentResponse();

        // Log Message to be sent
        TransactionLog::create([
            'carrier' => 'dhl', 'type' => 'MSG', 'direction' => 'O', 'msg' => 'v1 - ' . $sentXML, 'mode' => $this->mode
        ]);

        // Send Message to DHL
        $receivedXML = $this->client->call($dhlShipment);

        // Log Response
        TransactionLog::create([
            'carrier' => 'dhl', 'type' => $msgType, 'direction' => 'I', 'msg' => $receivedXML, 'mode' => $this->mode
        ]);

        // Request succesful - Return Response as an array
        $route_id = 1;

        // XML -> object -> JSON
        $json = json_encode(simplexml_load_string(convertToUTF8($receivedXML)));

        $response = json_decode($json, true);

        return $response;
    }

    private function calc_routing($shipment)
    {

        return 1;
    }

    private function createShipmentResponse($reply, $serviceCode, $route_id, $imageType = 'PDF', $labelSize = '6X4')
    {
        $response = $this->generateSuccess();

        // Add additional data to be returned
        $volWeight = 0;

        $response['route_id'] = $route_id;
        $response['carrier'] = 'dhl';
        $response['ifs_consignment_number'] = nextAvailable('CONSIGNMENT');
        $response['consignment_number'] = $reply['AirwayBillNumber'];
        $response['volumetric_divisor'] = getVolumetricDivisor('DHL', $serviceCode);       // From Helper functions

        if ($reply['Piece'] > 1) {

            // Multiple Pieces
            $response['pieces'] = $reply['Piece'];
            for ($i = 0; $i < $response['pieces']; ++$i) {
                $response['packages'][$i]['sequence_number'] = $reply['Pieces']['Piece'][$i]['PieceNumber'];
                $response['packages'][$i]['carrier_tracking_code'] = $reply['Pieces']['Piece'][$i]['LicensePlate'];
                $response['packages'][$i]['barcode'] = 'J' . $reply['Pieces']['Piece'][$i]['LicensePlate'];
                $awbs[] = $response['packages'][$i]['carrier_tracking_code'];
            }
        } else {

            // Single Piece
            $response['pieces'] = 1;
            $response['packages'][0]['sequence_number'] = 1;
            $response['packages'][0]['carrier_tracking_code'] = $reply['Pieces']['Piece']['LicensePlate'];
            $response['packages'][0]['barcode'] = 'J' . $reply['Pieces']['Piece']['LicensePlate'];
            $awbs[] = $reply['Pieces']['Piece']['LicensePlate'];
        }

        // Return Labels
        $response['label_format_type'] = $reply['LabelImage']['OutputFormat'];
        $response['label_size'] = $labelSize;
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['AirwayBillNumber']; // Master AWB no
        $response['label_base64'][0]['base64'] = $this->generatePDF($reply, $serviceCode); // Reformat

        return $response;
    }

    private function generatePdf($data, $serviceCode = '')
    {
        $label = new DHLLabel(null, $serviceCode, $data);
        return $label->create();
    }

    private function extract_errors($errorsFound)
    {

        // Request failed with Errors
        if (is_array($errorsFound)) {

            // Multiple Errors
            foreach ($errorsFound as $error) {
                $errorString = preg_replace('!\s+!', ' ', $error);
                $errors[] = str_replace('XML:Error', '', str_replace('DHL ', '', $errorString));
            }
        } else {

            // Single Error
            $errorString = preg_replace('!\s+!', ' ', $errorsFound);
            $errors[] = str_replace('XML:Error', '', str_replace('DHL ', '', $errorString));
        }


        return $errors;
    }

}

?>
