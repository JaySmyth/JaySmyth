<?php

/*
 * ******************************************
 * Default Generic Pricing
 * 
 * Allows overiding specific methods for carrier
 * ******************************************
 */

namespace App\Pricing;

class PricingModel0 extends PricingModel {
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
    }

}

?>