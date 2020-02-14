<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Pricing\Facades;

use Illuminate\Support\Facades\Facade;

class Pricing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Pricing';
    }
}
