<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CopyDocs extends Mailable
{

    use Queueable,
        SerializesModels;

    public $from;
    protected $invoices;    
    protected $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoices, $from = 'courierinvoice_exports@antrim.ifsgroup.com', $subject = 'Copy Docs Request')
    {
        $this->invoices = $invoices;
        $this->from = $from;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from)
                        ->view('emails.purchase_invoices.copy_docs')
                        ->subject($this->subject)
                        ->with(['invoices' => $this->invoices]);
    }

}
