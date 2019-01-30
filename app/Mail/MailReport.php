<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailReport extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $reportName;
    protected $recipientName;
    protected $filePath;
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportName, $recipientName, $filePath, $data = array())
    {
        $this->reportName = $reportName;
        $this->recipientName = $recipientName;
        $this->filePath = $filePath;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->filePath && file_exists($this->filePath)) {
            $this->attach($this->filePath);
        }

        return $this->view('emails.mail_reports.' . $this->reportName)
                        ->subject(snakeCaseToWords($this->reportName) . ' - ' . $this->recipientName)
                        ->with(['name' => $this->recipientName, 'data' => $this->data]);
    }

}
