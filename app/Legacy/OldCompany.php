<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class OldCompany extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';
    
    /**
     * Specify primary key
     */
    protected $primaryKey = 'company';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';

    /*
     * Not mass assignable
     */
    protected $guarded = ['company'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
}
