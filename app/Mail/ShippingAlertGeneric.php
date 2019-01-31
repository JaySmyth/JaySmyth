<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShippingAlertGeneric extends Mailable
{

    use Queueable,
        SerializesModels;

    /**
     * The shipment instance.
     *
     * @var Order
     */
    protected $shipment;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipment, $email)
    {
        $this->shipment = $shipment;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.' . $this->email)
                        ->subject('Shipment ' . ucwords(str_replace('_', ' ', $this->email)) . '- ' . $this->shipment->consignment_number)
                        ->with(['shipment' => $this->shipment]);
    }

}
