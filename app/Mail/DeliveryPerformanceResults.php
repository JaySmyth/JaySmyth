<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryPerformanceResults extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;
    protected $carriers;
    protected $startDate;
    protected $endDate;
    protected $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($depotId, $results, $carriers, $startDate, $endDate, $type)
    {
        $this->depotName = \App\Models\Depot::find($depotId)->name;
        $this->results = $results;
        $this->carriers = $carriers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipments.delivery_performance_results')
                        ->subject($this->depotName." Delivery Performance Details: $this->startDate to $this->endDate")
                        ->with([
                          'subject' => 'Delivery Performance - '.$this->type,
                          'data' => $this->results,
                          'carriers' => $this->carriers,
                          'depotName' => $this->depotName
                        ]);
    }
}
