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
use App\Pricing\PricingModel0;
use App\Pricing\PricingModel1;
use App\Pricing\PricingModel2;
use App\Pricing\PricingModel3;
use App\Pricing\PricingModel4;
use App\Pricing\PricingModel5;
use App\Pricing\PricingModel6;

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
        $shipment = Shipment::find($id);

        if ($shipment) {
            $shipmentArray = $shipment->toArray();
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
        $this->preProcess($shipment, $serviceId);

        // Calculate Costs
        $this->calcCosts();

        if (isset($this->model)) {
            $this->log[] = $this->model->log;
        } else {
            $this->log[] = 'Rate Not Defined';
        }

        // Calculate Sales
        $this->calcSales();
        if (isset($this->model)) {
            $this->log[] = $this->model->log;
        } else {
            $this->log[] = 'Rate Not Defined';
        }

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
        if (!empty($this->serviceId)) {

            // Use specified Service/ Carrier
            $this->service = Service::find($this->serviceId);
        } else {
            $this->service = Service::find($this->shipment['service_id']);
        }

        $this->carrier = Carrier::find($this->service->carrier_id);
        $this->shipment['service_id'] = $this->service->id;
        $this->shipment['service_code'] = $this->service->code;
        $this->shipment['carrier_id'] = $this->service->carrier_id;
        $this->shipment['carrier_code'] = $this->carrier->code;

        // If no Shipment date set use today
        if (! isset($this->shipment['ship_date']) || empty($this->shipment['ship_date'])) {
            $this->shipment['ship_date'] = date('Y-m-d');
        }

        // Does company have a VAT exemption
        $company = Company::find($this->shipment['company_id']);
        $this->shipment['vat_exempt'] = $company->vat_exempt;

        // Do we need to send pricing debug info
        if ($company->carrier_choice == 'debug') {
            $this->debug = true;
        }
    }

    public function addToArray($data, $result = [])
    {
        if ($data) {
            if (is_array($data)) {
                foreach ($data as $value) {
                    $result[] = $value;
                }
            } else {
                $result[] = $data;
            }
        }

        return $result;
    }

    public function mergeErrors($costs, $sales)
    {
        $errors = $this->addToArray($costs);
        $errors = $this->addToArray($sales, $errors);

        return $errors;
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
        $errors = $this->mergeErrors($this->costs['errors'], $this->sales['errors']);

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
                $this->model = new PricingModel1($this->debug);
                break;

            case 'fedex':
                $this->model = new PricingModel2($this->debug);
                break;

            case 'ups':
                $this->model = new PricingModel3($this->debug);
                break;

            case 'tnt':
                $this->model = new PricingModel4($this->debug);
                break;

            case 'dhl':
                $this->model = new PricingModel5($this->debug);
                break;

            case 'cwide':
                $this->model = new PricingModel6($this->debug);
                break;

            default:
                $this->model = new PricingModel0($this->debug);
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

        // If we know how to process this shipment and it is valid for the old system
        if (isset($rate['model'])) {
            $this->buildModel($rate['model']);

            $response = $this->model->price($this->shipment, $rate, $priceType);
        } else {

            // Sorry can't price shipment
            if ($priceType == 'Costs' && $this->service->allow_zero_cost == 1) {

                // Allow Zero Costs
            } else {
                $response['errors'][] = "$priceType Rate Table not found";
            }
        }

        // If no Cost/ Sale then set to empty array
        if (! isset($response['charges'])) {
            $response['charges'] = [];
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
