<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierPackagingType extends Model
{
    public function packagingType()
    {
        return $this->belongsToMany(\App\Models\PackagingType::class);
    }
}
