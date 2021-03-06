<?php

namespace App\Multifreight;

use Illuminate\Database\Eloquent\Model;

class RecCost extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'multifreight';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rec_cost';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
