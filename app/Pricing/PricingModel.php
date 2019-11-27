<?php

namespace App\Pricing;

use App\PricingZones;
use App\Countries;
use App\FuelSurcharge;
use App\Rate;
use App\RateDetail;
use App\Company;
use App\Service;
use App\CarrierPackagingType;
use App\Surcharge;
use App\SurchargeDetail;

class PricingModel
{
    public $debug = false;
    public $shipment;
    public $service;
    public $zone;
    public $rate;
    public $fuelPercentage;
    public $fuelCap;
    public $discount;
    public $chargeableWeight;
    public $packagingType;
    public $costsRequired;
    public $priceType;
    public $response = array();
    public $models;
    public $model;
    public $surcharge;
    public $surchargeId;
    public $rateDetail;
    public $lowerMaxWeight;
    public $upperMaxWeight;
    public $maxDim1;
    public $maxDim2;
    public $maxDim3;
    public $upperMaxDim;
    public $lowerMaxGirth;
    public $upperMaxGirth;
    public $fuelChargeCodes;
    public $log = [];
    //
    public $maxStdDimension;                                                    // Largest package dimension not to have a surcharge applied
    public $maxStdWeight;                                                       // Largest package weight not to have a surcharge applied
    private $weightConv = [];
    private $dimConv = [];

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
     *      getSurcharge()
     *      doCalcs()
     *      calcFreight()
     *      calcFuel()
     *      getRateDetails()
     *      calcDiscount()
     * *************************************
     */

    public function log($msg)
    {
        $this->log[] = $msg . " - " . date('H:i:s');
    }

    public function __construct($debug = false)
    {
        $this->debug = $debug;

        if ($this->debug) {
            $this->log("**** Logging On ****");
        }
        /*
         * *****************************************************
         * Note : Pricing Model number is the carrier_id of the
         * pricing model that the tarrif is built around not the
         * Shipment carrier_id, although in most cases these
         * will be the same.
         *
         * eg Shipment Carrier   Pricing Model   Model Number
         *         Fedex           Fedex          (2) Fedex
         *          UPS             UPS            (3) UPS
         *          UPS            Fedex          (2) Fedex
         *
         * The model number determines the Fuel surcharge % and
         * Pricing Zones used.
         * *****************************************************
         */
        $this->response['errors'] = [];

        $this->models = [
            'std' => '0',
            'domestic' => '1',
            'fedex' => '2',
            'ups' => '3',
            'tnt' => '4',
            'dhl' => '5',
            'cwide' => '6',
        ];

        // Calculate Fuel Surcharge on the following charge codes
        $this->fuelChargeCodes = ['ADH', 'BRO', 'COR', 'DISC', 'DTP', 'EAS', 'FRT', 'FUEL', 'ICE', 'LIA', 'LPS', 'MIS', 'OOA', 'OSP', 'OWP', 'RAS', 'RES'];

        $this->lowerMaxWeight = 9999;       // ADH & MAX
        $this->upperMaxWeight = 9999;       // ADH & MAX
        $this->maxDim1 = 9999;              // ADH & MAX
        $this->maxDim2 = 9999;              // ADH & MAX
        $this->maxDim3 = 9999;              // ADH & MAX
        $this->upperMaxDim = 9999;          // MAX
        //
        $this->maxStdDimension = 122;
        $this->maxStdWeight = 69.50;
        $this->lowerMaxGirth = 330;         // LPS & MAX
        $this->upperMaxGirth = 419;         // LPS & MAX

        $this->weightConv = [
            'lb' => [
                'kg' => 0.453592,
                'lb' => 1
            ],
            'kg' => [
                'lb' => 2.20462,
                'kg' => 1
            ]
        ];

        $this->dimConv = [
            'in' => [
                'lb' => 1,
                'kg' => 0.45359237
            ],
            'cm' => [
                'lb' => 2.2046333,
                'kg' => 1
            ]
        ];
    }

    /**
     * Accepts a shipment, rate and price type (Cost/ Sales)
     * and calculates the appropriate price
     *
     * @param array $shipment
     * @param array $rate
     * @param array $priceType
     *
     * @return array Price breakdown
     */
    public function price($shipment, $rate, $priceType)
    {
        $this->response['debug'] = [];

        // Save Shipment details
        $this->shipment = $shipment;
        $this->priceType = $priceType;
        $this->service = Service::find($shipment['service_id']);

        $this->log("PriceType: $priceType");
        $this->log("ServiceId: ".$shipment['service_id']);

        // Save Rate Header
        $this->rate = $rate;
        if (empty($rate)) {
            $this->log('Rate not defined');
            $this->response['errors'][] = "Unable to identify " . $this->priceType . " Rate";
        } else {
            $this->log("Using Rate: ".$this->rate['id'] . ' - '.$this->rate['description']);
            $this->model = $this->models[$this->rate['model']];

            // Get Pricing zone
            $this->getZone();

            // Calc Chargable weight
            $this->calcChargeable();

            // Get Fuel Surcharge Percentage
            $this->getFuelPercentage();

            // If No Errors
            if ($this->response['errors'] == []) {

                // Pull everything together and return
                $this->doCalcs();

                $this->response['zone'] = $this->zone;
                $this->response['model'] = $this->rate['model'];
                $this->response['rate_id'] = $this->rate['id'];
                $this->response['packaging'] = $this->packagingType;
            } else {
                $this->log("*** Failed with errors");
            }
        }
        $this->response['log'] = $this->log;

        return $this->response;
    }

    /**
     * Identify the correct pricing zone
     *
     * @return type
     */
    public function getZone()
    {
        $pricingZones = new PricingZones();

        // Get appropriate zone for the rate model
        $this->log('Find Zone using');
        $this->log('company_id: ' . $this->shipment['company_id']);
        $this->log('   sender_country_code: ' . $this->shipment['sender_country_code']);
        $this->log('   sender_postcode: ' . $this->shipment['sender_postcode']);
        $this->log('   service_code : ' . $this->shipment['service_code']);
        $this->log('   recipient_country_code: ' . $this->shipment['recipient_country_code']);
        $this->log('   recipient_postcode: ' . $this->shipment['recipient_postcode']);
        $this->log('   model: ' . $this->model);
        $zone = $pricingZones->getZone($this->shipment, $this->models[$this->rate['model']]);
        $zoneType = ($this->priceType == 'Sales') ? 'sale_zone' : 'cost_zone';

        if (empty($zone->$zoneType)) {
            $error = "Unable to identify " . $this->priceType . " Zone";
            $this->log($error);
            $this->response['errors'][] = $error;
            return;
        }

        if ($this->priceType == 'Sales') {
            $this->zone = (isset($zone->sale_zone)) ? $zone->sale_zone : null;
        } else {
            $this->zone = (isset($zone->cost_zone)) ? $zone->cost_zone : null;
        }
        $this->log("Using ".$this->priceType." Zone: ".$this->zone);
    }

    /**
     * Gets the Packaging Type of the current package
     * and set this->packagingType to the IFS equivalent
     *
     * @param type $pkgNo
     */
    public function getPackagingType($pkgNo = 0)
    {

        // Temporary fix to treat "BOX" as "CTN" for pricing purposes
        // May be removed when legacy system disabled
        if ($this->shipment['packages'][$pkgNo]['packaging_code'] == "BOX") {
            $this->shipment['packages'][$pkgNo]['packaging_code'] = "CTN";
        }

        // Identify Shipment Package type for pricing
        $packageType = Company::find($this->shipment['company_id'])
                ->getPackagingTypes($this->shipment['mode_id'])
                ->where('code', $this->shipment['packages'][$pkgNo]['packaging_code'])
                ->first();

        if ($packageType) {
            $carrierPackagingCode = CarrierPackagingType::where('packaging_type_id', $packageType->packaging_type_id)
                    ->where('carrier_id', $this->shipment['carrier_id'])
                    ->first();

            if ($carrierPackagingCode) {
                $this->packagingType = $carrierPackagingCode->rate_code;
            } else {
                $this->packagingType = $this->shipment['packages'][$pkgNo]['packaging_code'];
            }
        } else {
            $this->packagingType = $this->shipment['packages'][$pkgNo]['packaging_code'];
        }

        $this->log("Packaging Type: ".$this->packagingType);
    }

    /**
     * Calculates the Chargeable weight
     *
     * sets $this->chargeableWeight
     */
    public function calcChargeable()
    {
        if (!isset($this->shipment['weight'])) {
            $this->shipment['weight'] = 0;
        }

        $this->calcChargeableWeights();

        $this->chargeableWeight = max($this->shipment['weight'], $this->shipment['volumetric_weight']);
        $this->log("Act Wgt: ".$this->shipment['weight']);
        $this->log("Vol Wgt: ".$this->shipment['volumetric_weight']);
        $this->log("Chargeable: ".$this->chargeableWeight);

        if ($this->chargeableWeight == 0) {
            $this->response['errors'][] = 'Chargable weight must be greater than 0';
        }
    }

    public function calcChargeableWeights()
    {
        $this->shipment['weight'] = 0;
        $this->shipment['volumetric_weight'] = 0;
        /*
         * **********************************
         * Loop through packages totaling up
         * weight and volumetric_weight
         * **********************************
         */
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['length']) && isset($package['width']) && isset($package['height'])) {

                // If LPS shipment weight & vol must be least 40kgs otherwise no minimum
                $wgRate = $this->weightConv[$this->shipment['weight_uom']][$this->rate['weight_units']];
                $dimRate = $this->dimConv[$this->shipment['dims_uom']][$this->rate['weight_units']];

                if ($this->isLPSPackage($package)) {
                    $this->log("LPS Package so weight min 40kgs");
                    $minPkgWeight = 40 * $wgRate;
                } else {
                    $this->log("Not LPS Package so no min weight");
                    $minPkgWeight = 0;
                }

                $this->log("Vol Divisor for rate: ".$this->rate["volumetric_divisor"]);
                $pkgVolWt = calcVolume(
                    $package['length'],
                    $package['width'],
                    $package['height'],
                    $this->rate["volumetric_divisor"]
                );

                // Convert weight into same unit as the rate sheet
                $pkgVolWt = $pkgVolWt * $dimRate;
                $this->log("Pkg Vol Weight in rate units: ".$pkgVolWt);

                $this->shipment['weight'] += max($minPkgWeight, $package['weight'] * $wgRate);
                $this->shipment['volumetric_weight'] += max($minPkgWeight, $pkgVolWt);
                $this->log("Package Weight: ".$this->shipment['weight']);
                $this->log("Package Volume: ".$this->shipment['volumetric_weight']);
            } else {

                // If Dims not present stop calculation
                break;
            }
        }
    }

    /**
     * Gets the fuel % for the specified Pricing Model
     * and sets $this->fuelPercentage
     */
    public function getFuelPercentage()
    {
        $surcharge = new FuelSurcharge();
        $fuelPercentage = $surcharge->getFuelPercentage($this->shipment['carrier_id'], $this->shipment['service_code'], $this->shipment['ship_date']);

        if (empty($fuelPercentage)) {
            $this->fuelPercentage = 0;
            $error = 'Unable to determine Fuel surcharge. Carrier :' . $this->shipment['carrier_id']
                    . " Service :" . $this->shipment['service_code']
                    . " Date :" . $this->shipment['ship_date'];
            $this->response['errors'][] = $error;
            $this->log($error);
        } else {
            $this->fuelPercentage = $fuelPercentage->fuel_percent;
            $this->log("Fuel%: ".$fuelPercentage->fuel_percent);
        }
    }

    public function getSurcharge($chargeType, $basedOn = "service")
    {
        $this->log("Surcharge based on Service");
        $surchargeDetails = [];
        if ($basedOn == "service") {

            // Identify surcharge from service
            $this->surchargeId = ($this->priceType == 'Sales') ? $this->service->sales_surcharge_id : $this->service->costs_surcharge_id;
        } else {

            // Identify surcharge from Rate
            // *** Not Yet Implemented ***
            $this->surchargeId = $this->rate['surcharge_id'];
        }

        if ($this->surchargeId > 0) {
            $this->surcharge = Surcharge::find($this->surchargeId);
            $surchargeDetails = $this->surcharge->getCharges($this->surchargeId, $chargeType, $this->shipment['company_id'], date('Y-m-d', strtotime($this->shipment['ship_date'])));
        } else {
            $this->log("No Surcharge found");
        }

        if (isset($surchargeDetails[0])) {
            $this->log("Surcharge ".$surchargeDetails[0]->name. " Found");
            return $surchargeDetails[0];
        } else {
            if (isset($surchargeDetails->name)) {
                $this->log("Surcharge ".$surchargeDetails->name. " Found");
            }
            return $surchargeDetails;
        }
    }

    public function calcSurcharge($code, $packages = 0)
    {
        $this->surchargeDetails = $this->getSurcharge($code);
        $description = (isset($this->surchargeDetails->name)) ? $this->surchargeDetails->name : "Surcharge";
        if ($packages == 0) {
            $packages = $this->shipment['pieces'];
        } else {
            $description .= " ($packages Packages)";
        }

        if ($this->surchargeId > 0 && isset($this->surchargeDetails->name)) {
            $charge['code'] = $code;
            $charge['description'] = $description;
            $charge['value'] = 0;
            $charge['value'] = $this->surchargeDetails->consignment_rate;
            $charge['value'] += $this->chargeableWeight * $this->surchargeDetails->weight_rate;
            $charge['value'] += $packages * $this->surchargeDetails->package_rate;

            if ($charge['value'] < $this->surchargeDetails->min) {
                $charge['value'] = $this->surchargeDetails->min;
            }
            $this->log(
                "Surcharge $code : Cons val ".$this->surchargeDetails->consignment_rate
                ." + wght val ".$this->chargeableWeight * $this->surchargeDetails->weight_rate
                ." + pkg val ". $packages * $this->surchargeDetails->package_rate
            );
            $this->addSurcharge($charge);
        } else {
            $this->log("No surcharge applicable");
            $this->surcharge = null;
        }
    }

    /**
     * If shipment is to a residential address and a
     * Residential surcharge has been set for the rate
     * Then it is added to the pricing Response
     */
    public function calcSurcharges()
    {
        $this->calcStdSurcharges();

        // Large Package Shipment (Overrides OSP and OWP)
        if ($this->isLPS()) {
            $i = $this->countLPSPackages();
            $this->log("$i LPS Packages found");
            $this->calcSurcharge('LPS', $i);
            if ($this->isPeakSeason()) {
                $this->log("Add Peak Season PSS");
                $this->calcSurcharge('PLP', $i);
            }
        } else {

            // Oversize Piece
            if ($this->isOSP()) {
                $this->log("OSP Packages found");
                $this->calcSurcharge('OSP');
                if ($this->isPeakSeason()) {
                    $this->log("Add Peak Season PSS");
                    $i = $this->countOSPPackages();
                    $this->calcSurcharge('PAH', $i);
                }
            } else {

                // OverWeight Piece
                if ($this->isOWP()) {
                    $this->log("OWP Packages found");
                    $this->calcSurcharge('OWP');
                    if ($this->isPeakSeason()) {
                        $this->log("Add Peak Season PSS");
                        $i = $this->countOWPPackages();
                        $this->calcSurcharge('PAH', $i);
                    }
                }
            }
        }
    }

    public function calcStdSurcharges()
    {

        // Domestic Surcharge Codes
        $surchargeCodes = 'ADH,COL,EAS,MAX,RAS,OOA,RES';

        // If Intl shipment add additional Intl only Surcharge codes
        if (!isDomestic($this->shipment['sender_country_code'], $this->shipment['recipient_country_code'])) {
            $surchargeCodes .= ',ADG,EQT,IDG,ICE,DTP,BRO';
        }

        $surcharges = explode(',', $surchargeCodes);
        foreach ($surcharges as $code) {
            $this->log("Check for $code Surcharge");
            $function = "is$code";
            if ($this->$function()) {
                $this->log("Potential $code Surcharge Found");
                $this->calcSurcharge($code);
            } else {
                $this->log("Potential $code Surcharge not Found");
            }
        }
    }

    // Additional Handling
    public function isADH()
    {
        return false;
    }

    // Address Correction
    public function isCOR()
    {
        return false;
    }

    // Accessible DG
    public function isADG()
    {
        if (isset($this->shipment['hazardous']) && in_array($this->shipment['hazardous'], ['1', '2', '3', '4', '5', '6', '8'])) {
            return true;
        }

        return false;
    }

    // Excepted Quantities DG
    public function isEQT()
    {
        if (isset($this->shipment['hazardous']) && in_array($this->shipment['hazardous'], ['E'])) {
            return true;
        }


        return false;
    }

    // Inaccessable DG
    public function isIDG()
    {
        if (isset($this->shipment['hazardous']) && in_array($this->shipment['hazardous'], ['7', '9'])) {
            return true;
        }

        return false;
    }

    // Dry Ice Shipment
    public function isICE()
    {
        $dryIceFlag = false;
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['dry_ice_weight']) && $package['dry_ice_weight'] > 0) {
                $dryIceFlag = true;
            }
        }

        return $dryIceFlag;
    }

    // Duties & Taxes Paid
    public function isDTP()
    {
        return false;
    }

    // Broker shipment
    public function isBRO()
    {
        $broker = false;
        if (isset($this->shipment['broker_name']) && $this->shipment['broker_name'] != "") {
            $broker = true;
        }

        if (isset($this->shipment['broker_company_name']) && $this->shipment['broker_company_name'] != "") {
            $broker = true;
        }

        return $broker;
    }

    // Over Max Limits
    public function isMAX()
    {
        return false;
    }

    // Is Peak Season
    public function isPeakSeason()
    {
        return false;
    }

    // Collection charge applies
    public function isCOL()
    {
        return false;
    }

    // Is Residential
    public function isRES()
    {
        if (isset($this->shipment['recipient_type']) && $this->shipment['recipient_type'] == 'r') {
            return true;
        } else {
            return false;
        }
    }

    // Remote Area Surcharge
    public function isRAS()
    {

        // Implemented at child level
        return false;
    }

    // Extended Area Surcharge
    public function isEAS()
    {

        // Implemented at child level
        return false;
    }

    // Extended Area Surcharge
    public function isOOA()
    {

        // Implemented at child level
        return false;
    }

    /**
     * Returns true if any piece in the shipment has any dim greater than 120cm
     */
    public function isOSP()
    {

        // If Carrier is IFS then charge does not apply
        if ($this->shipment['carrier_id'] == '1') {
            return false;
        }

        // Check if shipment contains any LPS packages
        $i = $this->countOSPPackages();
        if ($i > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns true if any piece in the shipment has a weight in excess of 69.5 kg
     */
    public function isOWP()
    {
        // Check if shipment contains any LPS packages
        $i = $this->countOWPPackages();
        if ($i > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns true if this is a large package shipment
     */
    public function isLPS()
    {

        // Check if shipment contains any LPS packages
        $i = $this->countLPSPackages();
        if ($i > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns true if this is a large package shipment
     */
    public function isLPSPackage($package)
    {
        if (isset($package['length']) && isset($package['width']) && isset($package['height'])) {
            $girth = $package['length'] + ($package['width'] + $package['height']) * 2;

            if ($girth > $this->lowerMaxGirth && $girth <= $this->upperMaxGirth) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if this is a large package shipment
     */
    public function countLPSPackages()
    {
        $i = 0;
        foreach ($this->shipment['packages'] as $package) {
            if ($this->isLPSPackage($package)) {
                $i++;
            }
        }

        return $i;
    }

    /**
     * Returns true if this is a large package shipment
     */
    public function countOSPPackages()
    {
        $i = 0;
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['length']) && isset($package['width']) && isset($package['height'])) {
                $maxDim = max($package['length'], $package['width'], $package['height']);
                if ($maxDim > $this->maxStdDimension) {
                    $i++;
                }
            }
        }

        return $i;
    }

    /**
     * Returns true if this is a large package shipment
     */
    public function countOWPPackages()
    {
        $i = 0;
        foreach ($this->shipment['packages'] as $package) {
            if (isset($package['weight']) && $package['weight'] > $this->maxStdWeight) {
                $i++;
                continue;
            }
            if (isset($package['volumetric_weight']) && $package['volumetric_weight'] > $this->maxStdWeight) {
                $i++;
                continue;
            }
        }

        return $i;
    }

    public function addSurcharge($charge = null)
    {

        // if not zero add to charges
        if (isset($charge['value']) && $charge['value'] != 0) {
            $charge['value'] = number_format($charge['value'], 2, '.', '');
            $this->response['charges'][] = $charge;
            $this->log('<' . $charge['description'] . ' - ' . $charge['value'] . '>');
        }
    }

    /**
     * Having gathered all required info calculate
     * the price.
     */
    public function doCalcs()
    {

        /*
         * ***************************************
         * Calculate Freight Cost
         * ***************************************
         */

        // If we do not require costs
        if ($this->priceType == 'costs' && $this->costsRequired == 'N') {
            $this->log("Costs not required");
            return;
        }

        $this->calcSurcharges();
        $this->calcFreight();
        $this->calcFuel();
    }

    /**
     * Calculate the Freight portion of the price
     */
    public function calcFreight()
    {

        // Get Package Type
        $this->getPackagingType();
        $packagingType = ($this->packagingType == '') ? 'Unknown' : $this->packagingType . "(s)";
        $this->log("Using Packaging type of $packagingType");

        // Get Rate Details for this Packaging Type/zone/pieces/weight
        $this->log("Looking for Rate for");
        $this->log("   CompanyId: ".$this->shipment['company_id']);
        $this->log("   RateId: ".$this->rate['id']);
        $this->log("   ServiceId: " . $this->shipment['service_id']);
        $this->log("   Recipient Type: " . $this->shipment['recipient_type']);
        $this->log("   Packaging Type: " . $this->packagingType);
        $this->log("   Pieces: " . $this->shipment['pieces']);
        $this->log("   Chargable Weight: " . $this->chargeableWeight);
        $this->log("   Zone: " . $this->zone);
        $this->log("   ShipDate: " . $this->shipment['ship_date']);
        $currentRateLine = $this->getRateDetails($this->shipment['company_id'], $this->rate['id'], $this->shipment['service_id'], $this->shipment['recipient_type'], $this->packagingType, $this->shipment['pieces'], $this->chargeableWeight, $this->zone, $this->shipment['ship_date']);
        if ($currentRateLine) {
            $result = ['charge' => 0, 'break_point' => 0];
            $charge = ['code' => 'FRT', 'description' => $this->shipment['pieces'] . " " . $packagingType . " to Area " . strtoupper($this->zone), 'value' => 0];

            /*
             * ******************************************************
             *  Special treatment for all non Fedex per kg cost rates
             * ******************************************************
             */
            if ($this->shipment['carrier_id'] != '2' || $this->priceType == "Sales") {

                /*
                 * *********************************************
                 *  If pricing segment is weight based,
                 *  calc values of previous segments (if any)
                 * *********************************************
                 */
                if ($currentRateLine->weight_rate > 0) {

                    // Recursively price all per kg break points
                    $result = $this->getPrevSegmentCharge($currentRateLine);
                    $charge['value'] = $result['charge'];
                    $this->log($charge['description'] . ' - ' .$charge['value']);
                }
            }
            /*
             * *********************************************
             * Calc Charges for this segment
             * *********************************************
             */
            $incrementalWeight = $this->chargeableWeight - $result['break_point'];
            $charge = $this->calcSegmentCharge($currentRateLine, $incrementalWeight, $charge);
            $this->addSurcharge($charge);
        } else {
            $this->log("No Rate found");
        }
    }

    public function calcSegmentCharge($currentRateLine, $incrementalWeight, $charge)
    {

        /*
         * *********************************************
         *  Calc value of this pricing segment
         * *********************************************
         */
        // Calculate kg rate
        if ($currentRateLine->weight_rate > 0) {
            $rateValue = $this->applyDiscount($currentRateLine->weight_rate, $this->rate['discount']);
            $charge['value'] += round(ceil($incrementalWeight / $currentRateLine->weight_increment) * $rateValue, 2);
            $this->log("Add Segment Kg charge: ".ceil($incrementalWeight / $currentRateLine->weight_increment). " kgs * $rateValue");
        }

        // Is there a Package related charge
        if ($currentRateLine->package_rate > 0) {
            $rateValue = $this->applyDiscount($currentRateLine->package_rate, $this->rate['discount']);
            $charge['value'] += $this->shipment['pieces'] * $rateValue;
            $this->log("Add Segment Pkg charge: ".$this->shipment['pieces']. " pkgs * $rateValue");
        }

        // Is there a Consignment related charge
        if ($currentRateLine->consignment_rate > 0) {
            $rateValue = $this->applyDiscount($currentRateLine->consignment_rate, $this->rate['discount']);
            $charge['value'] += $rateValue;
            $this->log("Add Segment Consignment charge: $rateValue");
        }

        return $charge;
    }

    /**
     * Recursive function to calculate freight costs/ charges
     * Valid only for weight and consignment charges only
     *
     * @param type $rateTableLine
     * @param type $chargeableWeight
     * @return type
     */
    public function getPrevSegmentCharge($rateTableLine)
    {
        $reply = ['charge' => 0, 'break_point' => 0];
        $previous = ['charge' => 0, 'break_point' => 0];

        $currentRateLine = $this->getCurrentRateLine($rateTableLine);
        if ($currentRateLine) {
            if ($currentRateLine->weight_rate > 0) {

                /*
                 * *****************************************************
                 * If there is a Weight related charge then recursively
                 * call until no weight related charge is found
                 * *****************************************************
                 */
                $reply = $this->getPrevSegmentCharge($currentRateLine);

                // How many kgs does this weight break apply to
                $weightIncrement = round($currentRateLine->break_point - $reply['break_point'], 2);
                $this->log("Calculate for segment ".$currentRateLine->break_point." to " . $reply['break_point']. " Weight Inc: $weightIncrement");

                // Add this weight breaks kg charge to charges already calculated
                $rateValue = $this->applyDiscount($currentRateLine->weight_rate, $this->rate['discount']);
                $reply['charge'] += round(ceil($weightIncrement / $currentRateLine->weight_increment) * $rateValue, 2);
                $this->log("Weight Charge: ".ceil($weightIncrement / $currentRateLine->weight_increment)." * ".$rateValue);
            } else {

                // Calculate Consignment charge
                $reply['charge'] = $this->applyDiscount($currentRateLine->consignment_rate, $this->rate['discount']);
                $this->log("Cons Charge: ".$reply['charge']);
            }

            // Return value of weight up to which we have calculated charges
            $reply['break_point'] = $currentRateLine->break_point;
        }

        return $reply;
    }

    public function applyDiscount($charge, $discount = 0)
    {
        $discountAmt = 0;
        if ($discount <> 0) {
            $discountAmt = round(($charge * $discount / 100), 2);
        }

        return round($charge - $discountAmt, 2);
    }

    public function getCurrentRateLine($rateTableLine)
    {

        // Get Rate Details for previous break point
        $prevDetail = new RateDetail();
        $prevDetail->debug = $this->debug;
        $rateDetail = $prevDetail->where('rate_id', $rateTableLine->rate_id)
                ->where('residential', $rateTableLine->residential)
                ->where('piece_limit', $rateTableLine->piece_limit)
                ->where('package_type', $rateTableLine->package_type)
                ->where('zone', $rateTableLine->zone)
                ->where('break_point', '<', $rateTableLine->break_point)
                ->where('from_date', $rateTableLine->from_date)
                ->where('to_date', $rateTableLine->to_date)
                ->orderBy('break_point', 'desc')
                ->first();

        // Get Rate Details for this Packaging Type/zone/pieces/weight incl discount if exists
        return $this->getRateDetails(
            $this->shipment['company_id'],
            $this->rate['id'],
            $this->shipment['service_id'],
            $this->shipment['recipient_type'],
            $this->packagingType,
            $this->shipment['pieces'],
            $rateDetail->break_point,
            $this->zone,
            $this->shipment['ship_date']
        );
    }

    /**
     * Calculate the correct Fuel Surcharge
     */
    public function calcFuel()
    {

        /*
         * ***************************************
         * Calculate Fuel Surcharge inc Fuel Cap
         * ***************************************
         */
        $charge['code'] = 'FUEL';
        $charge['description'] = 'Fuel Surcharge';
        $charge['value'] = 0;

        // If Fuel Surcharge set - calculate it
        if ($this->fuelPercentage > 0) {
            if (isset($this->rate['fuel_cap']) && ($this->rate['fuel_cap'] < $this->fuelPercentage)) {
                $this->fuelPercentage = $this->rate['fuel_cap'];                         // Cap Fuel Percentage
            }
            $value = $this->calcCharges();
            $charge['value'] = round(($value * $this->fuelPercentage) / 100, 2);
        }
        $this->addSurcharge($charge);
    }

    /**
     * Receives a list of all charges and
     * totals all freight related charges
     *
     * @param type
     */
    public function calcCharges()
    {
        $total = 0;

        // Loop through charges
        if (isset($this->response['charges'])) {
            foreach ($this->response['charges'] as $charge) {

                // If charge is one that we are looking for
                if (in_array($charge['code'], $this->fuelChargeCodes)) {
                    $total += $charge['value'];
                }
            }
        }

        return $total;
    }

    /**
     * Retrieve the detail Freight rate for the shipment.
     * If shipment is to a residential address and no
     * residential rate exists then the method will
     * fall back to the commercial rate.
     */
    public function getRateDetails($companyId, $rateId, $serviceId, $recipientType, $packagingType, $pieces, $chargeableWeight, $zone, $shipDate)
    {
        $rateDetail = new RateDetail();
        $rateDetail->debug = $this->debug;

        // Get residential Rate Details for this Packaging Type/zone/pieces/weight
        $rateDetail = $rateDetail->getRate(
            $companyId,
            $rateId,
            $serviceId,
            $recipientType,
            $packagingType,
            $pieces,
            $chargeableWeight,
            $zone,
            $shipDate
        );

        // If No rate found set error message
        if (empty($rateDetail)) {
            $this->response['errors'][] = "No " . $this->priceType . " rate/ current rate found";
            $this->response['debug'][] = "RateId : " . $this->rate['id']
                    . ", Packaging : " . $this->packagingType
                    . ", Zone : " . $this->zone
                    . ", Piece Limit >= " . $this->shipment['pieces']
                    . ", BreakPoint >= " . $this->chargeableWeight;
        }

        return $rateDetail;
    }

    /**
     * Calculate the appropriate discount to apply
     */
    public function calcDiscount()
    {

        /*
         * ***************************************
         * Calculate Discount if applicable
         * ***************************************
         */
        if (isset($this->response['charges'])) {
            foreach ($this->response['charges'] as $charge) {

                // If charge is one that we are looking for
                if (in_array($charge['code'], ['FRT'])) {

                    // And a discount % exists
                    if ($this->rate['discount'] > 0) {
                        $charge['value'] -= round(($charge['value'] * $this->rate['discount'] / 100), 2, PHP_ROUND_HALF_UP);
                        $this->log($charge['code'] . " Discounted by " . round(($charge['value'] * $this->rate['discount'] / 100), 2, PHP_ROUND_HALF_UP));
                    }
                }

                $charge['value'] = number_format($charge['value'], 2, '.', '');
                $newCharges[] = $charge;
            }

            $this->response['charges'] = $newCharges;
        }

        // $this->addSurcharge($charge);
    }

    public function show($var, $desc = 'Debug')
    {
        echo "$desc : <pre>";
        print_r($var);
        echo "</pre><br>";
    }
}
