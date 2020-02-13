<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransportJobCancelled extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $transportJob;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transportJob)
    {
        $this->transportJob = $transportJob;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->transportJob->type == 'c') {
            $subject = 'CANCELLED - Collection '.$this->transportJob->number.'/'.$this->transportJob->reference.' ('.$this->transportJob->from_company_name.')';
        } else {
            $subject = 'CANCELLED - Delivery '.$this->transportJob->number.'/'.$this->transportJob->reference.' ('.$this->transportJob->to_company_name.')';
        }

        return $this->view('emails.transport_jobs.cancelled')
            ->subject($subject)
            ->with(['transportJob' => $this->transportJob]);
    }
}
