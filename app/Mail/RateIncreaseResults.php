<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RateIncreaseResults extends Mailable
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
        return $this->view('emails.rates.increase_results')
                        ->subject('Rate Increase Results')
                        ->with(['results' => $this->results]);
    }
}
