<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionSetting extends Model
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
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A collection setting is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Set the value.
     *
     * @param  string  $value
     * @return string
     */
    public function setCollectionRouteAttribute($value)
    {
        $this->attributes['collection_route'] = strtoupper($value);
    }

    /**
     * Set the value.
     *
     * @param  string  $value
     * @return string
     */
    public function setDeliveryRouteAttribute($value)
    {
        $this->attributes['delivery_route'] = strtoupper($value);
    }
}
