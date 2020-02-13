<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    public $timestamps = false;

    public static function getMode($name)
    {
        if (! $name) {
            $name = 'courier';
        }

        return self::where('name', '=', $name)->firstOrFail();
    }
}
