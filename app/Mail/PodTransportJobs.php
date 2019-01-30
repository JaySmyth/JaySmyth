<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PodTransportJobs extends Mailable
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
        $collections = $this->transportJobs->where('type', 'c')->where('shipment_id', '<=', 0);
        $deliveries = $this->transportJobs->where('type', 'd');

        return $this->view('emails.transport_jobs.pod')
                        ->subject('High number of jobs need proof of delivery/collection')
                        ->with(['collections' => $collections, 'deliveries' => $deliveries]);
    }

}
