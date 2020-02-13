<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Feedback extends Mailable
{
    use Queueable,
        SerializesModels;

    public $user;
    public $answers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $answers)
    {
        $this->user = $user;
        $this->answers = $answers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user->email)
                        ->view('emails.users.feedback')
                        ->subject('Customer Feedback: '.$this->user->companies->first()->company_name)
                        ->with(['user' => $this->user, 'answers' => $this->answers]);
    }
}
