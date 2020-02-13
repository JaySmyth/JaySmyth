<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentUpload extends Model
{
    use Logable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_upload', 'created_at', 'updated_at'];

    /**
     * A shipment upload is owned by an import configuration.
     *
     * @return
     */
    public function importConfig()
    {
        return $this->belongsTo(ImportConfig::class)->with('company');
    }

    public function incrementTotalProcessed()
    {
        $this->total_processed++;
        $this->save();
    }

    /**
     * Ensure the directory has an opening and closing slash.
     *
     * @return string
     */
    public function setDirectoryAttribute($value)
    {
        if (substr($value, 0, 1) != '/') {
            $value = '/'.$value;
        }

        if (substr($value, -1) != '/') {
            $value .= '/';
        }

        $this->attributes['directory'] = strtolower($value);
    }
}
