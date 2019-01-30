<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrierChargeCode extends Model
{

    public $timestamps = false;

    /**
     * A charge belongs to a carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            $filter = trim($filter);
            return $query->where('code', 'LIKE', '%' . $filter . '%')
                            ->orWhere('description', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope SCS code.
     *
     * @return
     */
    public function scopeHasScsCode($query, $scsCode)
    {
        if ($scsCode) {
            return $query->where('scs_code', $scsCode);
        }
    }

    /**
     * Scope carrier.
     *
     * @return
     */
    public function scopeHasCarrier($query, $carrierId)
    {
        if (is_numeric($carrierId)) {
            return $query->where('carrier_id', $carrierId);
        }
    }

}
