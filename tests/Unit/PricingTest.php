<?php

namespace Tests\Unit;

use TestCase;

use App\User;
use App\Service;
use App\Pricing\Pricing;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PricingTest extends TestCase {

    private $user;
    private $niAddress;
    private $ukAddress;
    private $euAddress;
    private $usAddress;
    private $pricing;
    private $target;
    private $prices;

    /**
     * Initial Setup to run Test as a named User
     * and initialise the Pricing Object
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = User::find(2012);     // courier-demo@test.com
        $this->actingAs($this->user);

        $this->niAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"849","recipient_id":"","recipient_name":"G McBroom","recipient_company_name":"MyCo","recipient_type":"c","recipient_address1":"5 Mandeville Manor","recipient_address2":"","recipient_address3":"","recipient_city":"Portadown","recipient_country_code":"GB","recipient_state":"Armagh","recipient_postcode":"BT62 3UP","recipient_telephone":"02894464211","recipient_email":"test@hotmail.com","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"test@hotmail.com","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"GB","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"package","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->ukAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"849","recipient_id":"","recipient_name":"G McBroom","recipient_company_name":"MyCo","recipient_type":"c","recipient_address1":"5 Mandeville Manor","recipient_address2":"","recipient_address3":"","recipient_city":"Bradford","recipient_country_code":"GB","recipient_state":"West Yorkshire","recipient_postcode":"BD3 3ES","recipient_telephone":"02894464211","recipient_email":"test@hotmail.com","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"test@hotmail.com","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"GB","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"package","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->euAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"849","recipient_id":"133620","recipient_name":"A. Cecchi","recipient_company_name":"Biancalani SPA","recipient_type":"c","recipient_address1":"V. Menichetti 28","recipient_address2":"","recipient_address3":"","recipient_city":"Prato","recipient_country_code":"IT","recipient_state":"","recipient_postcode":"59100","recipient_telephone":"0574 54871","recipient_email":"","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"IT","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"package","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->usAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"849","recipient_id":"134242","recipient_name":"Adriana Lucin","recipient_company_name":"BROOKS BROS","recipient_type":"c","recipient_address1":"39-25 Skillman Ave.","recipient_address2":"Sunnyside NY 11104","recipient_address3":"","recipient_city":"SUNNYSIDE","recipient_country_code":"US","recipient_state":"NY","recipient_postcode":"11104","recipient_telephone":"7186094425","recipient_email":"","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"US","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"package","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';

        $this->pricing = new Pricing();
    }

    /**
     * Accepts a service code and Carrier Code
     * and returns the Service Id
     * 
     * @param type $serviceCode
     * @param type $carrier
     * @return integer service_id
     */
    public function getServiceId($serviceCode, $carrier)
    {

        $carriers = ['ifs' => 1, 'fedex' => 2, 'ups' => 3, 'dhl' => 5];
        $service = new Service();
        return $service->where('code', $serviceCode)
                        ->where('carrier_id', $carriers[$carrier])
                        ->first()->id;
    }

    /**
     * Accepts Priced Shipment and compares with
     * what we expect, raising errors if appropriate
     * 
     * @param type $target
     * @param type $prices
     */
    public function checkMatch($target, $prices, $test = '')
    {

        // Save data so that it is available to the custom error fn
        $this->target = $target;
        $this->prices = $prices;

        $costs = 0;
        foreach ($prices['costs'] as $charge) {
            switch (strtoupper($charge['code'])) {

                case 'FUEL':
                    // Do nothing
                    break;

                default:
                    $costs += $charge['value'];
                    break;
            }
        }

        $sales = 0;
        foreach ($prices['sales'] as $charge) {
            switch (strtoupper($charge['code'])) {

                case 'FUEL':
                    // Do Nothing
                    break;

                default:
                    $sales += $charge['value'];
                    break;
            }
        }

        $display = $this->buildError($target, $prices);

        // echo $test . " ". $display;
        $this->assertEquals($costs, $target['freight_cost'], 'Costs do not match' . $display, 0.005);
        $this->assertEquals($sales, $target['freight_charge'], 'Sales do not match' . $display, 0.005);
        $this->assertEquals($target['costs_zone'], $prices['costs_zone'], 'Cost Zone incorrect', 0.005);
        $this->assertEquals($target['sales_zone'], $prices['sales_zone'], 'Sales Zone incorrect', 0.005);
    }

    public function buildError($target, $prices)
    {

        $error = $this->displayValues($prices['costs'], 'Actual  Costs');
        $error .= $this->displayValues($prices['sales'], ' Sales') . "\n";
        $error .= " Expected Costs : " . $target['freight_cost'] . " Sales : " . $target['freight_charge'] . "\n";

        return $error;
    }

    public function displayValues($prices, $heading)
    {

        $error = "";
        foreach ($prices as $charge) {
            switch (strtoupper($charge['code'])) {

                case 'FUEL':
                    // Do nothing
                    break;

                default:
                    $error .= " $heading : " . $charge['code'] . " Value : " . $charge['value'];
                    break;
            }
        }

        return $error;
    }

    /**
     * Creates the shipment array using the
     * named address and sets as Residential
     * or commercial as required
     * 
     * @param type $address
     * @param type $residential
     * @return string
     */
    public function decode($address, $residential = false)
    {

        $shipment = json_decode($address, true);

        // Set as a Commercial/ residential address
        if ($residential) {
            $shipment['recipient_type'] = 'r';
        } else {
            $shipment['recipient_type'] = 'c';
        }

        return $shipment;
    }

    /*
     * ************************************
     * ***     Start of Unit Tests      ***
     * ************************************
     * 
     * Note:
     *      Target values should exclude
     *      Fuel Surcharge.
     */

    public function testHeading()
    {

        echo "\n******************************************";
        echo "\n               Unit Test";
        echo "\n      Check Pricing for Test Cases";
        echo "\n******************************************\n";
        $this->assertEquals(1, 1);
    }

    /*
     * ************************************
     *             Carrier IFS
     * ************************************
     */

    public function test_IFS_NI48_Shipment()
    {

        $serviceId = $this->getServiceId('ni48', 'ifs');
        $shipment = $this->decode($this->niAddress);

        $target = [
            'freight_cost' => 0,
            'freight_charge' => 7.42,
            'costs_zone' => 'ni',
            'sales_zone' => 'ni'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'IFS NI48');
    }

    /*
     * ************************************
     *           Carrier Fedex
     * ************************************
     */

    public function test_Fedex_UK48_Shipment()
    {

        $serviceId = $this->getServiceId('uk48', 'fedex');
        $shipment = $this->decode($this->ukAddress);

        $target = [
            'freight_cost' => 3.82,
            'freight_charge' => 19.34,
            'costs_zone' => '2',
            'sales_zone' => '2'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'Fedex UK48');
    }

    public function test_Fedex_IP_EU_Shipment()
    {

        $serviceId = $this->getServiceId('ip', 'fedex');
        $shipment = $this->decode($this->euAddress);

        $target = [
            'freight_cost' => 22.84,
            'freight_charge' => 48.14,
            'costs_zone' => 'S',
            'sales_zone' => 'S'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'Fedex IP EU');
    }

    public function test_Fedex_IP_US_Shipment()
    {

        $serviceId = $this->getServiceId('ip', 'fedex');
        $shipment = $this->decode($this->usAddress);

        $target = [
            'freight_cost' => 44.58,
            'freight_charge' => 92.63,
            'costs_zone' => 'H',
            'sales_zone' => 'A'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'Fedex IP US');
    }

    /*
     * ************************************
     *           Carrier UPS
     * ************************************
     */

    public function test_UPS_UK24_Shipment_Residential()
    {

        $serviceId = $this->getServiceId('uk24', 'ups');
        $shipment = $this->decode($this->ukAddress, true);

        $target = [
            'freight_cost' => 8.96,
            'freight_charge' => 18.72,
            'costs_zone' => '1',
            'sales_zone' => '1'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS UK24');
    }

    public function test_UPS_UK24_Shipment_Commercial()
    {

        $serviceId = $this->getServiceId('uk24', 'ups');
        $shipment = $this->decode($this->ukAddress, false);

        $target = [
            'freight_cost' => 6.56,
            'freight_charge' => 15.89,
            'costs_zone' => '1',
            'sales_zone' => '1'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS UK24');
    }

    public function test_UPS_STD_EU_Shipment_Residential()
    {

        $serviceId = $this->getServiceId('std', 'ups');
        $shipment = $this->decode($this->euAddress, true);

        $target = [
            'freight_cost' => 23.04,
            'freight_charge' => 18.88,
            'costs_zone' => '5',
            'sales_zone' => '5'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS STD EU Res');
    }

    public function test_UPS_STD_EU_Shipment_Commercial()
    {

        $serviceId = $this->getServiceId('std', 'ups');
        $shipment = $this->decode($this->euAddress, false);

        $target = [
            'freight_cost' => 20.64,
            'freight_charge' => 16.05,
            'costs_zone' => '5',
            'sales_zone' => '5'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS STD EU Com');
    }

    public function test_UPS_US_Shipment_Residential()
    {

        $serviceId = $this->getServiceId('ip', 'ups');
        $shipment = $this->decode($this->usAddress, true);

        $target = [
            'freight_cost' => 35.76,
            'freight_charge' => 47.72,
            'costs_zone' => '6',
            'sales_zone' => '6'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS US Res');
    }

    public function test_UPS_US_Shipment_Commercial()
    {

        $serviceId = $this->getServiceId('ip', 'ups');
        $shipment = $this->decode($this->usAddress, false);

        $target = [
            'freight_cost' => 33.36,
            'freight_charge' => 44.89,
            'costs_zone' => '6',
            'sales_zone' => '6'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'UPS US Com');
    }

    /*
     * ************************************
     *           Carrier DHL
     * ************************************
     */

    public function test_DHL_EU_Shipment()
    {

        $serviceId = 26;
        $shipment = $this->decode($this->euAddress);

        $target = [
            'freight_cost' => 30.53,
            'freight_charge' => 62.09,
            'costs_zone' => '3',
            'sales_zone' => '3'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'DHL EU');
    }

    public function test_DHL_IP_US_Shipment()
    {

        $serviceId = 27;
        $shipment = $this->decode($this->usAddress);

        $target = [
            'freight_cost' => 26.98,
            'freight_charge' => 54.92,
            'costs_zone' => '6',
            'sales_zone' => '6'
        ];
        $prices = $this->pricing->price($shipment, $serviceId);

        $this->checkMatch($target, $prices, 'DHL US');
    }

}
