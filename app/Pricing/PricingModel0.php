<?php

/*
 * ******************************************
 * Default Generic Pricing
 * 
 * Allows overiding specific methods for carrier
 * ******************************************
 */

namespace App\Pricing;

class PricingModel0 extends PricingModel
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

    public function calcSurcharge($code, $packages = 0)
    {

        if ($packages == 0) {
            $packages = $this->shipment['pieces'];
        }

        $this->surchargeDetails = $this->getSurcharge($code);

        if ($this->surchargeId > 0 && isset($this->surchargeDetails->name)) {
            $charge['code'] = $code;
            $charge['description'] = $this->surchargeDetails->name;
            $charge['value'] = 0;
            $charge['value'] = $this->surchargeDetails->consignment_rate;
            $charge['value'] += $this->chargeableWeight * $this->surchargeDetails->weight_rate;
            $charge['value'] += $packages * $this->surchargeDetails->package_rate;

            if ($charge['value'] < $this->surchargeDetails->min) {
                $charge['value'] = $this->surchargeDetails->min;
            }

            /*
             * *************************************
             *  USG Residential Override
             * *************************************
             */

            // Is a USG/ USDS Service
            if (in_array($this->service->id, [7, 39])) {

                // Applies to two zones only
                if (in_array($this->zone, [9, 17])) {
                    $charge['value'] = 30;
                }
            }

            $this->addSurcharge($charge);
        } else {
            $this->surcharge = null;
        }
    }

}

?>