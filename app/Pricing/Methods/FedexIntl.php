<?php

/*
 * ******************************************
 * Fedex International Pricing
 * ******************************************
 */

namespace App\Pricing\Methods;

class FedexIntl extends PricingModel
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

        // Calculate Fuel Surcharge on the following charge codes
        $this->fuelChargeCodes = ['DISC', 'FRT'];
        $this->model = 2;
    }

    /*
     * **********************************
     * Carrier Specific Surcharges
     * **********************************
     */

    public function isOWP()
    {
        return false;
    }

    public function isOSP()
    {
        return false;
    }

    public function isLPS()
    {
        return false;
    }

    public function isRES()
    {
        return false;
    }
}
