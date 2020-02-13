<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PodShipments extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $shipments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.pod')
                        ->subject('High number of shipments need POD')
                        ->with(['shipments' => $this->shipments]);
    }
}
