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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($depot, $results, $carriers, $startDate, $endDate)
    {
        $this->depotName = \App\Models\Depot::find($depot)->name;
        $this->results = $results;
        $this->carriers = $carriers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
                          'subject' => 'Delivery Performance',
                          'data' => $this->results,
                          'carriers' => $this->carriers,
                          'depotName' => $this->depotName
                        ]);
    }
}
