<?php

namespace App\CarrierAPI\DHL;

use App\Models\Company;
use App\Models\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Arr;

class DHL
{
    protected $shipment;
    protected $username;
    protected $password;
    protected $url;
    protected $paymentTypes;
    protected $company;
    protected $request;
    protected $serviceType;
    protected $client;
    protected $isWithinEu;
    protected $rateRequest;
    protected $pltAvailable = false;
    protected $mode;

    public function __construct($shipment, $mode)
    {
        // Data array passed through
        $this->shipment = $shipment;

        // Set the mode
        $this->mode = $mode;

        // Payment types
        $this->paymentTypes = ['sender' => 'S', 'recipient' => 'R', 'other' => 'T'];

        // Company
        $this->company = Company::find($this->shipment['company_id']);

        // Boolean
        $this->isWithinEu = isWithinEu($this->shipment['sender_country_code'], $this->shipment['recipient_country_code']);

        // Get Service Product codes
        $productCodes = explode(',', Service::find($shipment['service_id'])->parameters);

        $this->serviceType = (isset($productCodes[0])) ? $productCodes[0] : null;

        // Define the environment
        switch ($this->mode) {
            case 'test':
                $this->username = 'ifsgloballoGB';
                $this->password = 'F#1tL$0dK#5w';
                $this->url = 'https://wsbexpress.dhl.com/rest/sndpt/';
                break;

            default:
                $this->username = 'ifsgloballoGB';
                $this->password = 'F#1tL$0dK#5w';
                $this->url = 'https://wsbexpress.dhl.com/rest/gbl/';
                break;
        }

        // New Guzzle client
        $this->client = new Client(['auth' => [$this->username, $this->password]]);
    }

    /**
     * @return mixed
     */
    public function sendRequest()
    {
        // Send rate request first
        if ($this->company->plt_enabled) {
            $this->sendRateRequest();
        }

        // Build an array to send as json to DHL
        $this->shipmentRequest();

        // Log the request
        $this->log('MSG-2', 'O', json_encode($this->request));

        try {
            // Send the request and get the response
            $response = $this->client->post($this->url.'ShipmentRequest', ['json' => $this->request]);

            // Get the response body
            $response = $response->getBody()->getContents();

            // Log the response body
            $this->log('REPLY-2', 'I', $response);

            return json_decode($response, true);
        } catch (GuzzleException $exception) {
            $error['ShipmentResponse']['Notification'][0]['Message'] = 'Problem processing shipment details. Please contact IT';

            return $error;
        }
    }

    /**
     * Send rate request to DHL and set the rateRequest property with result.
     */
    public function sendRateRequest()
    {
        $this->rateRequest();

        // Log the request
        $this->log('MSG-1', 'O', json_encode($this->request));

        try {
            // Send the request and get the response
            $response = $this->client->post($this->url.'RateRequest', ['json' => $this->request]);

            // Get the response body
            $response = $response->getBody()->getContents();

            // Log the response body
            $this->log('REPLY-1', 'I', $response);

            $this->rateRequest = json_decode($response, true);

            // Get the response for "WY" - indicates that PLT is available
            if (in_array('WY', Arr::flatten($this->rateRequest))) {
                $this->pltAvailable = true;
            }
        } catch (GuzzleException $exception) {
            $error['ShipmentResponse']['Notification'][0]['Message'] = 'Problem processing shipment details. Please contact IT';

            return $error;
        }
    }

    /**
     * The Rate request will return DHL’s product capabilities.
     */
    protected function rateRequest()
    {
        $this->request = [
            'RateRequest' => [
                'ClientDetails' => null,
                'RequestedShipment' => [
                    'DropOffType' => 'REQUEST_COURIER',
                    'ShipTimestamp' => date('Y-m-d', time()).'T'.date('H:i:s', time() + 120).'GMT+00:00',
                    'UnitOfMeasurement' => 'SI',
                    'DeclaredValue' => $this->shipment['customs_value'],
                    'DeclaredValueCurrecyCode' => (! empty($this->shipment['currency_code'])) ? $this->shipment['currency_code'] : 'GBP',
                    'Content' => $this->getContent(),
                    'PaymentInfo' => strtoupper($this->shipment['terms_of_sale']),
                    'NextBusinessDay' => 'Y',
                    'Account' => $this->shipment['bill_shipping_account'],
                    'RequestValueAddedServices' => 'Y',
                    'ServiceType' => $this->serviceType,
                    'Billing' => [
                        'ShipperAccountNumber' => $this->shipment['bill_shipping_account'],
                        'ShippingPaymentType' => $this->paymentTypes[$this->shipment['bill_shipping']],
                        'BillingAccountNumber' => $this->shipment['bill_shipping_account'],
                    ],
                    'Ship' => [
                        'Shipper' => [
                            'City' => $this->shipment['sender_city'],
                            'PostalCode' => $this->shipment['sender_postcode'],
                            'CountryCode' => $this->shipment['sender_country_code'],
                        ],
                        'Recipient' => [
                            'City' => $this->shipment['recipient_city'],
                            'PostalCode' => $this->shipment['recipient_postcode'],
                            'CountryCode' => $this->shipment['recipient_country_code'],
                        ],
                    ],
                    'Packages' => [
                        'RequestedPackages' => $this->addRatePackages(),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get shipment content.
     *
     * @return string
     */
    protected function getContent()
    {
        if ((strtolower($this->shipment['ship_reason']) == 'documents') || $this->isWithinEu) {
            return 'DOCUMENTS';
        }

        return 'NON_DOCUMENTS';
    }

    /**
     * Add package elements.
     *
     * @return array
     */
    protected function addRatePackages()
    {
        $packages = [];

        foreach ($this->shipment['packages'] as $package) {
            $packages[] = [
                '@number' => $package['index'],
                'Weight' => [
                    'Value' => $package['weight'],
                ],
                'Dimensions' => [
                    'Length' => $package['length'],
                    'Width' => $package['width'],
                    'Height' => $package['height'],
                ],
                'CustomerReferences' => 'Piece '.$package['index'],
            ];
        }

        return $packages;
    }

    /**
     * Create a transaction log.
     *
     * @param type $type
     * @param type $direction
     * @param type $msg
     */
    protected function log($type, $direction, $msg)
    {
        \App\Models\TransactionLog::create([
            'type' => $type,
            'carrier' => 'DHL',
            'direction' => $direction,
            'msg' => $msg,
            'mode' => $this->mode,
        ]);
    }

    /**
     * Build the request.
     *
     * @return array
     */
    protected function shipmentRequest()
    {
        $eori = (! empty($this->shipment['eori'])) ? $this->shipment['eori'] : $this->company->eori;

        if (strlen($eori) == 0) {
            $eori = '000000000000';
        }

        $this->request = [
            'ShipmentRequest' => [
                'RequestedShipment' => [
                    'ShipmentInfo' => [
                        'DropOffType' => 'REGULAR_PICKUP',
                        'ServiceType' => $this->serviceType,
                        'Currency' => (! empty($this->shipment['currency_code'])) ? $this->shipment['currency_code'] : 'GBP',
                        'UnitOfMeasurement' => 'SI',
                        'Billing' => $this->addBilling(),
                        'LabelType' => 'PDF',
                        'LabelTemplate' => 'ECOM26_64_002',
                        'LabelOptions' => [
                            'DetachOptions' => [
                                'SplitLabelsByPieces' => 'N',
                            ],
                        ],
                    ],
                    'ShipTimestamp' => date('Y-m-d', time()).'T'.date('H:i:s', time() + 120).'GMT+00:00',
                    'PaymentInfo' => strtoupper($this->shipment['terms_of_sale']),
                    'Ship' => [
                        'Shipper' => [
                            'Contact' => [
                                'PersonName' => $this->shipment['sender_name'],
                                'CompanyName' => (! empty($this->shipment['sender_company_name'])) ? $this->shipment['sender_company_name'] : $this->shipment['sender_name'],
                                'PhoneNumber' => $this->shipment['sender_telephone'],
                                'EmailAddress' => (! empty($this->shipment['sender_email'])) ? $this->shipment['sender_email'] : 'courier@antrim.ifsgroup.com',
                            ],
                            'Address' => $this->addAddress('sender'),
                            'RegistrationNumbers' => [
                                'RegistrationNumber' => [
                                    'Number' => $eori,
                                    'NumberTypeCode' => 'EIN',
                                    'NumberIssuerCountryCode' => $this->shipment['sender_country_code'],
                                ],
                            ],
                        ],
                        'Recipient' => [
                            'Contact' => [
                                'PersonName' => $this->shipment['recipient_name'],
                                'CompanyName' => (! empty($this->shipment['recipient_company_name'])) ? $this->shipment['recipient_company_name'] : $this->shipment['recipient_name'],
                                'PhoneNumber' => $this->shipment['recipient_telephone'],
                            ],
                            'Address' => $this->addAddress('recipient'),
                        ],
                    ],
                    'Packages' => [
                        'RequestedPackages' => $this->addPackages(),
                    ],
                ],
            ],
        ];

        $this->addPltShipment();
        $this->addInternationalDetail();

        // Dangerous goods not permitted on our account
        // Enable once approved and obtain valid "ContentID" values from DHL
        //$this->addDangerousGoods();
    }

    /**
     * Get the billing element.
     *
     * @return array
     */
    protected function addBilling()
    {
        $billing = [
            'ShipperAccountNumber' => $this->shipment['bill_shipping_account'],
            'ShippingPaymentType' => $this->paymentTypes[$this->shipment['bill_shipping']],
            'BillingAccountNumber' => $this->shipment['bill_shipping_account'],
        ];

        if (! empty($this->shipment['bill_tax_duty_account'])) {
            $billing['DutyAndTaxPayerAccountNumber'] = $this->shipment['bill_tax_duty_account'];
        }

        return $billing;
    }

    /**
     * Add address element.
     *
     * @param $type
     * @return array
     */
    protected function addAddress($type)
    {
        $address = [
            'StreetLines' => $this->shipment[$type.'_address1'],
            'City' => $this->shipment[$type.'_city'],
            'PostalCode' => $this->shipment[$type.'_postcode'],
            'CountryCode' => $this->shipment[$type.'_country_code'],
        ];

        if (! empty($this->shipment[$type.'_address2'])) {
            $address['StreetLines2'] = $this->shipment[$type.'_address2'];
        }

        if (! empty($this->shipment[$type.'_address3'])) {
            $address['StreetLines3'] = $this->shipment[$type.'_address3'];
        }

        return $address;
    }

    /**
     * Add package elements.
     *
     * @return array
     */
    protected function addPackages()
    {
        $packages = [];

        foreach ($this->shipment['packages'] as $package) {
            $packages[] = [
                '@number' => $package['index'],
                'Weight' => $package['weight'],
                'Dimensions' => [
                    'Length' => $package['length'],
                    'Width' => $package['width'],
                    'Height' => $package['height'],
                ],
                'CustomerReferences' => 'Piece '.$package['index'],
            ];
        }

        return $packages;
    }

    /**
     * Add PLT flags.
     *
     * ShipmentRequest/RequestedShipment/ShipmentInfo/SpecialServices/Service
     * ShipmentRequest/RequestedShipment/InternationalDetail/ExportDeclaration
     * ShipmentRequest/RequestedShipment/InternationalDetail/ExportDeclaration
     */
    protected function addPltShipment()
    {
        if ($this->pltAvailable) {
            $this->request['ShipmentRequest']['RequestedShipment']['ShipmentInfo']['PaperlessTradeEnabled'] = true;
            $this->request['ShipmentRequest']['RequestedShipment']['ShipmentInfo']['LabelOptions']['RequestDHLCustomsInvoice'] = 'Y';
            $this->request['ShipmentRequest']['RequestedShipment']['ShipmentInfo']['SpecialServices'] = [
                'Service' => [
                    'ServiceType' => 'WY',
                ],
            ];
            $this->request['ShipmentRequest']['RequestedShipment']['InternationalDetail']['ExportDeclaration'] = [
                'InvoiceDate' => date('Y-m-d'),
                'InvoiceNumber' => $this->company->id.time(),
            ];
        }
    }

    /**
     * Add commodities and line items elements.
     * ShipmentRequest/RequestedShipment/InternationalDetail/Commodities
     * ShipmentRequest/RequestedShipment/InternationalDetail/Content
     * ShipmentRequest/RequestedShipment/InternationalDetail/ExportDeclaration/ExportLineItems/ExportLineItem.
     *
     * @return null
     */
    protected function addInternationalDetail()
    {
        $this->request['ShipmentRequest']['RequestedShipment']['InternationalDetail']['Content'] = $this->getContent();

        if (! empty($this->shipment['contents'])) {
            $this->request['ShipmentRequest']['RequestedShipment']['InternationalDetail']['Commodities'] = [
                'Description' => substr(trim($this->shipment['contents'][0]['description']), 0, 35),
                'CustomsValue' => $this->shipment['customs_value'],
            ];

            $lineItems = [];
            $i = 1;

            foreach ($this->shipment['contents'] as $content) {
                $commodityCode = (! empty($content['harmonized_code'])) ? $content['harmonized_code'] : $content['commodity_code'];

                if (! $this->pltAvailable && strlen($commodityCode) == 0) {
                    $commodityCode = '0000000000';
                }

                $lineItems[] = [
                    'CommodityCode' => $commodityCode,
                    'ItemNumber' => $i,
                    'Quantity' => $content['quantity'],
                    'QuantityUnitOfMeasurement' => $this->convertToDhlUom($content['uom']),
                    'ItemDescription' => substr(trim($content['description']), 0, 35),
                    'UnitPrice' => round($content['unit_value'], 2),
                    'NetWeight' => round($content['unit_weight'] * $content['quantity'], 2),
                    'GrossWeight' => round($content['unit_weight'] * $content['quantity'], 2),
                    'ManufacturingCountryCode' => $content['country_of_manufacture'],
                ];

                $i++;
            }

            $this->request['ShipmentRequest']['RequestedShipment']['InternationalDetail']['ExportDeclaration']['ExportLineItems']['ExportLineItem'] = $lineItems;
        } else {
            $description = (strtolower($this->shipment['ship_reason']) == 'documents') ? $this->shipment['documents_description'] : $this->shipment['goods_description'];

            $this->request['ShipmentRequest']['RequestedShipment']['InternationalDetail']['Commodities'] = [
                'Description' => substr(trim($description), 0, 35),
            ];
        }
    }

    /**
     * Convert UOM to DHL spec.
     *
     * @param $uom
     * @return string
     */
    protected function convertToDhlUom($uom)
    {
        switch (strtoupper($uom)) {
            case 'CG':
                // Centigram
                return '2GM';
            case 'CM':
                // Centimeters
                return '2M';
            case 'CM3':
                // Cubic Centimeters
                return '2M3';
            case 'EA':
            case 'HUN':
            case 'QT':
            case 'YN':
                // Each
                return '2NO';
            case 'FT':
                // Square Feet
                return '2M2';
            case 'GAL':
                // Gallons
                return '2L';
            case 'GRM':
            case 'G':
                // Gram
                return 'GM';
            case 'MG':
                //  Milligrams
                return '3GM';
            case 'ML':
                // Milliliters
                return '3L';
            case 'PR':
                // Pairs
                return 'PRS';
            case 'LB':
                // Pounds
                return '3KG';
            case 'TOZ':
                // Ounces
                return '2KG';
            case 'YD':
                // Yards
                return '3M';
            default:
                return $uom;
        }
    }

    /**
     * Add dangerous goods.
     * ShipmentRequest/RequestedShipment/DangerousGoods/Content.
     *
     * @return array
     */
    protected function addDangerousGoods()
    {
        if ((isset($this->shipment['hazardous']) && $this->shipment['hazardous'] != 'N') || (isset($this->shipment['dry_ice_flag']) && $this->shipment['dry_ice_flag'])) {
            $this->request['ShipmentRequest']['RequestedShipment']['DangerousGoods']['Content'] = [
                'ContentID' => $this->shipment['hazardous'].'00',
                'DryIceTotalNetWeight' => $this->shipment['dry_ice_total_weight'],
            ];
        }
    }
}
