<?php

namespace App\CarrierAPI\Express;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Express
{
    protected $shipment;
    protected $mode;
    protected $id;
    protected $secret;
    protected $username;
    protected $password;
    protected $client;
    protected $request;

    public function __construct($shipment, $mode)
    {
        $this->shipment = $shipment;
        $this->mode = $mode;

        $this->id = config('services.express.id');
        $this->secret = config('services.express.secret');
        $this->username = config('services.express.username');
        $this->password = config('services.express.password');

        // New Guzzle client
        $this->client = new Client(['base_uri' => config('services.express.url')]);
    }

    /**
     * Send API requests to Express.
     *
     * @return array
     */
    public function sendRequest()
    {
        // Build an array to send as json to Express
        $request = $this->getCreateConsigmentRequest();

        //dd($request);

        // Log the request
        $this->log('consignment', 'O', json_encode($request));

        try {
            $headers = [
                'Authorization' => 'Bearer '.$this->getBearerToken(),
                'Accept' => 'application/json',
            ];

            // Send the request and get the response
            $response = $this->client->post('Consignment/CreateConsignment', ['headers' => $headers, 'json' => $request]);

            // Get the response body
            $response = $response->getBody()->getContents();

            // Log the response body
            $this->log('REPLY', 'I', $response);

            return json_decode($response, true);
        } catch (GuzzleException $exception) {
            $error['ShipmentResponse']['Notification'][0]['Message'] = 'Problem processing shipment details. Please contact IT';

            return $error;
        }

        return $reply;
    }

    /**
     * Build the create consigmment request.
     *
     * @return array
     */
    protected function getCreateConsigmentRequest()
    {
        return [
            'consigneeName' => $this->shipment['recipient_name'] ?? null,
            'consigneeNumber' => null,
            'consigneeStreet' => $this->shipment['recipient_address1'] ?? null,
            'consigneeStreet2' => $this->shipment['recipient_address2'] ?? null,
            'consigneeTownland' => '',
            'consigneeCity' => $this->shipment['recipient_city'] ?? null,
            'consigneeCounty' => $this->shipment['recipient_state'] ?? null,
            'consigneePostcode' => $this->shipment['recipient_postcode'] ?? null,
            'consigneeRegion' => '',
            'contactName' => $this->shipment['recipient_name'] ?? null,
            'contactNo' => $this->shipment['recipient_telephone'] ?? null,
            'specialInstructions' => $this->shipment['special_instructions'] ?? null,
            'orderReference' => $this->shipment['shipment_reference'] ?? null,
            'serviceType' => 'STANDARD',
            'dispatchDate' => now()->toISOString(),
            'items' => $this->getItems(),
            'labelsLink' => true
        ];
    }

    protected function getItems()
    {
        $items = [];
        foreach ($this->shipment['packages'] as $package) {
            $items[] = [
                'itemType' => 'CARTON',
                'itemWeight' => $package['weight'],
                'itemHeight' => $package['height'],
                'itemWidth' => $package['width'],
                'itemLength' => $package['length'],
                'itemComments' => '',
                'noOfGarments' => 0,
                'limitedQuantites' => false,
                'dangerousGoods' => false
            ];
        }

        return $items;
    }

    /**
     * Call getService endpoint and return service array.
     *
     * @return mixed
     */

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
            'carrier' => 'Express',
            'direction' => $direction,
            'msg' => $msg,
            'mode' => $this->mode,
        ]);
    }

    /**
     * Get the bearer token.
     *
     * @return string
     * @throws GuzzleException
     */
    protected function getBearerToken()
    {
        $response = $this->client->request('GET', 'Token/GetNewToken', [
            'query' => [
                'ClientID' => $this->id,
                'ClientSecret' => $this->secret,
                'username' => $this->username,
                'password' => $this->password,
            ]
        ]);

        $contents = json_decode($response->getBody()->getContents(), true);

        return $contents['bearerToken'];
    }

}
