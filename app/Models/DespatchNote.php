<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DespatchNote extends Model
{
    public $timestamps = false;

    /**
     * A charge belongs to a carrier.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
