<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagingType extends Model
{

    public function getRateCode() {
        return $this->belongsToMany('App\CarrierPackaging');
    }
}