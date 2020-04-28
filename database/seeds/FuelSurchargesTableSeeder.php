<?php

use Illuminate\Database\Seeder;

class FuelSurchargesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["id" => 38,"carrier_id" => 1,"service_code" => "ie24","fuel_percent" => 3.9,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => null,"updated_at" => null];
        $data[] = ["id" => 1394,"carrier_id" => 1,"service_code" => "ie48","fuel_percent" => 8.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 1392,"carrier_id" => 1,"service_code" => "ni24","fuel_percent" => 8.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 1131,"carrier_id" => 1,"service_code" => "ni24p","fuel_percent" => 9,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-10-11 08 => 20 => 49","updated_at" => "2019-10-11 08 => 20 => 49"];
        $data[] = ["id" => 1393,"carrier_id" => 1,"service_code" => "ni48","fuel_percent" => 8.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 1407,"carrier_id" => 2,"service_code" => "ie","fuel_percent" => 9.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-03 10 => 57 => 08","updated_at" => "2020-04-03 10 => 57 => 08"];
        $data[] = ["id" => 1405,"carrier_id" => 2,"service_code" => "ip","fuel_percent" => 9.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-03 10 => 57 => 08","updated_at" => "2020-04-03 10 => 57 => 08"];
        $data[] = ["id" => 1406,"carrier_id" => 2,"service_code" => "ipf","fuel_percent" => 9.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-03 10 => 57 => 08","updated_at" => "2020-04-03 10 => 57 => 08"];
        $data[] = ["id" => 1198,"carrier_id" => 2,"service_code" => "uk24r","fuel_percent" => 9,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-10-30 16 => 15 => 35","updated_at" => "2019-10-30 16 => 15 => 35"];
        $data[] = ["id" => 1390,"carrier_id" => 2,"service_code" => "uk48","fuel_percent" => 8.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 51","updated_at" => "2020-03-31 15 => 17 => 51"];
        $data[] = ["id" => 177,"carrier_id" => 2,"service_code" => "uk48p","fuel_percent" => 7,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2017-09-15 14 => 52 => 51","updated_at" => "2017-09-15 14 => 52 => 51"];
        $data[] = ["id" => 663,"carrier_id" => 2,"service_code" => "uk48r","fuel_percent" => 7,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-12-03 11 => 21 => 00","updated_at" => "2018-12-03 11 => 21 => 00"];
        $data[] = ["id" => 795,"carrier_id" => 2,"service_code" => "usg","fuel_percent" => 6.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-04-23 08 => 34 => 38","updated_at" => "2018-04-23 08 => 34 => 38"];
        $data[] = ["id" => 1408,"carrier_id" => 3,"service_code" => "eu24","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 1409,"carrier_id" => 3,"service_code" => "ip","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 1410,"carrier_id" => 3,"service_code" => "ipu","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 1411,"carrier_id" => 3,"service_code" => "std","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 1413,"carrier_id" => 3,"service_code" => "uk24","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 474,"carrier_id" => 3,"service_code" => "uk24r","fuel_percent" => 18.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-06-15 14 => 06 => 34","updated_at" => "2018-06-15 14 => 06 => 34"];
        $data[] = ["id" => 1412,"carrier_id" => 3,"service_code" => "uk48r","fuel_percent" => 10.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-10 14 => 21 => 39","updated_at" => "2020-04-10 14 => 21 => 39"];
        $data[] = ["id" => 892,"carrier_id" => 4,"service_code" => "ie","fuel_percent" => 17.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-04-19 15 => 44 => 14","updated_at" => "2019-04-19 15 => 44 => 14"];
        $data[] = ["id" => 1403,"carrier_id" => 4,"service_code" => "ie24","fuel_percent" => 9.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-03 10 => 57 => 08","updated_at" => "2020-04-03 10 => 57 => 08"];
        $data[] = ["id" => 434,"carrier_id" => 4,"service_code" => "ie48","fuel_percent" => 7.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-03-01 09 => 55 => 28","updated_at" => "2018-03-01 09 => 55 => 28"];
        $data[] = ["id" => 1404,"carrier_id" => 4,"service_code" => "std","fuel_percent" => 9.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-03 10 => 57 => 08","updated_at" => "2020-04-03 10 => 57 => 08"];
        $data[] = ["id" => 907,"carrier_id" => 4,"service_code" => "uk24","fuel_percent" => 17.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-04-19 15 => 44 => 14","updated_at" => "2019-04-19 15 => 44 => 14"];
        $data[] = ["id" => 908,"carrier_id" => 4,"service_code" => "uk24r","fuel_percent" => 17.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-04-19 15 => 44 => 14","updated_at" => "2019-04-19 15 => 44 => 14"];
        $data[] = ["id" => 1395,"carrier_id" => 5,"service_code" => "ip","fuel_percent" => 15.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 1396,"carrier_id" => 5,"service_code" => "std","fuel_percent" => 15.5,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 1106,"carrier_id" => 5,"service_code" => "uk24","fuel_percent" => 19.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-09-20 13 => 53 => 54","updated_at" => "2019-09-20 13 => 53 => 54"];
        $data[] = ["id" => 423,"carrier_id" => 5,"service_code" => "uk24r","fuel_percent" => 9.25,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-04-27 14 => 28 => 32","updated_at" => "2018-04-27 14 => 28 => 32"];
        $data[] = ["id" => 1391,"carrier_id" => 10,"service_code" => "uk48p","fuel_percent" => 8.75,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 52","updated_at" => "2020-03-31 15 => 17 => 52"];
        $data[] = ["id" => 352,"carrier_id" => 11,"service_code" => "usps","fuel_percent" => 0,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2018-03-23 15 => 48 => 16","updated_at" => "2018-03-23 15 => 48 => 16"];
        $data[] = ["id" => 1414,"carrier_id" => 12,"service_code" => "usds","fuel_percent" => 6,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-04-20 10 => 16 => 25","updated_at" => "2020-04-20 10 => 16 => 25"];
        $data[] = ["id" => 1389,"carrier_id" => 14,"service_code" => "ie48","fuel_percent" => 4.8,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2020-03-31 15 => 17 => 51","updated_at" => "2020-03-31 15 => 17 => 51"];
        $data[] = ["id" => 1119,"carrier_id" => 14,"service_code" => "ni48","fuel_percent" => 8,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-09-27 15 => 39 => 47","updated_at" => "2019-09-27 15 => 39 => 47"];
        $data[] = ["id" => 1144,"carrier_id" => 15,"service_code" => "ni48","fuel_percent" => 8.8,"from_date" => date('Y-m-d'),"to_date" => "2099-12-31","created_at" => "2019-09-27 15 => 39 => 47","updated_at" => "2019-09-27 15 => 39 => 47"];

        // Modify a few records
        DB::table('fuel_surcharges')->insert($data);
    }
}
