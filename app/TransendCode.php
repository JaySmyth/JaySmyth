<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransendCode extends Model
{
    /*
     * No timestamps.
     */

    public $timestamps = false;

    /*
     * Black list of NON mass assignable - all others are mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id'];

}
