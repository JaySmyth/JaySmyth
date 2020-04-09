<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierAccounts extends Model
{
    protected $fillable = [
        'carrier_id',
        'account',
        'company_name',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'postcode',
        'country_code',
        'telephone',
        'vatno',
    ];
}
