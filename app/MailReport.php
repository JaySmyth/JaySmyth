<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailReport extends Model
{
    /**
     * A mail report has many recipients.
     *
     * @return
     */
    public function recipients()
    {
        return $this->hasMany(MailReportRecipient::class)->orderBy('name');
    }
}
