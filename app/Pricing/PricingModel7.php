<?php

/*
 * ******************************************
 * XDP Pricing
 *
 * Allows overiding specific methods for carrier
 * ******************************************
 */

namespace App\Pricing;

use App\Models\DomesticZone;

class PricingModel7 extends PricingModel
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
    }

    public function getZone()
    {
        $domesticZones = new DomesticZone();
        $this->zone = $domesticZones->getZone($this->shipment, $this->rate['model']);
    }
}
