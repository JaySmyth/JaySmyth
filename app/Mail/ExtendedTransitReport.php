<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExtendedTransitReport extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;
    protected $startDate;
    protected $endDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($table, $startDate, $endDate)
    {
        $this->data = $table;
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
        return $this->view('emails.shipments.extended_transit_report')
                        ->subject("Extended Transit Report: ".substr($this->startDate, 0, 10)." to ".substr($this->endDate, 0, 10))
                        ->with([
                          'subject' => 'Extended Transit Report',
                          'data' => $this->data
                        ]);
    }
}
