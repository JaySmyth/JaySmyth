<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateChangeLogs extends Model {

    protected $fillable = [
        'user_id',
        'company_id',
        'service_id',
        'rate_id',
        'directory',
        'filename',
        'action'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

}
