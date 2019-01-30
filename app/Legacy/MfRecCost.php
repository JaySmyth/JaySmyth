<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class MfRecCost extends Model
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
    protected $table = 'mf_rec_cost';

}
