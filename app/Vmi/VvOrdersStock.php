<?php

namespace App\Vmi;

use Illuminate\Database\Eloquent\Model;

class VvOrdersStock extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'vmi';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vv_orders_stock';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * A company is owned by a depot.
     *
     * @return
     */
    public function stock()
    {
        return $this->belongsTo(VvStock::class, 'stock_id', 'id');
    }
}
