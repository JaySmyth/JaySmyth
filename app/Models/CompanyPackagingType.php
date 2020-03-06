<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPackagingType extends Model
{
    public function packagingType()
    {
        return $this->belongsTo(\App\Models\PackagingType::class, 'packaging_type_id');
    }
}
