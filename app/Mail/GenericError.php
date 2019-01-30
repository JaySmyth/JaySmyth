<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericError extends Mailable
{

    use Queueable,
        SerializesModels;

    public $subject;
    protected $msg;
    protected $path;
    protected $warning;
    protected $detail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $msg, $path = false, $warning = false, $detail = false)
    {
        $this->subject = $subject;
        $this->msg = $msg;
        $this->path = $path;
        $this->warning = $warning;
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (file_exists($this->path)) {
            $this->attach($this->path);
        }

        return $this->view('emails.errors.generic')
                        ->subject($this->subject)
                        ->with(['msg' => $this->msg, 'path' => $this->path, 'warning' => $this->warning, 'detail' => $this->detail]);
    }

}
