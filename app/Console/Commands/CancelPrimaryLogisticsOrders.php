<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;

class CancelPrimaryLogisticsOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:cancel-primary-logistics-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends "cancel order" requests to Primary Logistics (CartRover API)';

    /**
     * Cancel a new job instance.
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
        $shipments = \App\Shipment::whereCarrierId(12)->whereReceivedSent(1)->whereStatusId(7)->get();

        foreach ($shipments as $shipment) {

            try {

                // Send the json to cart rover
                $response = $client->get('https://api.cartrover.com/v1/cart/orders/wms_cancel/' . $shipment->consignment_number);

                // Get cart rover response
                $reply = json_decode($response->getBody()->getContents(), true);

                // Order created successfully
                if (isset($reply['success_code']) && $reply['success_code']) {
                    $shipment->received_sent = 0;
                    $shipment->save();
                } else {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Cancel Primary Logistics Order Failed (' . $shipment->consignment_number . ')', $reply['message']));
                }
            } catch (GuzzleException $exc) {

                if ($exc->hasResponse()) {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Cancel Primary Logistics Order (' . $shipment->consignment_number . ')', Psr7\str($exc->getResponse())));
                }
            }
        }
    }

}
