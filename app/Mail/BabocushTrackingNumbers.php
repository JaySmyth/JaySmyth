<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BabocushTrackingNumbers extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;
    protected $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($results, $filePath)
    {
        $this->results = $results;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->filePath && file_exists($this->filePath)) {
            $this->attach($this->filePath);
        }

        return $this->view('emails.shipments.babocush_tracking_numbers')
                        ->subject('Babocush USA tracking numbers - '.count($this->results['success']))
                        ->with(['results' => $this->results]);
    }
}
