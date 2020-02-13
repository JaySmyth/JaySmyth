<?php

use Illuminate\Database\Seeder;

class SpecialServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('special_services')->insert([
            ['code' => 'SATDEL', 'name' => 'Saturday Delivery', 'carrier_id' => '2', 'service_id' => '4'],
            ['code' => '930', 'name' => 'Delivery 9:30AM', 'carrier_id' => '2', 'service_id' => '4'],
            ['code' => 'SATDEL', 'name' => 'Saturday Delivery', 'carrier_id' => '3', 'service_id' => '3'],
            ['code' => '930', 'name' => 'Delivery 9:30AM', 'carrier_id' => '3', 'service_id' => '3'],
        ]);
    }
}
