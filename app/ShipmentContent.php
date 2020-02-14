<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentContent extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'package_index',
        'description',
        'manufacturer',
        'product_code',
        'commodity_code',
        'harmonized_code',
        'country_of_manufacture',
        'quantity',
        'uom',
        'unit_value',
        'currency_code',
        'unit_weight',
        'weight_uom',
        'shipment_id',
    ];
}
