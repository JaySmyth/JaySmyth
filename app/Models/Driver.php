<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /*
     * No timestamps
     */
    public $timestamps = false;

    /**
     * A driver has many manifests.
     *
     * @return
     */
    public function driverManifests()
    {
        return $this->hasMany(DriverManifest::class);
    }

    /**
     * A transport manifest is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A driver has a default vehicle.
     *
     * @return
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * @return int
     */
    public function getOpenManifestCountAttribute()
    {
        return $this->driverManifests->where('closed', 0)->count();
    }

    /**
     * Set the driver's name.
     *
     * @param  string  $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}
