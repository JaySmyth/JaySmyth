<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvalidCommodityDescription extends Model
{
    /*
   * Mass assignable.
   */

    public $timestamps = false;

    /*
     * No timestamps.
     */
    protected $guarded = ['id'];
}
