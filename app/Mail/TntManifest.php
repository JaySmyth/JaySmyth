<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TntManifest extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (file_exists($this->attachment)) {
            $this->attach($this->attachment, ['as' => 'manifest.pdf', 'mime' => 'application/pdf']);
        }

        return $this->to('courier@antrim.ifsgroup.com')
            ->subject('TNT Summary Manifest')
            ->view('emails.manifests.tnt');
    }
}
