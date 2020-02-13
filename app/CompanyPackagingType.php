<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPackagingType extends Model
{
    public function packagingType()
    {
        return $this->belongsTo(\App\PackagingType::class, 'packaging_type_id');
    }
}
