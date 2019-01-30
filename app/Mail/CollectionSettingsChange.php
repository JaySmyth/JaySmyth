<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollectionSettingsChange extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $company;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Collection/Delivery settings changed for ' . $this->company->company_name;

        return $this->view('emails.companies.collection_settings')
                        ->subject($subject)
                        ->with(['company' => $this->company, 'user' => $this->user]);
    }

}
