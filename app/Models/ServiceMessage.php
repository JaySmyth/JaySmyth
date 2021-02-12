<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceMessage extends Model
{
    public $timestamps = false;
    protected $guarded  = ['id'];
    protected $dates = ['from_date', 'to_date', 'created_at', 'updated_at'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Users that have viewed a message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
