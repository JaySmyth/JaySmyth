<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message', 'status', 'status_detail', 'carrier', 'city', 'state', 'country_code', 'postcode', 'tracker_id', 'source', 'user_id', 'shipment_id', 'datetime', 'local_datetime', 'estimated_delivery_date', 'local_estimated_delivery_date'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['datetime', 'estimated_delivery_date', 'local_datetime', 'local_estimated_delivery_date'];

    /**
     * A Tracking event belongs to a shipment.
     *
     * @return
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the message attribute - replace all occurrences of carrier names with "carrier".
     *
     * @param  string  $value
     * @return string
     */
    public function getMessageAttribute($value)
    {
        return str_ireplace(\App\Carrier::where('code', '<>', 'ifs')->pluck('name')->toArray(), 'carrier', $value);
    }

    /**
     * Returns a user's name if the tracking event has been created manually.
     *
     * @return string or null
     */
    public function getUserNameAttribute()
    {
        $user = User::find($this->user_id);

        if ($user) {
            return $user->name;
        }
    }
}
