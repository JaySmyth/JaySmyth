<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FukPostcode extends Model
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
    protected $table = 'FUKPostCodes';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
}
