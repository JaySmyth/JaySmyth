<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnmanifestedTransportJobs extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $transportJobs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transportJobs)
    {
        $this->transportJobs = $transportJobs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.transport_jobs.unmanifested')
                        ->subject('High number of collections/deliveries need manifested')
                        ->with(['transportJobs' => $this->transportJobs]);
    }

}
