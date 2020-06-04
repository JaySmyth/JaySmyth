<?php

use Illuminate\Database\Seeder;

class ImportConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $data[] = [
            "id" => 20,
            "user_id" => 0,
            "company_id" => 508,
            "company_name" => "*TEMPLATE*",
            "mode_id" => 1,
            "column0" => "shipment_reference",
            "column1" => "recipient_name",
            "column2" => "recipient_company_name",
            "column3" => "recipient_address1",
            "column4" => "recipient_address2",
            "column5" => "recipient_city",
            "column6" => "recipient_state",
            "column7" => "recipient_postcode",
            "column8" => "recipient_country_code",
            "column9" => "recipient_email",
            "column10" => "recipient_telephone",
            "column11" => "pieces",
            "column12" => "weight",
            "column13" => "length",
            "column14" => "width",
            "column15" => "height",
            "column16" => "service_code",
            "column17" => "customs_value",
            "column18" => "product_code",
            "column19" => "product_quantity",
            "column20" => "",
            "column21" => "",
            "column22" => "",
            "column23" => "",
            "column24" => "",
            "column25" => "",
            "column26" => "",
            "column27" => "",
            "column28" => "",
            "column29" => "",
            "column30" => "",
            "column31" => "",
            "column32" => "",
            "column33" => "",
            "column34" => "",
            "column35" => "",
            "column36" => "",
            "column37" => "",
            "column38" => "",
            "column39" => "",
            "column40" => "",
            "column41" => "",
            "column42" => "",
            "column43" => "",
            "column44" => "",
            "column45" => "",
            "column46" => "",
            "column47" => "",
            "column48" => "",
            "column49" => "",
            "column50" => "",
            "column51" => "",
            "column52" => null,
            "fields" => "",
            "delim" => "comma",
            "enabled" => "0",
            "test_mode" => "0",
            "start_row" => 2,
            "resp_fields" => "",
            "resp_headings" => "0",
            "ship_ref_sep" => "",
            "default_service" => "uk48",
            "default_terms" => "DDP",
            "default_pieces" => 1,
            "default_goods_description" => "Miscellaneous",
            "default_recipient_name" => "",
            "default_recipient_telephone" => null,
            "default_recipient_email" => "",
            "default_weight" => 0.00,
            "default_customs_value" => 0.00,
            "terms" => null,
            "cc_import_results_email" => "",
            "created_at" => "2016-11-30 15 => 44 => 43",
            "updated_at" => "2019-01-24 15 => 22 => 52",
            "third_party" => "0"
        ];

        // Modify a few records
        DB::table('import_configs')->insert($data);
    }
}
