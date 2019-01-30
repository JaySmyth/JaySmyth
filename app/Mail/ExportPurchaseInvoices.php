<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportPurchaseInvoices extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $invoices;
    protected $files;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoices, $files)
    {
        $this->invoices = $invoices;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Attach the XML files
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                $this->attach($file);
            }
        }

        return $this->view('emails.purchase_invoices.export')
                        ->subject('Purchase Invoice Export (XML attached)')
                        ->with(['invoices' => $this->invoices, 'files' => $this->files]);
    }

}
