<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /*
     * No timestamps
     */
    public $timestamps = false;

    /**
     * A vehicle is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Set the registration.
     *
     * @param  string  $value
     * @return string
     */
    public function setRegistrationAttribute($value)
    {
        $this->attributes['registration'] = strtoupper($value);
    }

    /**
     * Set the registration.
     *
     * @param  string  $value
     * @return string
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = ucwords(strtolower($value));
    }
}
