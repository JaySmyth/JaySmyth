<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FxFuel extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FX_Fuel';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['valid_from', 'valid_to'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
}
