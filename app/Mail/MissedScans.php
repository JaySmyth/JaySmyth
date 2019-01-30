<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissedScans extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $packages;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiptScans, $routeScans, $subject = 'Missed Scans')
    {
        $this->receiptScans = $receiptScans;
        $this->routeScans = $routeScans;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.shipments.missed_scans')
                        ->subject($this->subject)
                        ->with(['subject' => $this->subject, 'receiptScans' => $this->receiptScans, 'routeScans' => $this->routeScans]);
    }

}
