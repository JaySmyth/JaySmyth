<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class UpsEas extends Model
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
    protected $table = 'UPS_EAS';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    public function checkEas($countryCode, $postcode)
    {

        // Look to see if an EAS applies
        for ($i = strlen($postcode); $i >= 2; $i--) {

            $eas = $this->where('country_code', strtoupper($countryCode))
                    ->where('postal_low', '<=', substr($postcode, 0, $i))
                    ->where('postal_high', '>=', substr($postcode, 0, $i))
                    ->first();

            if ($eas)
                return $eas;
        }
    }

    public function calcCostOaa($countryCode, $postcode, $chargeableWeight)
    {

        $charge = 0;
        $eas = $this->checkEas($countryCode, $postcode);

        if ($eas) {
            $charge = $this->chargeableWeight * .28;
            if ($charge < 14.85) {
                $charge = 14.85;
            }
        }

        return $charge;
    }

    public function calcSalesOaa($countryCode, $postcode, $chargeableWeight)
    {

        $charge = 0;
        $eas = $this->checkEas($countryCode, $postcode);

        if ($eas) {
            $charge = $this->chargeableWeight * .38;
            if ($charge < 20.00) {
                $charge = 20.00;
            }
        }

        return $charge;
    }

}
