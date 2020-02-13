<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrierPackagingType extends Model
{
    public function packagingType()
    {
        return $this->belongsToMany('App\PackagingType');
    }
}
