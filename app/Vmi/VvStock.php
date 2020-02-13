<?php

namespace App\Vmi;

use Illuminate\Database\Eloquent\Model;

class VvStock extends Model
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
    protected $table = 'vv_stock';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
}
