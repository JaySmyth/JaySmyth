<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShippingAlertProblem extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $shipment;
    protected $problemEvent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipment, $problemEvent)
    {
        $this->shipment = $shipment;
        $this->problemEvent = $problemEvent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.problem')
                        ->subject('Important information regarding shipment ' . $this->shipment->consignment_number)
                        ->with(['shipment' => $this->shipment, 'problem' => $this->problemEvent]);
    }

}
