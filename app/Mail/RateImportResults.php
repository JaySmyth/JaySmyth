<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RateImportResults extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;
    protected $model;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model, $results)
    {
        $this->model = $model;
        $this->results = $results;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->model=='domestic') {
            return $this->view('emails.rates.import_domestic_results')
            ->subject('Domestic Master Rate Import')
            ->with(['results' => $this->results]);
        } else {
            return $this->view('emails.rates.import_intl_results')
            ->subject('Intl Master Rate Import')
            ->with(['results' => $this->results]);
        }
    }
}
