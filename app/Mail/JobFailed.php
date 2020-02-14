<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobFailed extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $name;
    protected $exception;
    protected $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $exception, $path = false)
    {
        $this->name = $name;
        $this->exception = $exception;
        $this->path = $path;
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

        return $this->view('emails.errors.job_failed')
                        ->subject('JOB FAILED: '.$this->name)
                        ->with(['exception' => $this->exception, 'name' => $this->name, 'path' => $this->path]);
    }
}
