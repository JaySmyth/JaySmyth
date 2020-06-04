<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomsProcedureCode extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /*
     *  No timestamps.
     */
    public $timestamps = false;
}
