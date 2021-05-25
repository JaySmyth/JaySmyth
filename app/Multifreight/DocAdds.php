<?php

namespace App\Multifreight;

use Illuminate\Database\Eloquent\Model;

class DocAdds extends Model
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
    protected $table = 'doc_adds';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * A collection belongs to a header.
     *
     * @return type
     */
    public function header()
    {
        return $this->hasOne(JobHdr::class, 'job_id', 'job_id')->select('job_disp', 'transport_type', 'product_desc', 'sysuser', 'cod_amount', 'job_dept');
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

    /**
     * Update the dirty flag.
     */
    public function clean()
    {
        $this->dirty = 0;
        $this->save();
    }
}
