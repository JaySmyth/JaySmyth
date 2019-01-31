<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;

    /*
     * Mass assignable.
     */
    protected $fillable = ['email', 'type', 'collected', 'despatched', 'out_for_delivery', 'delivered', 'cancelled', 'problems', 'shipment_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['collected_sent_at', 'despatched_sent_at', 'out_for_delivery_sent_at', 'delivered_sent_at', 'cancelled_sent_at'];

    /**
     * Set the email.
     *
     * @param  string  $value
     * @return string
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Set the problems sent.
     *
     * @param  string  $value
     * @return string
     */
    public function setProblemsSentAttribute($value)
    {
        $this->attributes['problems_sent'] = strtoupper($value);
    }

}
