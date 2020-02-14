<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArrangePickup extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * The shipment instance.
     *
     * @var Order
     */
    protected $shipment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.pickup')
                        ->subject('Arrange Pickup - '.$this->shipment->company->site_name.' ('.$this->shipment->carrier_consignment_number.')')
                        ->with(['shipment' => $this->shipment]);
    }
}
