<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
