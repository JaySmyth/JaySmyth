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

    /**
     * Calculate the Freight portion of the price.
     */
    public function calcFreight()
    {

        // Get Package Type
        $this->getPackagingType();
        $packagingType = (empty($this->packagingType)) ? 'Unknown' : $this->packagingType.'(s)';

        $currentRateLine = $this->getRateDetails($this->shipment['company_id'], $this->rate['id'], $this->shipment['service_id'], $this->shipment['recipient_type'], $this->packagingType, $this->shipment['pieces'], $this->chargeableWeight, $this->zone, $this->shipment['ship_date']);
        if ($currentRateLine) {
            $result = ['charge' => 0, 'break_point' => 0];
            $charge = [
                'code' => 'FRT',
                'description' => $this->shipment['pieces'].' '.$packagingType.' to Area '.strtoupper($this->zone),
                'value' => 0
            ];

            /*
             * *********************************************
             * Calc Charges for this segment
             * *********************************************
             */
            $charge = $this->calcSegmentCharge($currentRateLine, $this->chargeableWeight, $charge);
            $this->addSurcharge($charge);
        } else {
            $this->log('No Rate found');
        }
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
