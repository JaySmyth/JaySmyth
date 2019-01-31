<?php

use Illuminate\Database\Seeder;

class PackagingTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          // Define IFS Packaging
        DB::table('packaging_types')->insert([
            ['code' => 'CTN', 'name' => 'Carton', 'mode_id' => '1'],
            ['code' => 'HPA', 'name' => 'Half Pallet', 'mode_id' => '1'],
            ['code' => 'PAL', 'name' => 'Pallet', 'mode_id' => '1'],
            ['code' => 'PAK', 'name' => 'Carrier Supplied PAK', 'mode_id' => '1'],
            ['code' => 'BOX', 'name' => 'Carrier Supplied Box', 'mode_id' => '1'],
            ['code' => 'BOX15', 'name' => 'Carrier Supplied 15Kg Box', 'mode_id' => '1'],
            ['code' => 'BOX25', 'name' => 'Carrier Supplied 25Kg Box', 'mode_id' => '1'],
            ['code' => 'ENV', 'name' => 'Carrier Supplied Envelope', 'mode_id' => '1'],
        ]);

        // Define Carrier Packaging
        DB::table('carrier_packaging_types')->insert([
            ['packaging_type_id' => '1', 'carrier_id' => '2', 'code' => '01', 'rate_code' => 'Package'],
            ['packaging_type_id' => '2', 'carrier_id' => '2', 'code' => '01', 'rate_code' => 'Package'],
            ['packaging_type_id' => '3', 'carrier_id' => '2', 'code' => '01', 'rate_code' => 'Package'],
            ['packaging_type_id' => '4', 'carrier_id' => '2', 'code' => '02', 'rate_code' => 'Pack'],
            ['packaging_type_id' => '5', 'carrier_id' => '2', 'code' => '03', 'rate_code' => 'Package'],
            ['packaging_type_id' => '6', 'carrier_id' => '2', 'code' => '15', 'rate_code' => 'Package'],
            ['packaging_type_id' => '7', 'carrier_id' => '2', 'code' => '25', 'rate_code' => 'Package'],
            ['packaging_type_id' => '8', 'carrier_id' => '2', 'code' => '06', 'rate_code' => 'Letter'],
            ['packaging_type_id' => '1', 'carrier_id' => '5', 'code' => 'YP', 'rate_code' => 'Package'],
            ['packaging_type_id' => '8', 'carrier_id' => '5', 'code' => 'EE', 'rate_code' => 'Letter'],
        ]);

        // Define Packaging Types
        DB::table('company_packaging_types')->insert([
            [
                'code' => 'CTN',
                'description' => 'Carton',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '1',
                'mode_id' => '1'
            ],
            [
                'code' => 'HPA',
                'description' => 'Half Pallet',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '2',
                'mode_id' => '1'
            ],
            [
                'code' => 'PAL',
                'description' => 'Pallet',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '3',
                'mode_id' => '1'
            ],
            [
                'code' => 'PAK',
                'description' => 'Carrier Supplied PAK',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '4',
                'mode_id' => '1'
            ],
            [
                'code' => 'BOX',
                'description' => 'Carrier Supplied Box',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '5',
                'mode_id' => '1'
            ],
            [
                'code' => 'BOX15',
                'description' => 'Carrier Supplied 15 Kg Box',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '6',
                'mode_id' => '1'
            ],
            [
                'code' => 'BOX25',
                'description' => 'Carrier Supplied 25 Kg Box',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '7',
                'mode_id' => '1'
            ],
            [
                'code' => 'ENV',
                'description' => 'Carrier Supplied Envelope',
                'length' => '',
                'width' => '',
                'height' => '',
                'weight' => '',
                'display_order' => '',
                'company_id' => '0',
                'packaging_type_id' => '8',
                'mode_id' => '1'
            ],
        ]);
    }

}
