<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyStatusChange extends Mailable
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
        $subject = ($this->company->enabled) ? 'Company Enabled: ' : 'Company Account Disabled: ';
        
        return $this->view('emails.companies.status')
                        ->subject($subject . $this->company->company_name)
                        ->with(['company' => $this->company, 'user' => $this->user]);
    }

}
