<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransportJobCreated extends Mailable
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
            $subject = 'Collection Request - '.$this->transportJob->from_company_name.', '.$this->transportJob->from_city;
        } else {
            $subject = 'Delivery Request - '.$this->transportJob->to_company_name.', '.$this->transportJob->to_city;
        }

        return $this->view('emails.transport_jobs.created')
                        ->subject($subject)
                        ->with(['transportJob' => $this->transportJob]);
    }
}
