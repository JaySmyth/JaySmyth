<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFeedback extends Mailable
{
    use Queueable,
        SerializesModels;

    public $user;
    public $smiley;
    public $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $smiley, $comments)
    {
        $this->user     = $user;
        $this->smiley   = $smiley;
        $this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user->email)
                    ->view('emails.users.send_feedback')
                    ->subject('Customer Feedback: '.$this->user->companies->first()->company_name);
    }
}
