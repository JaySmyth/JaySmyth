<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NegativeVariances extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $invoice;
    protected $lines;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
        $this->lines = $this->invoice->getNegativeVariances();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.purchase_invoices.negative_variances')
                        ->subject('Negative Variances - '.$this->invoice->invoice_number)
                        ->with(['invoice' => $this->invoice, 'lines' => $this->lines]);
    }
}
