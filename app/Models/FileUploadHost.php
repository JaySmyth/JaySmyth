<?php

 namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUploadHost extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * A file upload is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
