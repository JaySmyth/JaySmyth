<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\CarrierAPI\Facades\CarrierAPI;

class ApiShipmentAvailabilityTest extends TestCase
{
    private $userId = 3104;
    private $companyId = 849;  // IFS Unit Testing Co
    private $mode = 'test';
    private $user;
    private $niAddress;
    private $ukAddress;
    private $euAddress;
    private $usAddress;

    /**
     * Initial Setup to run Test as a named User
     * and initialise the Pricing Object
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::find($this->userId);     // unittest@antrim.ifsgroup.com
        $this->actingAs($this->user);

        $this->niAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"' . $this->companyId . '","recipient_id":"","recipient_name":"G McBroom","recipient_company_name":"MyCo","recipient_type":"c","recipient_address1":"5 Mandeville Manor","recipient_address2":"","recipient_address3":"","recipient_city":"Portadown","recipient_country_code":"GB","recipient_state":"Armagh","recipient_postcode":"BT62 3UP","recipient_telephone":"02894464211","recipient_email":"test@hotmail.com","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"test@hotmail.com","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"GB","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"CTN","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->ukAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"' . $this->companyId . '","recipient_id":"","recipient_name":"G McBroom","recipient_company_name":"MyCo","recipient_type":"c","recipient_address1":"5 Mandeville Manor","recipient_address2":"","recipient_address3":"","recipient_city":"Bradford","recipient_country_code":"GB","recipient_state":"West Yorkshire","recipient_postcode":"BD3 3ES","recipient_telephone":"02894464211","recipient_email":"test@hotmail.com","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"test@hotmail.com","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"GB","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"CTN","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->euAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"' . $this->companyId . '","recipient_id":"133620","recipient_name":"A. Cecchi","recipient_company_name":"Biancalani SPA","recipient_type":"c","recipient_address1":"V. Menichetti 28","recipient_address2":"","recipient_address3":"","recipient_city":"Prato","recipient_country_code":"IT","recipient_state":"","recipient_postcode":"59100","recipient_telephone":"0574 54871","recipient_email":"","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"IT","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"CTN","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
        $this->usAddress = '{"_token":"fL7A9od2enTzf5glM89ChrXp0egRHs1WKrjrysai","address_book_definition":"","shipment_id":"","user_id":"' . $this->user->id . '","print_formats_id":"2","mode":"courier","mode_id":"1","dims_uom":"cm","weight_uom":"kg","date_format":"dd-mm-yyyy","currency_code":"GBP","weight":"10.00","service_id":"0","freight_charge":"0","data_loaded":"true","customs_value":"0.00","customs_value_currency_code":"","commodity_count":"0","sender_id":"","sender_name":"Garfield McBroom","sender_company_name":"Demo Company","sender_type":"c","sender_address1":"Unit D","sender_address2":"17 Bedford Street","sender_address3":"","sender_city":"Belfast","sender_country_code":"GB","sender_state":"County Antrim","sender_postcode":"BT2 7EF","sender_telephone":"02894464211","sender_email":"gmcbroom@antrim.ifsgroup.com","company_id":"' . $this->companyId . '","recipient_id":"134242","recipient_name":"Adriana Lucin","recipient_company_name":"BROOKS BROS","recipient_type":"c","recipient_address1":"39-25 Skillman Ave.","recipient_address2":"Sunnyside NY 11104","recipient_address3":"","recipient_city":"SUNNYSIDE","recipient_country_code":"US","recipient_state":"NY","recipient_postcode":"11104","recipient_telephone":"7186094425","recipient_email":"","pieces":"1","shipment_reference":"test shipment","ship_reason":"sold","collection_date":"14-09-2017","hazardous":"N","special_instructions":"","display_sender_email":"gmcbroom@antrim.ifsgroup.com","display_recipient_email":"","display_broker_email":"","other_email":"","bill_shipping":"sender","bill_tax_duty":"recipient","bill_shipping_account":"","bill_tax_duty_account":"","broker":{"company":"","contact":"","address1":"","address2":"","city":"","country_code":"","state":"","postcode":"","telephone":"","email":"","id":"","account":""},"invoice_type":"c","terms_of_sale":"DAP","ultimate_destination_country_code":"US","commercial_invoice_comments":"","dry_ice":{"flag":"","weight_per_package":"","total_weight":""},"alcohol":{"type":"","packaging":"BL","volume":"","quantity":""},"packages":[{"packaging_code":"CTN","weight":"10","length":"10","width":"10","height":"10"}],"documents_description":"BUSINESS DOCUMENTS ONLY","goods_description":"test"}';
    }

    public function testHeading()
    {
        echo "\n******************************************";
        echo "\n             Feature Test";
        echo "\n     Checking Service Availability";
        echo "\n******************************************\n";
        $this->assertEquals(1, 1);
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

    public function test_availability_NI48_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->niAddress, true), $this->mode);
        $this->checkAvailability('2', $availableServices, 'NI48');
    }

    /*
     * **********************************
     *           Carrier IFS
     * **********************************
     */

    /*
      public function test_availability_NI24_Shipment()
      {

      $availableServices = CarrierAPI::getAvailableServices(json_decode($this->niAddress, true), $this->mode);
      $this->checkAvailability('1', $availableServices, $msg);
      }
     */

    private function checkAvailability($targetService, $availableServices, $msg)
    {
        echo "Checking Service : $msg\n";
        $services = [];
        foreach ($availableServices as $service) {
            $services[] = $service['id'];
        }
        $this->assertContains($targetService, $services, 'Service ' . $targetService . ' not available');
    }

    public function test_availability_IE48_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->niAddress, true), $this->mode);
        $this->checkAvailability('2', $availableServices, 'IE48');
    }

    /*
     * **********************************
     *          Carrier Fedex
     * **********************************
     */

    public function test_availability_UK48_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->ukAddress, true), $this->mode);
        $this->checkAvailability('19', $availableServices, 'UK48');
    }

    public function test_availability_Fedex_EU_IP_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->euAddress, true), $this->mode);
        $this->checkAvailability('10', $availableServices, 'Fedex IP EU');
    }

    public function test_availability_Fedex_US_IP_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->usAddress, true), $this->mode);
        $this->checkAvailability('10', $availableServices, 'Fedex IP US');
    }

    /*
     * **********************************
     *           Carrier UPS
     * **********************************
     */

    public function test_availability_UK24_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->ukAddress, true), $this->mode);
        $this->checkAvailability('16', $availableServices, 'Fedex UK24');
    }

    public function test_availability_UPS_EU_std_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->euAddress, true), $this->mode);
        $this->checkAvailability('12', $availableServices, 'UPS STD EU');
    }

    public function test_availability_UPS_EU_IP_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->euAddress, true), $this->mode);
        $this->checkAvailability('11', $availableServices, 'UPS IP EU');
    }

    public function test_availability_UPS_US_IP_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->euAddress, true), $this->mode);
        $this->checkAvailability('11', $availableServices, 'UPS UP US');
    }

    /*
     * **********************************
     *           Carrier DHL
     * **********************************
     */

    public function test_availability_DHL_US_IP_Shipment()
    {
        $availableServices = CarrierAPI::getAvailableServices(json_decode($this->usAddress, true), $this->mode);
        $this->checkAvailability('27', $availableServices, 'DHL IP US');
    }
}
