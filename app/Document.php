<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /*
     * Mass assignable.
     */

    protected $fillable = ['filename', 'document_type', 'description', 'path', 'type', 'size', 'public_url', 'user_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Set the description.
     *
     * @param  string  $value
     * @return string
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst(strtolower($value));
    }

    /**
     * Returns the user's name who uploaded the document.
     *
     * @return string or null
     */
    public function getUserNameAttribute()
    {
        $user = User::find($this->user_id);

        if ($user) {
            return $user->name;
        }
    }
}
