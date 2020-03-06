<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class AddCharge_old extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /*
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A surcharge has many surcharge details.
     *
     * @return
     */
    public function addChargeDetails()
    {
        return $this->hasMany(AddChargeDetail::class)->orderBy('name');
    }

    /**
     * A surcharge has many surcharge details
     * But only some are valid for given date.
     *
     * @return
     */
    public function charges($effectiveDate)
    {
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');
        }

        return $this->addChargeDetails()->where('from_date', '<=', $effectiveDate)->where('to_date', '>=', $effectiveDate);
    }

    /**
     * Get the type (verbose).
     *
     * @return string
     */
    public function getVerboseTypeAttribute()
    {
        return ($this->type == 'c') ? 'Cost' : 'Sale';
    }
}
