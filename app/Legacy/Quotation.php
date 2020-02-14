<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
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
    protected $table = 'Quotations';
}
