<?php

use Illuminate\Database\Seeder;

class CarrierAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_string = '[{"id":1,"carrier_id":3,"account":"922E2A","company_name":"IFS Courier Express","address1":"IFS Logistics Park","address2":"Seven Mile Straight","address3":"","city":"Antrim","state":"Antrim","postcode":"BT41 4QE","country_code":"GB","telephone":"02894464211","vat_number":"722964815"},{"id":2,"carrier_id":3,"account":"0230YF","company_name":"C.T. Freight (UK) Ltd","address1":"Unit 16, Saxon Way","address2":"","address3":"","city":"Harmondsworth","state":"Middlesex","postcode":"UB7 0LW","country_code":"GB","telephone":"+44 (028) 9446","vat_number":null},{"id":3,"carrier_id":3,"account":"5XA406","company_name":"Moto Legends Distribution Ltd","address1":"Office 3, Edgefield House","address2":"Vicarage Lane","address3":"","city":"North Muskham","state":"Notts","postcode":"NG23 6ES","country_code":"GB","telephone":"+44 (028) 9446","vat_number":null},{"id":4,"carrier_id":3,"account":"Y19Y23","company_name":"Bibby Distribution Ltd (Corby)","address1":"3 Princewood Road","address2":"","address3":null,"city":"Corby","state":"Northamptonshire","postcode":"NN17 4AP","country_code":"GB","telephone":"+44 (028) 9446","vat_number":null},{"id":5,"carrier_id":3,"account":"Y73R72","company_name":"CMASS USA LLC","address1":"20 E Sunrise HWY","address2":"Valley Stream","address3":"","city":"New York","state":"New York","postcode":"11581","country_code":"US","telephone":"+44 (028) 9446","vat_number":null}]';

        $accounts = json_decode($json_string, true);

        // Modify a few records
        DB::table('carrier_accounts')->insert($accounts);
    }
}
