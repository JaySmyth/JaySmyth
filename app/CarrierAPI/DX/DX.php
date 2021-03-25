<?php

namespace App\CarrierAPI\DX;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class DX
{
    protected $shipment;
    protected $customerId;
    protected $username;
    protected $password;
    protected $url;
    protected $serviceDescription;
    protected $client;
    protected $mode;
    protected $request;


    public function __construct($shipment, $mode)
    {
        // Data array passed through
        $this->shipment = $shipment;

        // Set the mode
        $this->mode = $mode;

        $this->url = 'https://despatch-api.thedx.co.uk/DXCKService/';

        // Define the environment
        switch ($this->mode) {
            case 'test':
                $this->customerId = '14337622';
                $this->username = 'IFSTestAPI';
                $this->password = '99IfSTesTAPI523';
                $this->serviceDescription = 'DX Iver Leave Safe, 24 Hour, Leave Safe DX2H, 08:00 - 18:00';
                break;

            default:
                $this->customerId = '14631619';
                $this->username = 'IFSProdAPI';
                $this->password = '88ProdIfSApi48';
                $this->serviceDescription = 'IFS Global Logistics Signature Box, 24 Hour, Signature, 08:00 - 18:00';
                break;
        }

        // New Guzzle client
        $this->client = new Client();
    }


    /**
     * Send API requests to DX. First getServices call, followed by createLabel
     * requests for each package.
     *
     * @return array
     */
    public function sendRequest()
    {
        $service = $this->getService();

        if (! $service) {
            $error['ShipmentResponse']['Notification'][0]['Message'] = 'Service unavailable. Please contact Courier.';

            return $error;
        }

        // Get an IFS consignment number
        $reply = ['ifs_consignment_number' => nextAvailable('CONSIGNMENT')];

        // Make a label request for each package
        foreach ($this->shipment['packages'] as $package) {
            // Build an array to send as json to DX
            $request = $this->getLabelRequest($reply['ifs_consignment_number'], $package, $service);

            // Log the request
            $this->log('createLabel', 'O', json_encode($request));

            try {
                // Send the request and get the response
                $response = $this->client->post($this->url.'createLabel', ['json' => $request]);

                // Get the response body
                $response = $response->getBody()->getContents();

                $reply['packages'][] = json_decode($response, true);

                // Log the response body
                $this->log('REPLY', 'I', $response);
            } catch (GuzzleException $exception) {
                $error['ShipmentResponse']['Notification'][0]['Message'] = 'Problem processing shipment details. Please contact IT';

                return $error;
            }
        }

        return $reply;
    }

    /**
     * Call getService endpoint and return service array.
     *
     * @return mixed
     */
    protected function getService()
    {
        $request = [
            'serviceRequest' => [
                'customerID' => $this->customerId,
                'serviceFeatures' => [
                    [
                        'name' => 'deliveryLocationType',
                        'value' => (strtolower($this->shipment['recipient_type']) == 'c') ? 'BUSADD' : 'RESADD'
                    ]
                ],
                'deliverTo' => [
                    'address' => [
                        'postalCode' => $this->shipment['recipient_postcode'],
                        'country' => [
                            'countryCode' => $this->shipment['recipient_country_code']
                        ],
                        'type' => 'ORG_CUST',
                        'primary' => 1
                    ]
                ]
            ],
            'serviceHeader' => [
                'userId' => $this->username,
                'password' => $this->password
            ]
        ];

        // Log the request
        $this->log('getServices', 'O', json_encode($request));

        // Get available services
        $response = $this->client->post($this->url.'getServices', ['json' => $request]);

        // Get the response body
        $response = $response->getBody()->getContents();

        // Log the response body
        $this->log('REPLY', 'I', $response);

        // JSON to array
        $services = json_decode($response, true);

        foreach ($services['Service'] as $service) {
            if ($service['description'] == $this->serviceDescription) {
                return $service['legacyService'];
            }
        }

        return false;
    }

    /**
     * Create a transaction log.
     *
     * @param  type  $type
     * @param  type  $direction
     * @param  type  $msg
     */
    protected function log($type, $direction, $msg)
    {
        \App\Models\TransactionLog::create([
            'type' => $type,
            'carrier' => 'DX',
            'direction' => $direction,
            'msg' => $msg,
            'mode' => $this->mode,
        ]);
    }

    /**
     * Build the label request.
     *
     * @return array
     */
    protected function getLabelRequest($ifsConsignmentNumber, $package, $service)
    {
        return [
            'order' => [
                'customerID' => $this->customerId,
                'orderType' => 'Cons',
                'sourceSystem' => 'IFS API',
                'orderStatus' => [
                    'attributeList' => [
                        [
                            'name' => 'Current',
                            'value' => 'Active'
                        ]
                    ]
                ],
                'dates' => [
                    'date' => [
                        [
                            'value' => now()->toDateTimeLocalString(),
                            'type' => 'requestedCollectionDate',
                            'format' => 'yyyy-MM-dd HH:mm:ss'
                        ]
                    ]
                ],
                'sourceSystemReference' => $ifsConsignmentNumber.' - '.$package['index'].'/'.$this->shipment['pieces'],
                'customerReference' => $this->shipment['shipment_reference'] ?? null,
                'orderLines' => [
                    [
                        'consignment' => [
                            'pieces' => [
                                [
                                    'dimensions' => [
                                        [
                                            'value' => $package['weight'],
                                            'type' => 'cdlWeight',
                                            'UOM' => 'KG'
                                        ],
                                        [
                                            'value' => $package['length'],
                                            'type' => 'cdlLength',
                                            'UOM' => 'CM'
                                        ],
                                        [
                                            'value' => $package['width'],
                                            'type' => 'cdlWidth',
                                            'UOM' => 'CM'
                                        ],
                                        [
                                            'value' => $package['height'],
                                            'type' => 'cdlHeight',
                                            'UOM' => 'CM'
                                        ],
                                    ]
                                ]
                            ],
                            'serviceDetails' => [
                                'isInsured' => false,
                                'insuredAmount' => 0
                            ],
                            'legacyService' => $service,
                            'deliverTo' => [
                                'address' => [
                                    [
                                        'organisationName' => $this->shipment['recipient_company_name'] ?? null,
                                        'addressLine1' => $this->shipment['recipient_address1'] ?? null,
                                        'addressLine2' => $this->shipment['recipient_address2'] ?? null,
                                        'addressLine3' => $this->shipment['recipient_address3'] ?? null,
                                        'postalCode' => $this->shipment['recipient_postcode'] ?? null,
                                        'country' => [
                                            'countryCode' => $this->shipment['recipient_country_code']
                                        ],
                                        'type' => 'ORG_CUST',
                                        'primary' => true
                                    ]
                                ],
                                'contact' => [
                                    [
                                        'title' => null,
                                        'firstName' => Str::before($this->shipment['recipient_name'], ' ') ?? null,
                                        'lastName' => Str::after($this->shipment['recipient_name'], ' ') ?? null,
                                        'phone' => $this->shipment['recipient_telephone'] ?? null,
                                        'email' => $this->shipment['recipient_email'] ?? null,
                                        'mobile' => $this->shipment['recipient_telephone'] ?? null,
                                    ]
                                ]
                            ],
                            'consignmentReferences' => [
                                'attributeList' => [
                                    [
                                        'name' => 'customerReference',
                                        'value' => $ifsConsignmentNumber.'/'.$this->shipment['shipment_reference'] ?? null
                                    ],
                                    [
                                        'name' => 'contents',
                                        'value' => $this->shipment['goods_description'] ?? null
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'labelType' => 'PDF'
            ],
            'serviceHeader' => [
                'userId' => $this->username,
                'password' => $this->password
            ]
        ];
    }


}
