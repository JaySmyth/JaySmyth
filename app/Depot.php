<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{

    /**
     * A depot has one localisation.
     *
     * @return
     */
    public function localisation()
    {
        return $this->belongsTo(Localisation::class);
    }

}
