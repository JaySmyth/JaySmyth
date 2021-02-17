<?php

namespace App\CarrierAPI;

use App;
use App\Models\Company;
use App\Models\Shipment;
use App\Pricing\Facades\Pricing;
use App\CarrierAPI\ServiceRules;
use Carbon\Carbon;

/**
 * Company Services
 *
 * @author gmcbroom
 */
class CompanyServices
{
    private $company;
    private $consignment;

    private $carrier;
    private $mode;
    private $nonPricedServices = ['ief', 'ipf', 'air', 'usg'];

    public function getAvailableServices()
    {
        $availableServicesArray = [];

        // Check Company is enabled
        if ($this->company->enabled) {

            // Check addresses and perform any necessary Overrides
            $this->consignment->checkAddresses();
            $collect = $this->consignment->isCollect();
            $companyServices = $this->company->getServicesForMode($this->consignment->data['mode_id']);
            $suitableServicesArray = $this->getSuitableServices($companyServices);
            $availableServicesArray = $this->getSelectedServices($suitableServicesArray, $collect);
            if ($collect) {
                $availableServicesArray = $this->removePricingDetails($availableServicesArray);
            }
        }

        return $availableServicesArray;
    }

    public function setConsignment($consignment)
    {
        $this->consignment = $consignment;
        $this->company = Company::find($this->consignment->data['company_id']);
    }

    /**
     * Accepts Shipment details and array of services
     * and returns the services appropriate to the
     * shipment including total cost and price.
     *
     * @param array Shipment
     * @param array Services available to the Customer
     *
     * @return array Appropriate services
     */
    private function getSuitableServices($companyServices)
    {
        $cnt = 0;
        $possibleServicesArray = [];
        $serviceRules = new ServiceRules();
        foreach ($companyServices as $companyService) {

            // Check if service is applicable for this shipment
            if ($serviceRules->isSuitable($this->consignment->data, $companyService)) {

                //  Price even if Collect
                $prices = Pricing::price($this->consignment->data, $companyService->id);
                if ($this->serviceAllowed($prices, $companyService)) {
                    $possibleServicesArray[$cnt] = $this->formatServiceAsArray($cnt, $companyService, $prices);
                    $cnt++;
                }
            }
        }

        return $possibleServicesArray;
    }

    private function serviceAllowed($prices, $companyService)
    {
        if ($this->ifProblemWithService($companyService)) {
            return false;
        }

        // If Customer allowed to choose Carrier so no need to worry about pricing
        if (strtolower($this->company->carrier_choice) == 'user') {
            return true;
        }

        // If this is one of the non pricing services (eg air, ipf, ...)
        if ($this->isNonPricingService($companyService)) {
            return true;
        }

        // If we can successfully cost & price shipment
        $frtSales = $this->calcFreightPartOfSales($prices);
        if ($prices['shipping_cost'] > 0 && $prices['shipping_charge'] > 0 && $frtSales > 0) {
            return true;
        }

        // Can price but not cost
        if ($frtSales > 0 && $companyService->allow_zero_cost) {
            return true;
        }

        return false;
    }

    private function formatServiceAsArray($cnt, $companyService, $prices)
    {
        $service = $companyService->toArray();
        $service['cost'] = $prices['shipping_cost'];
        $service['cost_currency'] = $prices['cost_currency'];
        $service['cost_detail'] = $prices['costs'];
        $service['price'] = $prices['shipping_charge'];
        $service['price_currency'] = $prices['sales_currency'];
        $service['price_detail'] = $prices['sales'];

        // If Company specific name exists for this service then use it.
        if (isset($service['pivot']['name']) && $service['pivot']['name'] > '') {
            $service['name'] = $service['pivot']['name'];
        }

        return $service;
    }

    private function getSelectedServices($availableServicesArray, $collect = false)
    {
        if (in_array($this->company->carrier_choice, ['price', 'cost'])) {

            // Reduce list of services by price/ cost
            $availableServicesArray = $this->getCheapestService(
                $availableServicesArray,
                $this->company->carrier_choice,
                $collect
            );
        }

        // Return sorted array
        if (! empty($availableServicesArray)) {
            usort($availableServicesArray, function ($item1, $item2) {
                return $item1['price'] <=> $item2['price'];
            });
        }

        return $availableServicesArray;
    }

    private function removePricingDetails($availableServicesArray = [])
    {
        foreach ($availableServicesArray as $key => $availableService) {
            $availableServicesArray[$key]['cost'] = [];
            $availableServicesArray[$key]['cost_currency'] = '';
            $availableServicesArray[$key]['cost_detail'] = [];
            $availableServicesArray[$key]['price'] = [];
            $availableServicesArray[$key]['price_currency'] = '';
            $availableServicesArray[$key]['price_detail'] = [];
        }

        return $availableServicesArray;
    }

    /**
     * Accepts a list of priced services and returns
     * the cheapest service based on the carrier_choice
     * setting on the company table - cost/price.
     *
     * @param array Possible Services
     * @param string Carrier choice criteria - cost/ price
     *
     * @return array Chosen Service
     */
    public function getCheapestService($suitableServicesArray, $carrierChoice, $collect = false)
    {
        $chosenService = [];

        // Definition of cheapest defined by $carrier_choice - price or cost
        foreach ($suitableServicesArray as $suitableServiceArray) {

            // Show unpriced option if Collect using Fedex
            if ($collect && $suitableServiceArray['carrier_id'] == 2) {
                $chosenService[] = $suitableServiceArray;
            } else {

                // Only use if we can price it
                if ($suitableServiceArray['price'] > 0) {
                    $chosenService = $this->addServiceToList($chosenService, $suitableServiceArray, $carrierChoice);
                } else {

                    // Show unpriced option if IPF or AIR shipment
                    if ($this->isNonPricingService($suitableServiceArray)) {
                        $chosenService[$suitableServiceArray['code']] = $suitableServiceArray;
                    }
                }
            }
        }

        return $chosenService;
    }

    private function addServiceToList($chosenService = [], $suitableServiceArray = [], $carrierChoice = '')
    {
        if (isset($chosenService[$suitableServiceArray['code']])) {
            if ($suitableServiceArray[$carrierChoice] < $chosenService[$suitableServiceArray['code']][$carrierChoice]) {

                // This Service cheaper than Previous ones
                $chosenService[$suitableServiceArray['code']] = $suitableServiceArray;
            }
        } else {

            // First Record for this service
            $chosenService[$suitableServiceArray['code']] = $suitableServiceArray;
        }

        return $chosenService;
    }

    private function isNonPricingService($companyService)
    {
        // If this is one of the non pricing services (eg air, ipf, ...)
        if (in_array($companyService['code'], $this->nonPricedServices)) {
            return true;
        }

        return false;
    }

    private function calcFreightPartOfSales($prices)
    {
        $frtSales = 0;
        if (isset($prices['sales'])) {
            foreach ($prices['sales'] as $key => $val) {
                if ($val['code'] == 'FRT' && $val['value'] > 0) {
                    $frtSales += $val['value'];
                }
            }
        }

        return $frtSales;
    }

    private function ifProblemWithService($companyService)
    {
        if ($this->hasExceededMonthlyLimit($companyService)) {
            return true;
        }

        if ($this->hasExceededMaxWeight($companyService)) {
            return true;
        }

        if ($this->isCollectAndNoAccount($companyService)) {
            return true;
        }

        return false;
    }

    private function hasExceededMonthlyLimit($companyService)
    {
        /*
         * If a monthly limit has been defined on company_service, check that it has not been exceeded
         */
        if (isset($companyService->pivot->monthly_limit) && $companyService->pivot->monthly_limit > 0) {

            // Count the shipments for the month
            $count = Shipment::whereCompanyId($this->company->id)->whereBetween('ship_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->whereNotIn('status_id', [1, 7])->count();

            // Limit has been exceeded, remove from array
            if ($count >= $companyService->pivot->monthly_limit) {
                return true;
            }
        }

        return false;
    }

    private function hasExceededMaxWeight($companyService)
    {
        /*
         * If a max weight limit has been defined on company_service, check that it has not been exceeded
         */
        if (isset($companyService->pivot->max_weight_limit) && $companyService->pivot->max_weight_limit > 0) {
            if ($this->consignment->data['weight'] > $companyService->pivot->max_weight_limit) {
                return true;
            }
        }

        return false;
    }

    private function isCollectAndNoAccount($companyService)
    {

        // If this is a collect shipment
        if ($this->consignment->isCollect()) {

            // Fedex Collect Shipments not allowed unlesss account specified
            if ($companyService->carrier_id == 2 && ! empty($this->consignment->data['bill_shipping_account'])) {
                return false;
            }
        }

        return false;
    }

    private function zeroPrices($prices)
    {
        $prices['shipping_cost'] = 0;
        $prices['shipping_charge'] = 0;
        $prices['cost_currency'] = '';
        $prices['sales_currency'] = '';
        $prices['sales'] = [];
        $prices['costs'] = [];

        return $prices;
    }
}
