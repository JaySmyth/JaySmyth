<?php

namespace App\Tracking;

use GuzzleHttp\Exception\GuzzleException;
use SimpleXMLElement;

class TNT extends Tracking
{

    /**
     * Send request to TNT and format the response.
     *
     * @return array
     */
    public function getEvents()
    {
        try {
            // Build an array to send as json to ship.ifsgroup.com
            $request = $this->buildRequest();

            // Send the request and get the response
            $response = $this->sendRequest($request);

            $xml = simplexml_load_string($response->getBody(), "SimpleXMLElement", LIBXML_NOCDATA);

            // Decode the response to an array
            $response = json_decode(json_encode($xml), true);

            dd($response);

            // Format and return the response
            return $this->formatResponse($response);
        } catch (GuzzleException $exception) {
            if ($exception->hasResponse()) {
                //Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Get TNT tracking exception', Psr7\str($exception->getResponse())));
            }
        }
    }

    /**
     * Build the tracking request.
     *
     * @return array
     */
    protected function buildRequest()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><TrackRequest locale="en_GB" version="3.1"></TrackRequest>');
        $searchCriteriaNode = $xml->addChild('SearchCriteria');
        $searchCriteriaNode->addAttribute('marketType', 'INTERNATIONAL');
        $searchCriteriaNode->addAttribute('originCountry', 'GB');
        $searchCriteriaNode->addChild('ConsignmentNumber', $this->trackingNumber);
        $levelOfDetailNode = $xml->addChild('LevelOfDetail');
        $levelOfDetailNode->addChild('Complete');
        $levelOfDetailNode->addAttribute('package', 'true');
        $levelOfDetailNode->addAttribute('shipment', 'true');

        return $xml->asXML();
    }

    /**
     * Post the tracking request to carrier.
     *
     * @param $string
     *
     * @return bool|string
     */
    private function sendRequest($string)
    {
        $string = 'xml_in='.$string; // Append "xml_in=" to beginning of string

        $header = [
            'POST ShipperGate2.asp HTTP/1.0',
            'Accept: */*',
            'User-Agent: ShipperGate_socket/1.0',
            'Content-type: application/x-www-form-urlencoded',
            'Content-length: '.strlen($string),
            '',
        ];

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_USERPWD, config('services.tnt.user').':'.config('services.tnt.password'));
        curl_setopt($ch, CURLOPT_URL, 'https://express.tnt.com/expressconnect/track.do');                // set url to post to
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (! empty($header)) {
            curl_setopt($ch, CURLOPT_HEADER, 1);            // CURL to output header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  // Header for CURL to output
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);            // CURL NOT to output header
        }

        curl_setopt($ch, CURLOPT_POST, 0);                  // Transmit as POST method
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);

        $result = curl_exec($ch);                           // send!
        curl_close($ch);                                    // close

        return $result;
    }

    /**
     * Format the response.
     *
     * @param $response
     *
     * @return array
     */
    protected function formatResponse($response)
    {
    }


    /**
     * Determine status from tracking event.
     *
     * @param $activity
     *
     * @return string
     */
    protected function getStatus($event)
    {
        switch ($event['Status.Type']) {
            case 'I':

                if (stristr($event['Status.Description'], 'out for delivery') || stristr($event['Status.Description'], 'LOADED ON DELIVERY VEHICLE')) {
                    return 'out_for_delivery';
                }

                if (stristr($event['Status.Description'], 'Delivered to UPS Access Point') || stristr($event['Status.Description'], 'pick up the package')) {
                    return 'available_for_pickup';
                }

                if (stristr($event['Status.Description'], 'YOUR PACKAGE IS BEING HELD')) {
                    return 'on_hold';
                }

                if (stristr($event['Status.Description'], 'to the sender')) {
                    return 'return_to_sender';
                }

                return 'in_transit';

            case 'D':
                return 'delivered';

            case 'X':

                if (stristr($event['Status.Description'], 'to the sender')) {
                    return 'return_to_sender';
                }

                return 'failure';

            case 'P':
            case 'M':
                return 'pre_transit';

            default:
                return 'unknown';
        }
    }

    /**
     * Events to ignore.
     *
     * @return array
     */
    protected function ignore()
    {
        return [];
    }
}
