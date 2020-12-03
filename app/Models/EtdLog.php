<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtdLog extends Model
{

    /*
    * Mass assignable.
    */
    protected $guarded = ['id'];


    /**
     * Get the shipment that owns the log.
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
