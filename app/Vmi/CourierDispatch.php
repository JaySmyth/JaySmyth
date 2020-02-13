<?php

namespace App\Vmi;

use Illuminate\Database\Eloquent\Model;

class CourierDispatch extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'vmi';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courier_dispatch';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
}
