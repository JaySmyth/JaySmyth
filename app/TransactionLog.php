<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{

    protected $fillable = [
        'type',
        'carrier',
        'direction',
        'msg',
        'mode'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

}
