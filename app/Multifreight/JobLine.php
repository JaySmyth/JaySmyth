<?php

namespace App\Multifreight;

use Illuminate\Database\Eloquent\Model;

class JobLine extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'multifreight';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_line';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * A line belongs to a job header.
     *
     * @return type
     */
    public function header()
    {
        return $this->belongsTo(JobHdr::class, 'job_id', 'job_id')->select('job_disp');
    }

    /**
     * Get the scs job number.
     *
     * @return type
     */
    public function getScsJobNumberAttribute()
    {
        return optional($this->header)->job_disp;
    }
}
