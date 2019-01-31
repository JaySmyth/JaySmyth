<?php

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reports')->insert([
            ['name' => 'Shippers Report', 'description' => 'Details shipments by company', 'route' => 'shippers', 'permission' => 'view_shippers_report', 'criteria' => '', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'Non Shippers Report', 'description' => 'Details companies that have not shipped', 'route' => 'non-shippers', 'permission' => 'view_non_shippers_report', 'criteria' => '', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'Scanning Report', 'description' => 'Displays details on package scanning', 'route' => 'scanning', 'permission' => 'view_scanning_report', 'criteria' => '', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'DIM Report', 'description' => 'Displays details on package package dimensions', 'route' => 'dims', 'permission' => 'view_dim_report', 'criteria' => '', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'FedEx Customs (non stat)', 'description' => 'Breakdown of FedEx customs clearance', 'route' => 'fedex-customs', 'permission' => 'view_fedex_customs_report', 'criteria' => '{"carrier_id":2,"services":[7,9,10,11],"customs_value_low":0.00,"customs_value_high":0.01}', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'FedEx Customs (low value)', 'description' => 'Breakdown of FedEx customs clearance', 'route' => 'fedex-customs', 'permission' => 'view_fedex_customs_report', 'criteria' => '{"carrier_id":2,"services":[7,9,10,11],"customs_value_low":0.02,"customs_value_high":749.99}', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'FedEx Customs (high value)', 'description' => 'Breakdown of FedEx customs clearance', 'route' => 'fedex-customs', 'permission' => 'view_fedex_customs_report', 'criteria' => '{"carrier_id":2,"services":[7,9,10,11],"customs_value_low":750.00,"customs_value_high":1999.99}', 'depot_id' => 1, 'mode_id' => 1],
            ['name' => 'FedEx Customs (individual entry)', 'description' => 'Breakdown of FedEx customs clearance', 'route' => 'fedex-customs', 'permission' => 'view_fedex_customs_report', 'criteria' => '{"carrier_id":2,"services":[7,9,10,11],"customs_value_low":2000.00,"customs_value_high":999999}', 'depot_id' => 1, 'mode_id' => 1],
        ]);
    }

}
