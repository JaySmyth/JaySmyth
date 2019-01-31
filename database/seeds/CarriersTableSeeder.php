<?php

use Illuminate\Database\Seeder;

class CarriersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert some dummy records
        DB::table('carriers')->insert([
            ['id' => '1', 'code' => 'ifs', 'name' => 'IFS'],
            ['id' => '2', 'code' => 'fedex', 'name' => 'FedEx'],
            ['id' => '3', 'code' => 'ups', 'name' => 'UPS'],
            ['id' => '4', 'code' => 'tnt', 'name' => 'TNT'],
            ['id' => '5', 'code' => 'dhl', 'name' => 'DHL'],
            ['id' => '6', 'code' => 'rm', 'name' => 'Royal Mail'],
            ['id' => '7', 'code' => 'pf', 'name' => 'Parcelforce'],
            ['id' => '8', 'code' => 'ps', 'name' => 'Pallet Service Carrier'],
        ]);
    }

}
