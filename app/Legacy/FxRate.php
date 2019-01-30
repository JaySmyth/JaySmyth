<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FxRate extends Model
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
    protected $table = 'FX_Rates';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /*
     * Local Variables
     */
    private $rate_type = '';
    private $frt_rate = 0;
    private $std_rate = 0;
    private $conv_rate = 0;
    private $b_point;
    private $debug = false;

    public function getRate($table, $pkgType, $zone, $residential, $pieces, $weight, $weight_type = "KGS", $search_dir = 'up')
    {

        /*
          ######################################
          OVERIDES TO PASSED VALUES
          Set as per Sam 22/07/2008
          DO NOT CHANGE !!!!
          ######################################
         */
        
        if ($this->debug)
            echo "Table : $table PkgType : $pkgType Zone : $zone Residential : $residential Pieces : $pieces Weight : $weight<br>";

        switch ($this->gateway) {

            case "FXRS":
                // If Package type == 'Letter' and Package weight > .5kgs, Price as a Pack.
                if ($pkgType == 'Letter' && $weight > .5) {                     // If Letter weight is greater than .5
                    $pkgType = 'Pack';                                          // Reclassify as a Package
                }
                // If Package type == 'Pack'   and Package weight > 2.5kgs Price as a Package.
                if ($pkgType == 'Pack' AND $weight > 2.5) {                     // If Pack weight is greater than 2.5
                    $pkgType = 'Package';                                       // Reclassify as a Package
                }
                break;

            default:
                break;
        }
        /*
          ######################################
          END
          ######################################
         */
        if ($this->debug)
            echo "Table : $table PkgType : $pkgType Zone : $zone Pieces : $pieces Weight : $weight<br>";

        if ($residential == 'Y') {

            // Look for a residential rate
            $rate = FxRate::where('table_no', $table)
                    ->where('p_type', $pkgType)
                    ->where('zone', $zone)
                    ->where('b_point', '>=', $weight)
                    ->where('residential', 'Y')
                    ->where('piece_limit', '>=', $pieces)
                    ->orderBy('b_point')
                    ->orderBy('piece_limit')
                    ->first();

            if ($rate)
                return $rate;
        }

        // Look for a non residential rate
        $rate = FxRate::where('table_no', $table)
                ->where('p_type', $pkgType)
                ->where('zone', $zone)
                ->where('b_point', '>=', $weight)
                ->where('residential', 'N')
                ->where('piece_limit', '>=', $pieces)
                ->orderBy('b_point')
                ->orderBy('piece_limit')
                ->first();

        return $rate;
    }

}
