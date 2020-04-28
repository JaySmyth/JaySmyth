<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode('
        [
            {"id":1,"company_name":"IFS Global Logistics","address1":"Ifs Logistics Park","address2":"Seven Mile Straight","address3":"","city":"Antrim","state":"County Antrim","postcode":"BT41 4QE","country_code":"GB","address_type":"c","telephone":"02894464211","email":"","site_name":"IFS Global Logistics","company_code":"B8NDJQ","scs_code":"0101172","eori":"","group_account":"","bulk_collections":"0","carrier_choice":"cost","vat_exempt":"0","enabled":"1","upload_only":"0","testing":"0","notes":"","legacy":"0","legacy_pricing":"0","legacy_invoice":"0","print_format_id":1,"sale_id":1,"depot_id":1,"shipper_type_override":"","recipient_type_override":"","master_label":"1","commercial_invoice":"1","plt_enabled":"1","full_dutyandvat":"0","localisation_id":1,"pricing_date_offset":0,"created_at":"2016-11-30 10:07:43","updated_at":"2019-12-20 16:12:22"},
            {"id":2,"company_name":"Test Company 1","address1":"Ifs Logistics Park","address2":"Cairn Industrial Estate","address3":"","city":"Portadown","state":"County Armagh","postcode":"BT62 5ES","country_code":"GB","address_type":"c","telephone":"02835825825","email":"","site_name":"Test Company 1 Company","company_code":"ZERBGV","scs_code":"1234567","eori":null,"group_account":null,"bulk_collections":"0","carrier_choice":"cost","vat_exempt":"0","enabled":"1","upload_only":"0","testing":"0","notes":null,"legacy":"0","legacy_pricing":"0","legacy_invoice":"0","print_format_id":1,"sale_id":1,"depot_id":4,"shipper_type_override":"","recipient_type_override":"","master_label":"1","commercial_invoice":"0","plt_enabled":"1","full_dutyandvat":"0","localisation_id":1,"pricing_date_offset":0,"created_at":"2016-11-30 10:07:53","updated_at":"2016-11-30 10:10:59"},
            {"id":3,"company_name":"Test Company 2","address1":"Seven Mile Straight","address2":"","address3":"","city":"Antrim","state":"County Antrim","postcode":"BT41 4QE","country_code":"GB","address_type":"c","telephone":"02894464211","email":"","site_name":"Test Company 2","company_code":"LPAZU7","scs_code":"0142325","eori":null,"group_account":null,"bulk_collections":"0","carrier_choice":"cost","vat_exempt":"0","enabled":"1","upload_only":"0","testing":"0","notes":"","legacy":"0","legacy_pricing":"0","legacy_invoice":"0","print_format_id":2,"sale_id":2,"depot_id":1,"shipper_type_override":"","recipient_type_override":"","master_label":"1","commercial_invoice":"1","plt_enabled":"1","full_dutyandvat":"0","localisation_id":1,"pricing_date_offset":0,"created_at":"2016-11-30 10:07:54","updated_at":"2018-03-12 15:06:40"},
            {"id":4,"company_name":"Test Company 3","address1":"Stockmans","address2":"Way","address3":"","city":"Kilrea","state":"County Londonderry","postcode":"BT43 Y3E","country_code":"GB","address_type":"c","telephone":"02829541152","email":"","site_name":"Test Company 3","company_code":"QGY0VK","scs_code":"1234567","eori":null,"group_account":"","bulk_collections":"0","carrier_choice":"cost","vat_exempt":"0","enabled":"1","upload_only":"0","testing":"0","notes":"","legacy":"0","legacy_pricing":"0","legacy_invoice":"0","print_format_id":1,"sale_id":1,"depot_id":4,"shipper_type_override":"","recipient_type_override":"","master_label":"1","commercial_invoice":"0","plt_enabled":"1","full_dutyandvat":"0","localisation_id":1,"pricing_date_offset":0,"created_at":"2016-11-30 10:10:52","updated_at":"2018-10-10 10:03:47"}]', true);

        \DB::table('companies')->delete();

        \DB::table('companies')->insert($data);
    }
}
