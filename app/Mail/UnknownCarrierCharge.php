<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnknownCarrierCharge extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $invoice;
    protected $carrierChargeCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $carrierChargeCode)
    {
        $this->invoice = $invoice;
        $this->carrierChargeCode = $carrierChargeCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.purchase_invoices.unknown_carrier_charge')
                        ->subject('Unknown Carrier Charge (record needs updated)')
                        ->with(['invoice' => $this->invoice, 'carrierChargeCode' => $this->carrierChargeCode]);
    }

}
