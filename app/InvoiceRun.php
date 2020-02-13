<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceRun extends Model
{
    public $timestamps = false;
    public $status;

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_run'];

    /**
     * An invoice run has many shipments.
     *
     * @return
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * @return type
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return type
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return type
     */
    public function getTotalShipmentsAttribute()
    {
        return $this->shipments->count();
    }

    /**
     * @return type
     */
    public function getTotalSalesAttribute()
    {
        return $this->shipments->sum('shipping_charge');
    }

    /**
     * @return type
     */
    public function getTotalCostsAttribute()
    {
        return $this->shipments->sum('shipping_cost');
    }

    /**
     * Return the difference.
     *
     * @return type
     */
    public function getDifferenceAttribute()
    {
        return abs($this->total_sales - $this->total_costs);
    }

    /**
     * Return the difference.
     *
     * @return type
     */
    public function getDifferenceFormattedAttribute()
    {
        if ($this->total_costs > $this->total_sales) {
            return '-'.number_format($this->difference, 2);
        } elseif ($this->total_sales > $this->total_costs) {
            return '+'.number_format($this->difference, 2);
        } else {
            return number_format($this->difference, 2);
        }
    }
}
