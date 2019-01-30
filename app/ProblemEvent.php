<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemEvent extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;

    /**
     * Get the event.
     *
     * @param  string  $value
     * @return string
     */
    public function setEventAttribute($value)
    {
        $this->attributes['event'] = strtoupper($value);
    }

    /**
     * Get the event.
     *
     * @param  string  $value
     * @return string
     */
    public function getEventAttribute($value)
    {
        return strtoupper($value);
    }

}
