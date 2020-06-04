<?php

use Illuminate\Database\Seeder;

class DomesticRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["rate_id" => 1,"service" => "ni24","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 0,"notional" => 0,"area" => "ni","from_date" => "2013-07-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 2,"service" => "ni48","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 0,"notional" => 0,"area" => "ni","from_date" => "2013-07-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 5,"service" => "ie48","packaging_code" => "Package","first" => 5.85,"others" => 5.85,"notional_weight" => 0,"notional" => 0,"area" => "ie","from_date" => "2013-07-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 9,"service" => "uk48","packaging_code" => "Package","first" => 3.9372,"others" => 1.9788,"notional_weight" => 25,"notional" => 8.67,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 9,"service" => "uk48","packaging_code" => "Package","first" => 3.9372,"others" => 1.9788,"notional_weight" => 25,"notional" => 8.67,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 9,"service" => "uk48","packaging_code" => "Package","first" => 12.0156,"others" => 7.8234,"notional_weight" => 25,"notional" => 8.67,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 9,"service" => "uk48","packaging_code" => "Package","first" => 14.6778,"others" => 9.537,"notional_weight" => 25,"notional" => 8.67,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 9,"service" => "uk48","packaging_code" => "Package","first" => 27.4482,"others" => 17.8296,"notional_weight" => 25,"notional" => 8.67,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 12.0054,"others" => 1.9584,"notional_weight" => 25,"notional" => 8.2212,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 12.0054,"others" => 1.9584,"notional_weight" => 25,"notional" => 8.2212,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 20.0022,"others" => 7.7214,"notional_weight" => 25,"notional" => 8.2212,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 22.6338,"others" => 9.4452,"notional_weight" => 25,"notional" => 8.2212,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 35.2818,"others" => 17.6562,"notional_weight" => 25,"notional" => 8.2212,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 12.0054,"others" => 1.9584,"notional_weight" => 25,"notional" => 8.2212,"area" => "ie","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 13,"service" => "uk48r","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 8.2212,"area" => "ni","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 20,"service" => "uk48","packaging_code" => "Package","first" => 3.4374,"others" => 2.754,"notional_weight" => 25,"notional" => 8.3028,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 20,"service" => "uk48","packaging_code" => "Package","first" => 3.4374,"others" => 2.754,"notional_weight" => 25,"notional" => 8.3028,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 20,"service" => "uk48","packaging_code" => "Package","first" => 11.5566,"others" => 7.5174,"notional_weight" => 25,"notional" => 8.3028,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 20,"service" => "uk48","packaging_code" => "Package","first" => 14.2188,"others" => 9.2412,"notional_weight" => 25,"notional" => 8.3028,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 20,"service" => "uk48","packaging_code" => "Package","first" => 26.5404,"others" => 17.238,"notional_weight" => 25,"notional" => 8.3028,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 21,"service" => "uk24r","packaging_code" => "Package","first" => 3.9372,"others" => 1.9788,"notional_weight" => 25,"notional" => 8.5476,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 21,"service" => "uk24r","packaging_code" => "Package","first" => 3.9372,"others" => 1.9788,"notional_weight" => 25,"notional" => 8.5476,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 21,"service" => "uk24r","packaging_code" => "Package","first" => 12.0156,"others" => 7.8234,"notional_weight" => 25,"notional" => 8.5476,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 21,"service" => "uk24r","packaging_code" => "Package","first" => 14.6778,"others" => 9.537,"notional_weight" => 25,"notional" => 8.5476,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 21,"service" => "uk24r","packaging_code" => "Package","first" => 27.4482,"others" => 17.8296,"notional_weight" => 25,"notional" => 8.5476,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 22,"service" => "uk24r","packaging_code" => "Package","first" => 3.4374,"others" => 2.754,"notional_weight" => 25,"notional" => 8.3028,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 22,"service" => "uk24r","packaging_code" => "Package","first" => 3.4374,"others" => 2.754,"notional_weight" => 25,"notional" => 8.3028,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 22,"service" => "uk24r","packaging_code" => "Package","first" => 11.5566,"others" => 7.5174,"notional_weight" => 25,"notional" => 8.3028,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 22,"service" => "uk24r","packaging_code" => "Package","first" => 14.2188,"others" => 9.2412,"notional_weight" => 25,"notional" => 8.3028,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 22,"service" => "uk24r","packaging_code" => "Package","first" => 26.5404,"others" => 17.238,"notional_weight" => 25,"notional" => 8.3028,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 23,"service" => "uk48r","packaging_code" => "Package","first" => 4.1514,"others" => 2.2848,"notional_weight" => 25,"notional" => 8.5476,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 23,"service" => "uk48r","packaging_code" => "Package","first" => 4.1514,"others" => 2.2848,"notional_weight" => 25,"notional" => 8.5476,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 23,"service" => "uk48r","packaging_code" => "Package","first" => 11.3628,"others" => 7.395,"notional_weight" => 25,"notional" => 8.5476,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 23,"service" => "uk48r","packaging_code" => "Package","first" => 8.3436,"others" => 5.4162,"notional_weight" => 25,"notional" => 8.5476,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 23,"service" => "uk48r","packaging_code" => "Package","first" => 20.9712,"others" => 13.6272,"notional_weight" => 25,"notional" => 8.5476,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 24,"service" => "uk48r","packaging_code" => "Package","first" => 3.417,"others" => 2.7438,"notional_weight" => 25,"notional" => 8.3028,"area" => "2","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 24,"service" => "uk48r","packaging_code" => "Package","first" => 3.417,"others" => 2.7438,"notional_weight" => 25,"notional" => 8.3028,"area" => "3","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 24,"service" => "uk48r","packaging_code" => "Package","first" => 10.9242,"others" => 7.0992,"notional_weight" => 25,"notional" => 8.3028,"area" => "4","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 24,"service" => "uk48r","packaging_code" => "Package","first" => 8.0886,"others" => 5.253,"notional_weight" => 25,"notional" => 8.3028,"area" => "5","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 24,"service" => "uk48r","packaging_code" => "Package","first" => 20.1552,"others" => 13.0968,"notional_weight" => 25,"notional" => 8.3028,"area" => "8","from_date" => "2020-01-06","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 65,"service" => "ie48","packaging_code" => "Package","first" => 5.85,"others" => 5.25,"notional_weight" => 0,"notional" => 0,"area" => "ie","from_date" => "2019-10-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 65,"service" => "ni48","packaging_code" => "Package","first" => 4.15,"others" => 3.75,"notional_weight" => 0,"notional" => 0,"area" => "ni","from_date" => "2019-10-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 500,"service" => "ie48","packaging_code" => "Package","first" => 14.3068,"others" => 8.7651,"notional_weight" => 0,"notional" => 0,"area" => "IE","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "ni24","packaging_code" => "Package","first" => 10.4728,"others" => 7.6862,"notional_weight" => 25,"notional" => 10.4728,"area" => "NI","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "ni48","packaging_code" => "Package","first" => 7.9055,"others" => 4.4674,"notional_weight" => 25,"notional" => 10.4728,"area" => "NI","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "uk48","packaging_code" => "Package","first" => 20.6186,"others" => 7.2107,"notional_weight" => 25,"notional" => 10.4724,"area" => "2","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "uk48","packaging_code" => "Package","first" => 22.7156,"others" => 7.9213,"notional_weight" => 25,"notional" => 10.4724,"area" => "3","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "uk48","packaging_code" => "Package","first" => 28.214,"others" => 18.0676,"notional_weight" => 25,"notional" => 10.4724,"area" => "4","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "uk48","packaging_code" => "Package","first" => 24.8125,"others" => 16.3669,"notional_weight" => 25,"notional" => 10.4724,"area" => "5","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 500,"service" => "uk48","packaging_code" => "Package","first" => 46.7941,"others" => 25.6511,"notional_weight" => 25,"notional" => 10.4724,"area" => "8","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 551,"service" => "ni24","packaging_code" => "Package","first" => 7.6314,"others" => 7.6314,"notional_weight" => 25,"notional" => 9.8164,"area" => "ni","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 551,"service" => "ni24p","packaging_code" => "PAL","first" => 35,"others" => 35,"notional_weight" => 0,"notional" => 0,"area" => "ni","from_date" => "2020-01-01","to_date" => "2099-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "ie24","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 0,"notional" => 0,"area" => "ie","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "ni24","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 0,"notional" => 0,"area" => "ni","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "uk24r","packaging_code" => "Package","first" => 5.4338,"others" => 2.8463,"notional_weight" => 25,"notional" => 10.4742,"area" => "2","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "uk24r","packaging_code" => "Package","first" => 5.4338,"others" => 2.8463,"notional_weight" => 25,"notional" => 10.4742,"area" => "3","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "uk24r","packaging_code" => "Package","first" => 13.4861,"others" => 8.7768,"notional_weight" => 25,"notional" => 10.4742,"area" => "4","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "uk24r","packaging_code" => "Package","first" => 10.557,"others" => 5.175,"notional_weight" => 25,"notional" => 10.4742,"area" => "5","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1029,"service" => "uk24r","packaging_code" => "Package","first" => 30.9672,"others" => 20.1101,"notional_weight" => 25,"notional" => 10.4742,"area" => "8","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "ie48","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 0,"notional" => 0,"area" => "ie","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "ni48","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 0,"area" => "ni","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "uk48r","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 0,"area" => "2","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "uk48r","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 0,"area" => "3","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "uk48r","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 0,"area" => "4","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "uk48r","packaging_code" => "Package","first" => 10.557,"others" => 5.175,"notional_weight" => 25,"notional" => 10.4742,"area" => "5","from_date" => "2020-01-01","to_date" => "2020-12-31"];
        $data[] = ["rate_id" => 1030,"service" => "uk48r","packaging_code" => "Package","first" => 0,"others" => 0,"notional_weight" => 25,"notional" => 0,"area" => "8","from_date" => "2020-01-01","to_date" => "2020-12-31"];


        // Modify a few records
        DB::table('domestic_rates')->insert($data);
    }
}
