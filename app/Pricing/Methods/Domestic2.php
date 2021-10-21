<?php

/*
 * ******************************************
 * TNT Pricing -    Allows overiding specific
 *                  methods for carrier
 * ******************************************
 */

namespace App\Pricing\Methods;

use App\Models\Carrier;
use App\Models\CarrierPackagingType;
use App\Models\Company;
use App\Models\DomesticRate;
use App\Models\DomesticZone;

class Domestic2 extends PricingModel
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
        $this->pricingZones = new DomesticZone;
        $this->fedexEas = new \App\Models\FedexEas();
        $this->model = 'dx';
    }

    public function getZone()
    {

        $this->zone = 0;
        $this->costsRequired = 'Y';
        $this->model = Carrier::find($this->shipment['carrier_id'])->code ?? 'dx';

        // Check for NI service
        if (substr(strtoupper($this->shipment['recipient_postcode']), 0, 2) == 'BT') {
            $this->zone = 'ni';
            $this->costsRequired = 'N';
            return;
        }
        // Check for ROI service
        if (strtoupper($this->shipment['recipient_country_code']) == 'IE') {
            $this->zone = 'ie';
            $this->costsRequired = 'N';

            return;
        }

        // Get any other zones
        if ($this->rate['id'] == 1042) {
            $this->zone = $this->pricingZones->getZone($this->shipment, 'fedex', $this->isReturn());
        } else {
            $this->zone = $this->pricingZones->getZone($this->shipment, $this->model, $this->isReturn());
        }

        // $this->response['errors'][] = 'Unknown Service';

        return;
    }


    /*
     * **********************************
     * Carrier Specific Surcharges.
     * **********************************
     */

}
