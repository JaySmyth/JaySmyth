<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    /**
     * A report belongs to a mode of transport (courier, air, etc.).
     *
     * @return
     */
    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    /**
     * A report is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }
}
