<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissingPrimaryFreightDetails extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $packages;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipments, $subject = 'Missing Shipment Details')
    {
        $this->shipments = $shipments;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.missing_shipment_details')
                        ->subject($this->subject)
                        ->with(['subject' => $this->subject, 'shipments' => $this->shipments]);
    }
}
