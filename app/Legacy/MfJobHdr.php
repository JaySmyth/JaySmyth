<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class MfJobHdr extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mf_job_hdr';

    /**
     * A job has many costs.
     * 
     * @return type
     */
    public function costs()
    {
        return $this->hasMany(MfRecCost::class, 'rec_id', 'job_id')->select('rec_id', 'cost_rate', 'description', 'charge_type');
    }

}
