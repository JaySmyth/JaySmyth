<?php

/*
 * ******************************************
 * DHL Pricing -    Allows overiding specific
 *                  methods for carrier
 * ******************************************
 */

namespace App\Pricing;

use App\Models\CarrierPackagingType;
use App\Models\Company;
use App\Models\DhlEas;

class PricingModel5 extends PricingModel
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

        $this->maxStdDimension = 120;
        $this->maxStdWeight = 70;
    }

    /**
     * Gets the Packaging Type of the current package
     * and set this->packagingType to the IFS equivalent.
     *
     * @param type $pkgNo
     */
    public function getPackagingType($pkgNo = 0)
    {
        // A Document only shipment can only contain documents for DHL
        if ($this->shipment['packages'][$pkgNo]['packaging_code'] == 'ENV' && $this->shipment['ship_reason'] != 'documents') {
            $this->shipment['packages'][$pkgNo]['packaging_code'] = 'CTN';
        }

        parent::getPackagingType();
    }

    public function isEAS()
    {
        $eas = new DhlEas();
        // Implemented at child level
        $this->log('   Using Country: '.$this->shipment['recipient_country_code']);
        $this->log('   City: '.$this->shipment['recipient_city']);
        $this->log('   Postcode: '.$this->shipment['recipient_postcode']);

        return $eas->isEas($this->shipment['recipient_country_code'], $this->shipment['recipient_city'], $this->shipment['recipient_postcode']);
    }
}
