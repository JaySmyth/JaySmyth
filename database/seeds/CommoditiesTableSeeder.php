<?php

use Illuminate\Database\Seeder;

class CommoditiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["id" => 1,"description" => "Electronic documentation on compact disc","product_code" => "","country_of_manufacture" => "GB","manufacturer" => "","unit_value" => 5,"currency_code" => "GBP","unit_weight" => 0,"weight_uom" => "kg","uom" => "EA","commodity_code" => "8523404500","harmonized_code" => "8523404500","shipping_cost" => 0,"company_id" => 7,"created_at" => "2017-01-20 14 => 44 => 02","updated_at" => "2017-01-20 14 => 44 => 02"];
        $data[] = ["id" => 2,"description" => "CAS 1042695-84-0Cyclopentanecarboxylic acid, 2-[(5","product_code" => "","country_of_manufacture" => "GB","manufacturer" => "","unit_value" => 0,"currency_code" => "GBP","unit_weight" => 0,"weight_uom" => "kg","uom" => "LTR","commodity_code" => "2918999000","harmonized_code" => "2918999000","shipping_cost" => 0,"company_id" => 7,"created_at" => "2017-01-20 14 => 44 => 02","updated_at" => "2017-01-20 14 => 44 => 02"];
        $data[] = ["id" => 3,"description" => "AT1001 API","product_code" => "","country_of_manufacture" => "GB","manufacturer" => "","unit_value" => 81.8,"currency_code" => "GBP","unit_weight" => 4,"weight_uom" => "kg","uom" => "KGM","commodity_code" => "2933","harmonized_code" => "29333900999","shipping_cost" => 0,"company_id" => 7,"created_at" => "2017-01-20 14 => 44 => 02","updated_at" => "2017-01-20 14 => 44 => 02"];
        $data[] = ["id" => 4,"description" => "TAMPER SEAL","product_code" => "","country_of_manufacture" => "GB","manufacturer" => "","unit_value" => 0,"currency_code" => "GBP","unit_weight" => 0,"weight_uom" => "kg","uom" => "EA","commodity_code" => "TAMPER SEAL","harmonized_code" => null,"shipping_cost" => 0,"company_id" => 7,"created_at" => "2017-01-20 14 => 44 => 02","updated_at" => "2017-01-20 14 => 44 => 02"];


        // Modify a few records
        DB::table('commodities')->insert($data);
    }
}
