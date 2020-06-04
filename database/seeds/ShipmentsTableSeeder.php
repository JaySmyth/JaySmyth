<?php

use Illuminate\Database\Seeder;

class ShipmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode('[{"id":1,"consignment_number":"10008945591","carrier_consignment_number":"484340955945","carrier_tracking_number":"484340955945","shipment_reference":"25532950","order_number":null,"token":"p1GrYPTL2RJt","source":null,"pieces":1,"weight":2.5,"weight_uom":"kg","supplied_weight":null,"dims_uom":"cm","volumetric_weight":18,"volumetric_divisor":5000,"supplied_volumetric_weight":null,"customs_value":168,"customs_value_currency_code":"GBP","documents_description":"","goods_description":"Clothes","special_instructions":"","max_dimension":90,"on_hold":"0","received":"1","created_sent":"0","received_sent":"0","pallet":"0","delivered":"1","pod_signature":"H.HAMMOND","pod_image":null,"pod_sent":"0","scs_job_number":"IFCUKJ01056038","invoicing_status":1,"shipping_charge":5.17,"fuel_charge":0.43,"sales_currency":"GBP","shipping_cost":4.21,"fuel_cost":0.35,"cost_currency":"GBP","quoted":"{\"shipping_cost\":4.21,\"shipping_charge\":5.17,\"fuel_cost\":0.35,\"fuel_charge\":0.43,\"cost_vat_amount\":0.84,\"cost_vat_code\":\"1\",\"cost_currency\":\"GBP\",\"sales_vat_amount\":1.03,\"sales_vat_code\":\"1\",\"sales_currency\":\"GBP\",\"costs\":[{\"code\":\"FRT\",\"description\":\"1 Package(s) to Area 2\",\"value\":\"3.86\"},{\"code\":\"FUEL\",\"description\":\"Fuel Surcharge\",\"value\":\"0.35\"}],\"costs_detail\":[],\"sales\":[{\"code\":\"FRT\",\"description\":\"1 Package(s) to Area 2\",\"value\":\"4.74\"},{\"code\":\"FUEL\",\"description\":\"Fuel Surcharge\",\"value\":\"0.43\"}],\"sales_debug\":[],\"sales_detail\":[],\"costs_zone\":\"2\",\"sales_zone\":\"2\",\"costs_model\":\"domestic\",\"sales_model\":\"domestic\",\"costs_rate_id\":9,\"sales_rate_id\":1021,\"costs_packaging\":\"Package\",\"sales_packaging\":\"Package\",\"errors\":[]}","carrier_pickup_required":"0","alcohol_type":null,"alcohol_packaging":null,"alcohol_volume":null,"alcohol_quantity":null,"dry_ice_flag":0,"dry_ice_weight_per_package":null,"dry_ice_total_weight":0,"hazardous":null,"external_tracking_url":null,"sender_type":"c","sender_name":".","sender_company_name":"Douglas & Grahame Ltd","sender_address1":"15 Sloefield Drive","sender_address2":"","sender_address3":"","sender_city":"Carrickfergus","sender_state":"County Antrim","sender_postcode":"BT38 8GX","sender_country_code":"GB","sender_telephone":"02893327777","sender_email":"thomas.jamison@douglasandgrahame.com","recipient_type":"c","recipient_name":"","recipient_company_name":"Terrys South West Ltd","recipient_address1":"T/a Fitzwell Collections","recipient_address2":"9 High Street","recipient_address3":"Barnstaple","recipient_city":"BARNSTAPLE","recipient_state":"Devon","recipient_postcode":"EX31 1BG","recipient_country_code":"GB","recipient_telephone":"01271 343 983","recipient_email":"","ship_reason":"sold","terms_of_sale":"ddp","invoice_type":null,"ultimate_destination_country_code":null,"eori":null,"commercial_invoice_comments":null,"bill_shipping":"sender","bill_tax_duty":"sender","bill_shipping_account":"811732648","bill_tax_duty_account":"811732648","broker_name":null,"broker_company_name":null,"broker_address1":null,"broker_address2":null,"broker_city":null,"broker_state":null,"broker_postcode":null,"broker_country_code":null,"broker_telephone":null,"broker_email":null,"broker_id":null,"broker_account":null,"legacy":"0","form_values":null,"user_id":395,"company_id":153,"status_id":6,"mode_id":1,"department_id":10,"carrier_id":2,"service_id":19,"route_id":1,"depot_id":1,"manifest_id":11111,"invoice_run_id":595,"collection_date":"2019-06-05 00:00:00","ship_date":"2019-06-05 16:35:46","delivery_date":"2019-06-07 09:32:00","created_at":"2019-06-05 15:24:57","updated_at":"2019-06-07 09:48:10","insurance_value":0,"lithium_batteries":0}]', true);

        \DB::table('shipments')->delete();

        \DB::table('shipments')->insert($data);
    }
}
