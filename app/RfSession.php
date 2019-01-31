<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfSession extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;
    public $guarded = ['id'];

}
