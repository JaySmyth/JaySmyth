<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepricingLog extends Model
{

    protected $guarded = ['id'];


    protected $dates = ['created_at', 'updated_at'];
}
