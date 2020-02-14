<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FuelSurchargeUploadResults extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $results;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($results)
    {
        $this->results = $results;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // Attach the CSV files
        foreach ($this->results['files'] as $file) {
            if (file_exists($file)) {
                $this->attach($file);
            }
        }

        return $this->view('emails.fuel_surcharges.upload_results')
                        ->subject($this->results['subject'])
                        ->with(['results' => $this->results]);
    }
}
