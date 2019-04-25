<?php

namespace App\Console\Commands\PrimaryLogistics;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;

class GetInventory extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'primary-logistics:get-inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stock inventory held by Primary Logistics and mail as CSV attachment';
    protected $user;
    protected $key;
    protected $uri = 'https://api.cartrover.com/v1/merchant/inventory';
    protected $tempFile;

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
        $this->tempFile = storage_path('app/temp/inventory_' . time() . '.csv');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['auth' => [$this->user, $this->key]]);

        try {

            // Send the request to CartRover
            $response = $client->get($this->uri);

            // Get cart rover response
            $reply = json_decode($response->getBody()->getContents(), true);

            if (!empty($reply['success_code'])) {

                $handle = fopen($this->tempFile, "a");
                $headers = ['SKU', 'Qty Available', 'Qty On Hand'];
                fputcsv($handle, $headers);

                foreach ($reply['response'] as $item) {
                    $line = [$item["sku"], $item["qty_available"], $item["qty_on_hand"]];
                    fputcsv($handle, $line);
                }

                fclose($handle);

                Mail::to(['aplatt@antrim.ifsgroup.com', 'vmi@kilroot.ifsgroup.com'])->send(new \App\Mail\GenericError('Daily Inventory Report - Babocush USA', null, $this->tempFile));
            }
        } catch (GuzzleException $exc) {
            if ($exc->hasResponse()) {
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Get Primary Logistics Inventory', Psr7\str($exc->getResponse())));
            }
        }
    }

}
