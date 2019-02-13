<?php

namespace App\Console\Commands\PrimaryLogistics;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;

class CancelOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'primary-logistics:cancel-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends "cancel order" requests to Primary Logistics (CartRover API)';
    protected $user;
    protected $key;
    protected $uri = 'https://api.cartrover.com/v1/cart/orders/wms_cancel/';

    /**
     * Cancel a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = config('services.cartrover.api_user');
        $this->key = config('services.cartrover.api_key');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['auth' => [$this->user, $this->key]]);

        // Retreive the shipments to be cancelled
        $shipments = \App\Shipment::whereSource('cartrover')->whereCarrierId(12)->whereCompanyId(874)->whereReceivedSent(1)->whereStatusId(7)->whereIn('recipient_country_code', ['US', 'CA'])->get();

        foreach ($shipments as $shipment) {

            try {

                // Send the request to CartRover
                $response = $client->get($this->uri . $shipment->consignment_number);

                // Get cart rover response
                $reply = json_decode($response->getBody()->getContents(), true);

                // Order created successfully
                if (isset($reply['success_code']) && $reply['success_code']) {
                    $shipment->received_sent = 0;
                    $shipment->source = 'cartrover_cancelled';
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
