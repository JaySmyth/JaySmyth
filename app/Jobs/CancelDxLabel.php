<?php

namespace App\Jobs;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CancelDxLabel implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $shipment;
    protected $url;
    protected $user;
    protected $password;

    public function __construct($shipmentId)
    {
        $this->shipment = Shipment::find($shipmentId);
        $this->url = config('services.dx.url');
        $this->user = config('services.dx.user');
        $this->password = config('services.dx.password');
    }

    public function handle()
    {
        if ($this->shipment->carrier_id == 17 && $this->shipment->status_id != 7) {
            // We need to cancel each label created
            foreach ($this->shipment->packages as $package) {
                $response = Http::post($this->url.'cancelLabel', [
                    'cancelLabel' => [
                        "customerID" => $this->shipment->bill_shipping_account,
                        "trackingNumber" => $package->carrier_tracking_number
                    ],
                    'serviceHeader' => [
                        'userId' => $this->user,
                        'password' => $this->password
                    ]
                ]);

                dd($response->body());
            }
        }
    }

}
