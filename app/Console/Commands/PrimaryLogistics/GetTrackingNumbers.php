<?php

namespace App\Console\Commands\PrimaryLogistics;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GetTrackingNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'primary-logistics:get-tracking-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates shipment tracking number and creates EasyPost trackers';
    protected $user;
    protected $key;
    protected $uri = 'https://api.cartrover.com/v1/cart/orders/status/';
    protected $tempFile;
    protected $updated = 0;

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
        $this->tempFile = storage_path('app/temp/tracking_'.time().'.csv');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['auth' => [$this->user, $this->key]]);

        // Retreive the shipments that require updated
        $shipments = \App\Shipment::where('carrier_consignment_number', '=', DB::raw('consignment_number'))->whereSource('cartrover')->whereCarrierId(12)->whereCompanyId(874)->whereReceivedSent(1)->whereNotIn('status_id', [1, 7])->whereIn('recipient_country_code', ['US', 'CA'])->get();

        $this->info($shipments->count().' shipments found');

        foreach ($shipments as $shipment) {
            try {

                // Send the request to CartRover
                $response = $client->get($this->uri.$shipment->consignment_number);

                // Get cart rover response
                $reply = json_decode($response->getBody()->getContents(), true);

                if (isset($reply['response']['order_status']) && $reply['response']['order_status'] == 'shipped') {
                    $shipment->carrier_consignment_number = $reply['response']['shipments'][0]['tracking_no'];
                    $shipment->carrier_tracking_number = $reply['response']['shipments'][0]['tracking_no'];
                    $shipment->ship_date = strtotime($reply['response']['shipments'][0]['date']);
                    $shipment->save();

                    // Create an easypost tracker
                    dispatch(new \App\Jobs\CreateEasypostTracker($shipment->carrier_consignment_number, $reply['response']['shipments'][0]['carrier']));

                    // Append to the shopify stock sync file
                    $this->addToTrackingFile($shipment, $reply['response']['shipments'][0]['carrier']);

                    $this->updated++;
                }
            } catch (GuzzleException $exc) {
                if ($exc->hasResponse()) {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Get Primary Logistics Tracking Numbers ('.$shipment->company->company_name.'/'.$shipment->consignment_number.')', Psr7\str($exc->getResponse())));
                }
            }
        }

        if ($this->updated > 0) {
            Mail::to('kerrikids1526376033@in.fulfillment.stock-sync.com')->cc(['it@antrim.ifsgroup.com', 'info@babocush.com', 'kerry.nevins@babocush.com'])->send(new \App\Mail\GenericError('Tracking Numbers - '.$this->updated.' shipments', null, $this->tempFile));
        }
    }

    /**
     * Build a CSV file to send to babocush.
     *
     * @param type $shipment
     */
    protected function addToTrackingFile($shipment, $carrier)
    {
        $handle = fopen($this->tempFile, 'a');

        $line = [
            $shipment->shipment_reference,
            1,
            $shipment->pieces,
            $shipment->carrier_consignment_number,
            $carrier,
        ];

        fputcsv($handle, $line);
        fclose($handle);
    }
}
