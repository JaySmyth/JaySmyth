<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransportJobReinstated extends Mailable
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
            $subject = 'REINSTATED - Collection ' . $this->transportJob->number . ' (' . $this->transportJob->from_company_name . ')';
        } else {
            $subject = 'REINSTATED - Delivery ' . $this->transportJob->number . ' (' . $this->transportJob->to_company_name . ')';
        }

        return $this->view('emails.transport_jobs.reinstated')
                        ->subject($subject)
                        ->with(['transportJob' => $this->transportJob]);
    }

}
