<?php

/*
 * ******************************************
 * XDP Pricing
 *
 * Allows overiding specific methods for carrier
 * ******************************************
 */

namespace App\Pricing\Methods;

use App\Models\DomesticZone;
use App\Models\XdpEas;
use App\Models\CongestionPostcode;

class Xdp extends PricingModel
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

        $this->model = 7;
    }

    public function getZone()
    {
        $domesticZones = new DomesticZone();
        $this->zone = $domesticZones->getZone($this->shipment, $this->model, $this->isReturn());
    }

    public function isOOA()
    {
        $eas = new XdpEas();

        return $eas->isOutOfArea($this->shipment['recipient_postcode']);
    }

    /**
     * Returns true if this is a large package shipment.
     */
    public function isLPSPackage($package)
    {
        return false;
    }

    // Is in a congested postcode
    public function isCON()
    {
        $congestionPostcodes = new CongestionPostcode();

        return $congestionPostcodes->isCongested($this->shipment['recipient_postcode']);
    }
}
