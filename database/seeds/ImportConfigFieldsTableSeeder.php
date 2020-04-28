<?php

use Illuminate\Database\Seeder;

class ImportConfigFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $data[] = ["id" => 1,"name" => "bill_shipping","description" => "Party to bill Freight costs to.","display_order" => 33,"mode_id" => 1];
        $data[] = ["id" => 2,"name" => "bill_shipping_account","description" => "Carrier Account to bill Freight costs to.","display_order" => 34,"mode_id" => 1];
        $data[] = ["id" => 3,"name" => "bill_tax_duty","description" => "Party to bill Duty/ Taxes to.","display_order" => 35,"mode_id" => 1];
        $data[] = ["id" => 4,"name" => "bill_tax_duty_account","description" => "Carrier Account to bill Duty/ Taxes to.","display_order" => 36,"mode_id" => 1];
        $data[] = ["id" => 5,"name" => "commercial_invoice_comments","description" => "Comment to be displayed on Commercial Invoices","display_order" => 37,"mode_id" => 1];
        $data[] = ["id" => 6,"name" => "customs_value","description" => "Customs value","display_order" => 38,"mode_id" => 1];
        $data[] = ["id" => 7,"name" => "customs_value_currency_code","description" => "Customs value currency code","display_order" => 39,"mode_id" => 1];
        $data[] = ["id" => 8,"name" => "dims_uom","description" => "Dimmensions unit of measure","display_order" => 40,"mode_id" => 1];
        $data[] = ["id" => 9,"name" => "documents_description","description" => "Documents Description","display_order" => 41,"mode_id" => 1];
        $data[] = ["id" => 10,"name" => "goods_description","description" => "Goods Description","display_order" => 42,"mode_id" => 1];
        $data[] = ["id" => 12,"name" => "height","description" => "Package Height","display_order" => 62,"mode_id" => 1];
        $data[] = ["id" => 13,"name" => "length","description" => "Package Length","display_order" => 60,"mode_id" => 1];
        $data[] = ["id" => 14,"name" => "pieces","description" => "Pieces (Packages)","display_order" => 51,"mode_id" => 1];
        $data[] = ["id" => 15,"name" => "product_code","description" => "Product Code","display_order" => 43,"mode_id" => 1];
        $data[] = ["id" => 16,"name" => "recipient_address1","description" => "Recipient Address1","display_order" => 23,"mode_id" => 1];
        $data[] = ["id" => 17,"name" => "recipient_address2","description" => "Recipient Address2","display_order" => 24,"mode_id" => 1];
        $data[] = ["id" => 18,"name" => "recipient_address3","description" => "Recipient Address3","display_order" => 25,"mode_id" => 1];
        $data[] = ["id" => 19,"name" => "recipient_city","description" => "Recipient City","display_order" => 26,"mode_id" => 1];
        $data[] = ["id" => 20,"name" => "recipient_company_name","description" => "Recipient Company Name","display_order" => 22,"mode_id" => 1];
        $data[] = ["id" => 21,"name" => "recipient_country_code","description" => "Recipient Country Code","display_order" => 29,"mode_id" => 1];
        $data[] = ["id" => 22,"name" => "recipient_email","description" => "Recipient Email Address","display_order" => 30,"mode_id" => 1];
        $data[] = ["id" => 23,"name" => "recipient_name","description" => "Recipient Contact Name","display_order" => 21,"mode_id" => 1];
        $data[] = ["id" => 24,"name" => "recipient_postcode","description" => "Recipient Postcode","display_order" => 28,"mode_id" => 1];
        $data[] = ["id" => 25,"name" => "recipient_state","description" => "Recipient State","display_order" => 27,"mode_id" => 1];
        $data[] = ["id" => 26,"name" => "recipient_telephone","description" => "Recipient Telephone","display_order" => 31,"mode_id" => 1];
        $data[] = ["id" => 27,"name" => "recipient_type","description" => "Recipient Type","display_order" => 32,"mode_id" => 1];
        $data[] = ["id" => 28,"name" => "sender_address1","description" => "Sender Address1","display_order" => 3,"mode_id" => 1];
        $data[] = ["id" => 29,"name" => "sender_address2","description" => "Sender Address2","display_order" => 4,"mode_id" => 1];
        $data[] = ["id" => 30,"name" => "sender_address3","description" => "Sender Address3","display_order" => 5,"mode_id" => 1];
        $data[] = ["id" => 31,"name" => "sender_city","description" => "Sender City","display_order" => 6,"mode_id" => 1];
        $data[] = ["id" => 32,"name" => "sender_company_name","description" => "Sender Company Name","display_order" => 2,"mode_id" => 1];
        $data[] = ["id" => 33,"name" => "sender_country_code","description" => "Sender Country Code","display_order" => 9,"mode_id" => 1];
        $data[] = ["id" => 34,"name" => "sender_email","description" => "Sender Email","display_order" => 10,"mode_id" => 1];
        $data[] = ["id" => 35,"name" => "sender_name","description" => "Sender Contact Name","display_order" => 1,"mode_id" => 1];
        $data[] = ["id" => 36,"name" => "sender_postcode","description" => "Sender Postcode","display_order" => 8,"mode_id" => 1];
        $data[] = ["id" => 37,"name" => "sender_state","description" => "Sender State","display_order" => 7,"mode_id" => 1];
        $data[] = ["id" => 38,"name" => "sender_telephone","description" => "Sender Telephone","display_order" => 11,"mode_id" => 1];
        $data[] = ["id" => 39,"name" => "sender_type","description" => "Sender Type","display_order" => 12,"mode_id" => 1];
        $data[] = ["id" => 40,"name" => "service_code","description" => "Service Code","display_order" => 45,"mode_id" => 1];
        $data[] = ["id" => 41,"name" => "ship_reason","description" => "Reason for Shipment","display_order" => 46,"mode_id" => 1];
        $data[] = ["id" => 42,"name" => "shipment_reference","description" => "Shipment Reference","display_order" => 47,"mode_id" => 1];
        $data[] = ["id" => 43,"name" => "special_instructions","description" => "Special Instructions","display_order" => 48,"mode_id" => 1];
        $data[] = ["id" => 44,"name" => "terms_of_sale","description" => "Terms of Sale","display_order" => 49,"mode_id" => 1];
        $data[] = ["id" => 45,"name" => "ultimate_destination_country_code","description" => "Country of ultimate destination","display_order" => 50,"mode_id" => 1];
        $data[] = ["id" => 46,"name" => "volumetric_weight","description" => "Volumetric Weight","display_order" => 63,"mode_id" => 1];
        $data[] = ["id" => 47,"name" => "weight","description" => "Weight","display_order" => 52,"mode_id" => 1];
        $data[] = ["id" => 48,"name" => "weight_uom","description" => "Weight Unit of measure","display_order" => 53,"mode_id" => 1];
        $data[] = ["id" => 49,"name" => "width","description" => "Package Width","display_order" => 61,"mode_id" => 1];
        $data[] = ["id" => 50,"name" => "product_quantity","description" => "Product Quantity","display_order" => 44,"mode_id" => 0];
        $data[] = ["id" => 51,"name" => "ignore","description" => "Ignore","display_order" => 0,"mode_id" => 1];

        // Modify a few records
        DB::table('import_config_fields')->insert($data);
    }
}
