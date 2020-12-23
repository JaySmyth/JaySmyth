<?php

/*
 * ****************************************************
 *                        Notes:
 * ****************************************************
 *
 * To add new Carrier Pricing the minimum required is to
 *
 *  1   Add the Carrier to $this->buildmodel()
 *  2   Define the model in PricingModel::__Construct()
 *  3   Create a class PricingModel{carrier_id}
 *      Extending PricingModel. Any carrier specific
 *      requirements may be added here.
 *  4   Add carriers zones to pricing_zones
 *  5   Add Fuel% to fuel_percentage table
 * ****************************************************
 */

namespace app\Pricing;

use App\Models\Carrier;
use App\Models\Company;
use App\Models\CompanyRate;
use App\Models\Package;
use App\Models\Service;
use App\Models\Shipment;

/**
 * ********************************
 * buildModel() builds the correct
 * Pricing Model to allow the
 * shipment to be priced.
 * ********************************.
 */
class Pricing
{
    private $service = '';
    private $serviceId;
    private $costs;
    private $sales;
    private $errors;
    private $salesRate;
    private $costRate;
    public $debug = false;
    public $log = [];

    public function __construct($id = null)
    {
        if ($id) {
            $this->load($id);
        }
    }

    public function log($msg)
    {
        $this->log[] = $msg;
    }

    public function load($id)
    {
        $shipmentArray = Shipment::find($id)->toArray();
        if (! empty($shipmentArray)) {
            $shipmentArray['packages'] = Package::where('shipment_id', $id)->get()->toArray();

            return $this->price($shipmentArray, $shipmentArray['service_id']);
        } else {
            return;
        }
    }

    public function price($shipment, $serviceId = '')
    {
        $this->shipment = $shipment;
        $this->serviceId = $serviceId;

        // Fill in any missing data
        $this->preProcess();

        // Calculate Costs and capture logs
        $this->calcCosts();
        $this->log[] = (isset($this->model)) ? $this->model->log : 'Rate Not Defined';

        // Calculate Sales and capture logs
        $this->calcSales();
        $this->log[] = (isset($this->model)) ? $this->model->log : 'Rate Not Defined';

        return $this->createResponse();
    }

    /**
     * **********************************************
     * Perform any necessary processing prior to
     * processing the shipment. eg to fill in missing
     * information
     * **********************************************.
     */
    public function preProcess()
    {
        $this->identifyService();
        $this->carrier = Carrier::find($this->service->carrier_id);
        $this->shipment['service_id'] = $this->service->id;
        $this->shipment['service_code'] = $this->service->code;
        $this->shipment['carrier_id'] = $this->service->carrier_id;
        $this->shipment['carrier_code'] = $this->carrier->code;

        // If no Shipment date set use today
        if (empty($this->shipment['ship_date'])) {
            $this->shipment['ship_date'] = date('Y-m-d');
        }

        $company = Company::find($this->shipment['company_id']);
        if ($company) {
            // Does company have a VAT exemption
            $this->shipment['vat_exempt'] = Company::find($this->shipment['company_id'])->vat_exempt;

            // Do we need to send pricing debug info
            $this->debug = ($company->carrier_choice == 'debug') ? true : false;

            // Set pricing offset
            $this->shipment['ship_date'] = Carbon::parse($this->shipment['ship_date'])->addDays($company->pricing_date_offset)->format('Y-m-d');
        }
    }

    public function identifyService()
    {
        if (!empty($this->serviceId)) {
            // Use specified Service Id
            $this->service = Service::find($this->serviceId);
        } else {
            // Use Shipemnt Service Id
            $this->service = Service::find($this->shipment['service_id']);
        }
    }

    public function sumCharges($charges, $chargeCode = '')
    {
        $total = 0;
        if (is_array($charges)) {
            foreach ($charges as $charge) {
                if (empty($chargeCode)) {
                    $total += $charge['value'];
                } else {
                    if ($chargeCode == $charge['code']) {
                        $total += $charge['value'];
                    }
                }
            }

            return $total;
        }

        return $total;
    }

    public function createResponse()
    {
        $errors = array_merge($this->costs['errors'], $this->sales['errors']);
        if (empty($errors)) {
            $response['shipping_cost'] = round($this->sumCharges($this->costs['charges']), 2);
            $response['shipping_charge'] = round($this->sumCharges($this->sales['charges']), 2);
            $response['fuel_cost'] = round($this->sumCharges($this->costs['charges'], 'FUEL'), 2);
            $response['fuel_charge'] = round($this->sumCharges($this->sales['charges'], 'FUEL'), 2);
            $response['cost_vat_amount'] = $this->costs['vat_amount'];
            $response['cost_vat_code'] = $this->costs['vat_code'];
            $response['cost_currency'] = $this->costs['currency'];
            $response['sales_vat_amount'] = $this->sales['vat_amount'];
            $response['sales_vat_code'] = $this->sales['vat_code'];
            $response['sales_currency'] = $this->sales['currency'];
            $response['costs'] = $this->costs['charges'];
            if (isset($this->costs['debug'])) {
                $response['costs_detail'] = $this->costs['debug'];
            }
            $response['sales'] = $this->sales['charges'];
            if (isset($this->sales['debug'])) {
                $response['sales_debug'] = $this->sales['debug'];
                $response['sales_detail'] = $this->sales['debug'];
            }
            $response['costs_zone'] = $this->costs['zone'];
            $response['sales_zone'] = $this->sales['zone'];
            $response['costs_model'] = $this->costs['model'];
            $response['sales_model'] = $this->sales['model'];
            $response['costs_rate_id'] = $this->costs['rate_id'];
            $response['sales_rate_id'] = $this->sales['rate_id'];
            $response['costs_packaging'] = $this->costs['packaging'];
            $response['sales_packaging'] = $this->sales['packaging'];
        } else {
            $response['shipping_cost'] = 0;
            $response['shipping_charge'] = 0;
            $response['fuel_cost'] = 0;
            $response['fuel_charge'] = 0;
            $response['cost_currency'] = '';
            $response['sales_currency'] = '';
            $response['cost_vat_amount'] = 0;
            $response['cost_vat_code'] = 0;
            $response['sales_vat_amount'] = 0;
            $response['sales_vat_code'] = 0;
            $response['costs'] = [];
            $response['costs_detail'] = [];
            $response['sales'] = [];
            $response['sales_detail'] = [];
            $response['costs_zone'] = '';
            $response['sales_zone'] = '';
            $response['costs_packaging'] = '';
            $response['sales_packaging'] = '';
        }
        $response['errors'] = $errors;

        if ($this->debug) {
            $response['Pricinglog'] = $this->log;
            mail('debug@antrim.ifsgroup.com', 'Pricing Analysis - Errors', json_encode($errors).'/n'.json_encode($this->log));
        }

        return $response;
    }

    private function buildModel($model)
    {

        // Build appropriate Model
        switch (strtolower($model)) {

            /*
             * *********************
             *  Choose Pricing model
             * *********************
             */
            case 'domestic':
                $this->model = new \App\Pricing\Methods\Domestic($this->debug);
                break;

            case 'fedex':
                $this->model = new \App\Pricing\Methods\FedexIntl($this->debug);
                break;

            case 'ups':
                $this->model = new \App\Pricing\Methods\Ups($this->debug);
                break;

            case 'tnt':
                $this->model = new \App\Pricing\Methods\Tnt($this->debug);
                break;

            case 'dhl':
                $this->model = new \App\Pricing\Methods\Dhl($this->debug);
                break;

            case 'cwide':
                $this->model = new \App\Pricing\Methods\CountryWide($this->debug);
                break;

            case 'xdp':
                $this->model = new \App\Pricing\Methods\Xdp($this->debug);
                break;

            default:
                $this->model = new \App\Pricing\Methods\Generic($this->debug);
                break;
        }

        $this->model->debug = $this->debug;
    }

    public function calcCosts()
    {

        // Get Cost Rate - No Fuel Cap on costs
        $this->costRate = Company::find($this->shipment['company_id'])->costRateForService($this->shipment['service_id']);
        $this->costRate['fuel_cap'] = 999;
        $this->log('Fuel Cap: '.$this->costRate['fuel_cap']);

        // Price Shipment
        $this->costs = $this->getPrice($this->costRate, 'Costs');

        // Calculate Vat
        $vat = calcVat(
            $this->shipment['recipient_country_code'],
            $this->sumCharges($this->costs['charges']),
            $this->shipment['vat_exempt']
        );
        $this->costs['vat_amount'] = $vat['vat_amount'];
        $this->costs['vat_code'] = $vat['vat_code'];

        if (isset($this->costRate['currency_code'])) {
            $this->costs['currency'] = $this->costRate['currency_code'];
        } else {
            $this->costs['currency'] = '';
        }
    }

    public function calcSales()
    {

        // Get Sales Rate
        $this->salesRate = Company::find($this->shipment['company_id'])->salesRateForService($this->shipment['service_id']);

        // Price Shipment
        $this->sales = $this->getPrice($this->salesRate, 'Sales');

        // Calculate Vat
        $vat = calcVat(
            $this->shipment['recipient_country_code'],
            $this->sumCharges($this->sales['charges']),
            $this->shipment['vat_exempt']
        );
        $this->sales['vat_amount'] = $vat['vat_amount'];
        $this->sales['vat_code'] = $vat['vat_code'];

        if (isset($this->salesRate['currency_code'])) {
            $this->sales['currency'] = $this->salesRate['currency_code'];
        } else {
            $this->sales['currency'] = '';
        }
    }

    public function getPrice($rate, $priceType)
    {
        $response['errors'] = [];
        $response['zone'] = '';
        $response['model'] = '';
        $response['rate_id'] = '';
        $response['packaging'] = '';
        $response['charges'] = [];

        // If we know how to process this shipment and it is valid for the old system
        if (isset($rate['model'])) {
            $this->buildModel($rate['model']);

            $response = $this->model->price($this->shipment, $rate, $priceType);
        } else {

            // Unable to cost shipment but allowed a zero cost
            if ($priceType == 'Costs' && $this->service->allow_zero_cost) {

                // Allow Zero Costs
            } else {
                $response['errors'][] = "$priceType Rate Table not found";
            }
        }

        return $response;
    }

    public function show($var, $desc = 'Debug')
    {
        echo "$desc : <pre>";
        print_r($var);
        echo '</pre><br>';
    }
}
