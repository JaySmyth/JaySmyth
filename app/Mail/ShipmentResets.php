<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentResets extends Mailable
{
    use Queueable, SerializesModels;

    public $shipments;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipments, $subject)
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
        return $this->view('emails.shipments.resets')->subject($this->subject);
    }
}
