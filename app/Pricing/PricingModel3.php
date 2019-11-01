<?php

/*
 * ******************************************
 * UPS Domestic Pricing
 * ******************************************
 */

namespace App\Pricing;

use App\UpsEas;
use App\Surcharge;

class PricingModel3 extends PricingModel
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

        $this->maxStdDimension = 122;
        $this->maxStdWeight = 32;
        $this->lowerMaxGirth = 300;
        $this->upperMaxGirth = 400;

        // Calculate Fuel Surcharge on the following charge codes
        $this->fuelChargeCodes = ['BRO', 'COR', 'DISC', 'DTP', 'EAS', 'FRT', 'FUEL', 'ICE', 'LIA', 'LPS', 'MIS', 'OOA', 'OSP', 'OWP', 'RAS', 'RES'];
    }

    public function isRAS()
    {

        // Identify if Surcharge applies and if so what type
        $easType = $this->getEasType("RAS");
        if ($easType == "RAS") {
            return true;
        } else {
            return false;
        }
    }

    public function isEAS()
    {

        // Identify if Surcharge applies and if so what type
        $easType = $this->getEasType("EAS");

        if ($easType == "EAS") {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Returns true if easType passed is the same as the
     * Recipient Postcode easType
     *
     * @return boolean
     */

    public function getEasType($easType)
    {

        // Out of area surcharge based on first part of postcode
        $temp = explode(' ', $this->shipment['recipient_postcode']);
        $postcode = $temp[0];

        // Does an EAS charge apply?
        $upsEas = new UpsEas();
        $eas = $upsEas->getSurcharge($this->shipment['recipient_country_code'], $postcode);

        if ($eas) {
            switch ($eas->destination_surcharge) {
                case "Remote Area Surcharge":
                    return "RAS";
                    break;

                default:
                    // Assume all others are "Extended Area Surcharge":
                    return "EAS";
                    break;
            }
        }

        return false;
    }

    /**
     * Returns true if any piece in the shipment has any dim greater than 120cm
     */
    public function isOSP()
    {
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['length']) && isset($package['width']) && isset($package['height'])) {
                $maxSide = max($package['length'], $package['width'], $package['height']);

                if ($maxSide > $this->maxStdDimension) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if any piece in the shipment has any dim greater than 120cm
     */
    public function isOWP()
    {
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['weight']) && $package['weight'] > $this->maxStdWeight) {
                return true;
            }
        }

        return false;
    }

    public function isPeakSeason()
    {
        /**
        *   4th Nov to 10 Jan
        */
        $startDate = date('Y-m-d', strtotime(date('Y') . "/11/04"));
        $endDate = date('Y-m-d', strtotime(date('Y') + 1 . "/01/10"));
        $currentDate = date('Y-m-d');
        if ($currentDate >= $startDate && $currentDate <= $endDate) {
            return true;
        }

        return false;
    }
}
