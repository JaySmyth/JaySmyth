<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomesticRateDiscount extends Model {
    /*
     * Not mass assignable
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['from_date', 'to_date', 'created_at', 'updated_at'];
    public $timestamps = true;

}
