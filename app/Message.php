<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['valid_from', 'valid_to', 'created_at', 'updated_at'];

    /**
     * A message may have multiple depots.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function depots()
    {
        return $this->belongsToMany(Depot::class);
    }

    /**
     * Users that have viewed a message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Companies that have been excluded
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * Scope - enabled messages within the date range
     * 
     * @param type $query 
     */
    public function scopeActive($query)
    {
        return $query->where('valid_from', '<=', Carbon::now())
                        ->where('valid_to', '>=', Carbon::now())
                        ->whereEnabled(1);
    }

}
