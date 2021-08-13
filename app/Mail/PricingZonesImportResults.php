<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricingZonesImportResults extends Mailable
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
        return $this->view('emails.pricing_zones.import_results')
                ->subject('General Pricing Zones Import')
                ->with(['results' => $this->results]);
    }
}
