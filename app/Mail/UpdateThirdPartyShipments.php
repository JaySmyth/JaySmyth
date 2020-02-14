<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateThirdPartyShipments extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($results)
    {
        $this->results = $results;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Attach the CSV files
        if (! empty($this->results['file'])) {
            if (file_exists($this->results['file'])) {
                $this->attach($this->results['file']);
            }
        }

        return $this->view('emails.shipments.update_third_party')
                        ->subject($this->results['subject'])
                        ->with(['results' => $this->results]);
    }
}
