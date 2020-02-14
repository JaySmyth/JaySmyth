<?php

namespace App\Multifreight;

use Illuminate\Database\Eloquent\Model;

class JobHdr extends Model
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
    protected $table = 'job_hdr';

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
     * A job has many lines.
     *
     * @return type
     */
    public function lines()
    {
        return $this->hasMany(JobLine::class, 'job_id', 'job_id');
    }

    /**
     * A job has many collections.
     *
     * @return type
     */
    public function collections()
    {
        return $this->hasMany(JobCol::class, 'job_id', 'job_id');
    }

    /**
     * A job has many collections.
     *
     * @return type
     */
    public function deliveries()
    {
        return $this->hasMany(JobDel::class, 'job_id', 'job_id');
    }

    /**
     * A job has many costs.
     *
     * @return type
     */
    public function costs()
    {
        return $this->hasMany(RecCost::class, 'rec_id', 'job_id')->select('rec_id', 'cost_rate', 'description', 'charge_type');
    }
}
