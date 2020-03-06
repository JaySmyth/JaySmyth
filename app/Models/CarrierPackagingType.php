<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierPackagingType extends Model
{
    public function packagingType()
    {
        return $this->belongsToMany(\App\Models\PackagingType::class);
    }
}
