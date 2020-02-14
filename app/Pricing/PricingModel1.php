<?php

/*
 * ******************************************
 * Domestic Pricing
 * ******************************************
 */

namespace App\Pricing;

use App\CarrierPackagingType;
use App\Company;
use App\DomesticRate;
use App\DomesticZone;
use App\FedexEas;

class PricingModel1 extends PricingModel
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
    public $maxStdDimension = 150;

    public function __construct()
    {
        parent::__construct();
        $this->pricingZones = new DomesticZone;
    }

    public function getZone()
    {
        $domesticZones = new DomesticZone();
        switch (strtolower($this->shipment['service_code'])) {

            case 'uk24':
            case 'uk24r':
            case 'uk48':
            case 'uk48r':
            case 'uk48p':
                $this->zone = $domesticZones->getZone($this->shipment);
                $this->costsRequired = 'Y';
                break;

            case 'ni48':
            case 'ni24':
            case 'ni48p':
            case 'ni24p':
                if (strtoupper(substr($this->shipment['recipient_postcode'], 0, 2)) == 'BT') {
                    $this->zone = 'ni';
                    $this->costsRequired = 'N';
                }
                break;
            case 'ie24':
            case 'ie48':
            case 'ie48p':
                if (strtoupper($this->shipment['recipient_country_code']) == 'IE') {
                    $this->zone = 'ie';
                    $this->costsRequired = '0';
                }
                break;

            default:
                $this->zone = 0;
                $requireCosts = 'Y';
                $this->response['errors'][] = 'Unknown Service';
        }
    }

    public function getPackageRate($packageType)
    {
        $domesticRate = new DomesticRate();
        $domesticRate->debug = $this->debug;

        // Check for rate
        $rateDetail = $domesticRate->getRate(
            $this->shipment['company_id'],
            $this->rate['id'],
            $this->shipment['service_id'],
            $this->shipment['ship_date'],
            $packageType,
            $this->zone
        );

        if ($rateDetail) {
            $this->rateDetail = $rateDetail;
        } else {
            // Create error response
            $this->response['errors'][] = 'No '.$this->priceType.' rate/ current rate found';
            $this->response['errors'][] = 'Rate Id : '.$this->rate['id']
                    .' Company Id : '.$this->shipment['company_id']
                    .' Service Code : '.$this->shipment['service_code']
                    .' Packaging : '.$packageType
                    .' Zone : '.$this->zone
                    .' Pieces : '.$this->shipment['pieces']
                    .' Weight : '.$this->chargeableWeight;
        }
    }

    public function calcFreight()
    {
        $okToPrice = true;
        $shipmentSummary = $this->buildPackageSummary();

        $this->log('*** Domestic Shipment ***');
        // Calc charge for each piece as they may be of different types
        foreach ($shipmentSummary as $packageCode => $packageSummary) {
            $pieces = $packageSummary['pieces'];
            $chargeableWeight = $packageSummary['weight'];

            $this->setPackageType($packageCode);

            $this->log($pieces.' x '.$this->packagingType.' '.$chargeableWeight.' kgs Charged');

            $charge['code'] = 'FRT';
            $charge['description'] = "$pieces $this->packagingType(s) to Area ".strtoupper($this->zone);
            $charge['value'] = 0;

            // Get Rate for this package
            $this->getPackageRate($this->packagingType);

            if (isset($this->rateDetail->first) && isset($this->rateDetail->others) && isset($this->rateDetail->notional_weight)) {

                // Calc Charge for 1st piece
                if ($this->rateDetail->first > 0) {
                    $charge['value'] = round($this->rateDetail->first, 2);
                    $this->log('First Piece: '.round($this->rateDetail->first, 2));
                }

                // Calc charge for any additional pieces
                if ($pieces > 1) {
                    $charge['value'] += round(($pieces - 1) * round($this->rateDetail->others, 2), 2);
                    $this->log('Subs Pieces: '.round(($pieces - 1) * round($this->rateDetail->others, 2), 2));
                }

                // Rate has notional package charge so check to see if it applies
                if (isset($this->rateDetail->notional_weight) && $this->rateDetail->notional_weight > 0) {

                    // Calculate how many notional packages there are and price accordingly
                    $notionalPackages = ceil($chargeableWeight / round($this->rateDetail->notional_weight, 2)) - $pieces;
                    if ($notionalPackages > 0) {
                        if ($this->rateDetail->notional > 0) {

                            // Price notional packages
                            $charge['value'] += round($notionalPackages * round($this->rateDetail->notional, 2), 2);
                            $this->log('Notional: '.round($notionalPackages * round($this->rateDetail->notional, 2), 2));
                        } // else {

                            // Notional rate missing, so error out unless company_id == 550
                            // if ($this->shipment['company_id'] != 550) {
                            //     $okToPrice = false;
                            //     $this->log("Error - No Notional rate defined");
                            // }
                        // }
                    }
                }
            }

            // If charge is non zero then add
            if ($charge['value'] != 0 && $okToPrice) {
                $this->addSurcharge($charge);
            }
        }
    }

    public function setPackageType($packageCode)
    {

        // Firstly account for System Reserved packaging codes
        switch (strtoupper($packageCode)) {

            case 'ENV':
                $this->packagingType = 'Letter';
                break;

            case 'PAK':
                $this->packagingType = 'Pack';
                break;

            case 'CTN':
                $this->packagingType = 'Package';
                break;

            default:

                // Otherwise work it out
                $this->getPackageType($packageCode);
                break;
        }
    }

    public function buildPackageSummary()
    {

        // Sumarize packages by packaging_code
        foreach ($this->shipment['packages'] as $package) {

            // Calculate Volumetric weight
            $volumetricWeight = calcVolume($package['length'], $package['width'], $package['height']);
            $volumetricWeight = ceil($volumetricWeight * 2) / 2;

            // Calculate package chargeable weight
            $chargeableWeight = ($package['weight'] >= $volumetricWeight) ? $package['weight'] : $volumetricWeight;

            // Add package to summary
            if (isset($summary[$package['packaging_code']])) {
                $summary[$package['packaging_code']]['pieces']++;
                $summary[$package['packaging_code']]['weight'] += $chargeableWeight;
            } else {
                $summary[$package['packaging_code']]['pieces'] = 1;
                $summary[$package['packaging_code']]['weight'] = $chargeableWeight;
            }
        }

        return $summary;
    }

    /**
     * Tries to work out what packaging type to use
     * for the rate tables.
     *
     * @param type $packageCode
     */
    public function getPackageType($packageCode)
    {

        // Default to packageCode
        $this->packagingType = $packageCode;

        // Get Package Type details
        $packageType = Company::find($this->shipment['company_id'])
                ->getPackagingTypes($this->shipment['mode_id'])
                ->where('code', $packageCode)
                ->first();

        if ($packageType) {

            /*
             * *********************************************************
             * If calculating Costs or a Default packaging type has been
             * selected (company_id = 0). Then change packagine type to
             * the code used by the carrier in their pricing Tariffs
             *
             * e.g. CTN -> Package
             *
             * Otherwise use the custom packaging code provided.
             * *********************************************************
             */
            if ($this->priceType == 'Costs' || $packageType->company_id == 0) {
                $carrierPackageType = CarrierPackagingType::where('packaging_type_id', $packageType->packaging_type_id)
                        ->where('carrier_id', $this->shipment['carrier_id'])
                        ->first();

                if ($carrierPackageType) {
                    $this->packagingType = $carrierPackageType->rate_code;
                }
            }
        }
    }

    public function doCalcs()
    {

        /*
         * ***************************************
         * Calculate Freight Cost
         * ***************************************
         */

        // If we do not require costs
        if ($this->priceType == 'costs' && $this->costsRequired == 'N') {
            return;
        }

        // Calculate Freight charge
        $this->calcFreight();

        // Calculate Discount amount
        $this->calcDiscount();

        // Calculate Fuel Surcharge
        $this->calcFuel();

        // Calculate any other surcharges
        $this->calcSurcharges();
    }

    public function isRES()
    {

        /*
         * *************************************
         * Add Fedex Out of Area for UK48 only
         * *************************************
         */

        if (! in_array($this->shipment['service_code'], ['ni24', 'ni48', 'ie24', 'ie48', 'uk48'])) {
            if ($this->shipment['recipient_type'] == 'r') {
                return true;
            }
        }

        return false;
    }

    public function isLPS()
    {
        $serviceCodes = ['uk48', 'uk48r'];
        if (in_array(strtolower($this->shipment['service_code']), $serviceCodes)) {
            return false;
        }
    }

    /**
     * Returns true if any piece in the shipment has any dim greater than 120cm.
     */
    public function isOSP()
    {
        $serviceCodes = ['uk48', 'uk48r'];
        if (in_array(strtolower($this->shipment['service_code']), $serviceCodes)) {
            return false;
        }

        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['length']) && isset($package['width']) && isset($package['height'])) {
                $maxSide = max($package['length'], $package['width'], $package['height']);
                if ($maxSide > $this->maxStdDimension) {
                    return true;
                }
            }
        }
    }

    /**
     * Returns true if any piece in the shipment has a weight in excess of 69.5 kg.
     */
    public function isOWP()
    {
        $serviceCodes = ['uk48', 'uk48r'];
        if (in_array(strtolower($this->shipment['service_code']), $serviceCodes)) {
            return false;
        }

        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['weight']) && isset($package['volumetric_weight'])) {
                if ($package['weight'] > 69.50 || $package['volumetric_weight'] > 69.50) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if shipment attracts a collection charge.
     */
    public function isCOL()
    {

        // Shipped from the UK mainland
        if (strtolower($this->shipment['service_code']) == 'uk48r') {
            return true;
        }

        return false;
    }

    public function isEAS()
    {
        $surcharge = new \App\FedexEas();
        if (strtoupper($surcharge->getSurcharge($this->shipment['recipient_postcode'])) == 'EAS') {
            return true;
        }

        return false;
    }

    public function isRAS()
    {
        $surcharge = new \App\FedexEas();
        if (strtoupper($surcharge->getSurcharge($this->shipment['recipient_postcode'])) == 'RAS') {
            return true;
        }

        return false;
    }

    public function isOOA()
    {
        $surcharge = new \App\FedexEas();
        if (strtoupper($surcharge->getSurcharge($this->shipment['recipient_postcode'])) == 'OOA') {
            return true;
        }

        return false;
    }
}
