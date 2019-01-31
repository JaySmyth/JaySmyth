<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FuelSurchargeCreated extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $fuelSurcharge;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fuelSurcharge)
    {
        $this->fuelSurcharge = $fuelSurcharge;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.fuel_surcharge.create')
                        ->subject('New Fuel Surcharge')
                        ->with(['user' => $this->fuelSurcharge]);
    }

}
