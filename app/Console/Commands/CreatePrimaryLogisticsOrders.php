<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;

class CreatePrimaryLogisticsOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:create-primary-logistics-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends "new order" requests to Primary Logistics (CartRover API)';
    protected $username = '';
    protected $password = '';
    protected $uri = 'https://api.cartrover.com/v1/cart/orders/cartrover';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        // Retreive the shipments to be uploaded
        $shipments = \App\Shipment::whereCarrierId(12)->whereReceivedSent(0)->whereNotIn('status_id', [1, 7])->get();

        foreach ($shipments as $shipment) {

            if (!$this->isValid($shipment)) {
                continue;
            }

            $json = $this->buildJson($shipment);

            if (!$json) {
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Create Primary Logistics Order Failed (' . $shipment->consignment_number . ')', 'Failed to json encode data'));
                continue;
            }

            try {

                // Send the json to cart rover
                $response = $client->post($this->uri, $json);

                // Get cart rover response
                $reply = json_decode($response->getBody()->getContents(), true);

                // Order created successfully
                if (isset($reply['success_code']) && $reply['success_code']) {
                    $shipment->received_sent = 1;
                    $shipment->save();
                } else {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Create Primary Logistics Order Failed (' . $shipment->consignment_number . ')', $reply['message']));
                }
            } catch (GuzzleException $exc) {

                if ($exc->hasResponse()) {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Primary Logistics Order (' . $shipment->consignment_number . ')', Psr7\str($exc->getResponse())));
                }
            }
        }
    }

    /**
     * Return json.
     * 
     * @param type $shipment
     * @return type
     */
    protected function buildJson($shipment)
    {
        $items = [];

        foreach ($shipment->contents as $item) {
            $items[] = [
                'item' => $item->product_code,
                'quantity' => $item->quantity,
                'description' => $item->description,
            ];
        }

        $json = [
            'cust_ref' => $shipment->consignment_number,
            'cust_po_no' => $shipment->shipment_reference . '/' . $shipment->consignment_number,
            'ship_company' => $shipment->recipient_company_name,
            'ship_address_1' => $shipment->recipient_address1,
            'ship_address_2' => $shipment->recipient_address2,
            'ship_city' => $shipment->recipient_city,
            'ship_state' => $shipment->recipient_state,
            'ship_zip' => $shipment->recipient_postcode,
            'ship_country' => $shipment->recipient_country_code,
            'ship_e_mail' => $shipment->recipient_email,
            'ship_phone' => $shipment->recipient_telephone,
            'ship_address_type' => $shipment->recipient_type,
            'ship_is_billing' => false,
            'items' => $items
        ];

        return json_encode($json, JSON_HEX_AMP | JSON_HEX_APOS);
    }

    /**
     * Check that a shipment is valid for Primary Logistics upload.
     *
     * @return boolean
     */
    private function isValid($shipment)
    {
        // Not babocush
        if ($shipment->company_id != 874) {
            return false;
        }

        // Not US or Canada
        if (!in_array(strtoupper($shipment->recipient_country_code), ['US', 'CA'])) {
            return false;
        }

        return true;
    }

}
