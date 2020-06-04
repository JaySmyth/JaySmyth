<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SeaFreightTracking extends Model
{
    protected $table = 'sea_freight_tracking';

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['datetime'];

    /**
     * A tracking event is owned by a shipment.
     *
     * @return
     */
    public function seaFreightShipment()
    {
        return $this->belongsTo(SeaFreightShipment::class);
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
