<?php

/*
 * ******************************************
 * TNT Pricing -    Allows overiding specific
 *                  methods for carrier
 * ******************************************
 */

namespace App\Pricing\Methods;

use App\Models\TntEas;

class Tnt extends PricingModel
{
    /*
     * *************************************
     * Class contains Carrier specific
     * extensions for the PricingModel class
     *
     * Available functions
     *
     *      price($shipment, $rate, $priceType)
     *      getZone()
     *      getPackagingType($pkgNo = 0)
     *      calcChargeable()
     *      getFuelPercentage()
     *      getSurcharges()
     *      doCalcs()
     *      calcFreight()
     *      calcFuel()
     *      getRateDetails()
     *      calcDiscount()
     * *************************************
     */

    public function __construct()
    {
        parent::__construct();
        $this->model = 4;
    }

    /*
     * **********************************
     * Carrier Specific Surcharges.
     * **********************************
     */
    // Extended Area Surcharge
    public function isRAS()
    {
        $eas = new TntEas();
        // Implemented at child level
        return $eas->isEas($this->shipment['recipient_country_code'], $this->shipment['recipient_postcode']);
    }
}
