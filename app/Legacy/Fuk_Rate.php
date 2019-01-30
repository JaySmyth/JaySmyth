<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class Fuk_Rate extends Model
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
    protected $table = 'FUK_Rates';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
    
    public function getRate($tableNo, $packagingType, $zone, $pieces, $chargeableWeight, $shipDate) {
        
        return $rate = Fuk_Rate::where('table_no', $tableNo)
                ->where('p_type', $packagingType)
                ->where('zone', $zone)
                ->where('b_point', '>=', $chargeableWeight)
                ->where('piece_limit', '>=', $pieces)
                ->where('valid_from', '<=', $shipDate)
                ->where('valid_to', '>=', $shipDate)
                ->orderBy('b_point')
                ->orderBy('piece_limit')
                ->first();
    }
}
