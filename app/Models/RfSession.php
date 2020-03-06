<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfSession extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;
    public $guarded = ['id'];
}
