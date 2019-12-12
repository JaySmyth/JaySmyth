<?php

namespace App\Tracking;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;

class UPS extends Tracking
{
    protected $user;
    protected $password;
    protected $key;


    /**
     * Send request to UPS and format the response.
     *
     * @return array
     */
    public function getEvents()
    {
        try {

            $client = $this->setClient();

            // Build an array to send as json to ship.ifsgroup.com
            $request = $this->buildRequest();

            // Send the request and get the response
            $response = $client->post($this->url, ['json' => $request]);

            // Decode the response to an array
            $response = json_decode($response->getBody()->getContents(), true);

            // Format and return the response
            return $this->formatResponse($response);

        } catch (GuzzleException $exception) {

            if ($exception->hasResponse()) {
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Get UPS tracking exception', Psr7\str($exception->getResponse())));
            }

        }
    }

    /**
     * Set the credentials and Guzzle client.
     *
     * @return Client
     */
    protected function setClient()
    {
        // Set credentials
        $this->user = config('services.ups.user');
        $this->password = config('services.ups.password');
        $this->key = config('services.ups.key');
        $this->url = config('services.ups.url') . '/Track';

        // New Guzzle client
        return new Client(['base_uri' => $this->url]);
    }

    /**
     * Build the tracking request.
     *
     * @return array
     */
    protected function buildRequest()
    {
        return [
            'UPSSecurity' => [
                'UsernameToken' => [
                    'Username' => $this->user,
                    'Password' => $this->password
                ],
                'ServiceAccessToken' => [
                    'AccessLicenseNumber' => $this->key
                ]
            ],

            'TrackRequest' => [
                'Request' => [
                    'RequestOption' => '1'
                ],
                'InquiryNumber' => $this->trackingNumber
            ]
        ];
    }

    /**
     * Format the response.
     *
     * @param $response
     * @return array
     */
    protected function formatResponse($response)
    {
        //$response = Arr::dot($response);
        //dd($response);

        // Error encountered
        if (isset($response['Fault'])) {
            //$response = Arr::dot($response);
            //Mail::to('dshannon@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Get UPS tracking ' . $this->trackingNumber . ' - fault', $response['Fault.detail.Errors.ErrorDetail.PrimaryErrorCode.Description']));
            //return [];
        }

        if ($this->shipment->pieces > 1) {

            $events = [];

            for ($i = 0; $i <= $this->shipment->pieces; $i++) {
                if (!empty($response['TrackResponse']['Shipment']['Package'][$i]['Activity'])) {
                    $activities = array_reverse($response['TrackResponse']['Shipment']['Package'][$i]['Activity']);
                    $events = $events + $this->processActivities($activities);
                }
            }

        }

        if (empty($events) && !empty($response['TrackResponse']['Shipment']['Package']['Activity'])) {
            $activities = array_reverse($response['TrackResponse']['Shipment']['Package']['Activity']);
            $events = $this->processActivities($activities);
        }

        return $events;

    }

    /**
     * Get the events.
     *
     * @param $activities
     * @return array
     */
    protected function processActivities($activities)
    {
        // Flatten using dot notation
        //$activities = Arr::dot($activities);
        //dd($activities);

        $events = [];

        if (isset($activities[0])) {

            foreach ($activities as $activity) {

                // Flatten using dot notation
                $activity = Arr::dot($activity);

                //dd($activity);
                $message = null;

                if (!empty($activity['Status.Description'])) {
                    $message = $activity['Status.Description'];

                    if (!empty($activity['ActivityLocation.SignedForByName']) && !empty($activity['ActivityLocation.Description'])) {
                        $message .= ': ' . $activity['ActivityLocation.Description'];
                    }
                }

                $events[] = [
                    'status' => $this->getStatus($activity),
                    'status_detail' => (!empty($activity['Status.Description'])) ? $activity['Status.Description'] : null,
                    'city' => (!empty($activity['ActivityLocation.Address.City'])) ? $activity['ActivityLocation.Address.City'] : null,
                    'country_code' => (!empty($activity['ActivityLocation.Address.CountryCode'])) ? $activity['ActivityLocation.Address.CountryCode'] : null,
                    'postcode' => (!empty($activity['ActivityLocation.Address.PostalCode'])) ? $activity['ActivityLocation.Address.PostalCode'] : null,
                    'local_datetime' => Carbon::createFromformat('YmdHis', $activity['Date'] . $activity['Time']),
                    'datetime' => Carbon::createFromformat('YmdHis', $activity['Date'] . $activity['Time']),
                    'carrier' => 'UPS',
                    'source' => 'UPS',
                    'message' => $message,
                    'signed_by' => (!empty($activity['ActivityLocation.SignedForByName'])) ? $activity['ActivityLocation.SignedForByName'] : null,
                ];

            }
        }

        return $events;
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
        return [
            'Order Processed: Ready for UPS'
        ];
    }


}