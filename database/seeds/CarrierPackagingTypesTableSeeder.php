<?php

use Illuminate\Database\Seeder;

class CarrierPackagingTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('carrier_packaging_types')->delete();
        
        \DB::table('carrier_packaging_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 3,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '02',
                'rate_code' => 'Pack',
                'carrier_id' => 2,
                'packaging_type_id' => 4,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '03',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 5,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '15',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 6,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '25',
                'rate_code' => 'Package',
                'carrier_id' => 2,
                'packaging_type_id' => 7,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '06',
                'rate_code' => 'Letter',
                'carrier_id' => 2,
                'packaging_type_id' => 8,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'EE',
                'rate_code' => 'Letter',
                'carrier_id' => 5,
                'packaging_type_id' => 8,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 3,
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'YP',
                'rate_code' => 'Pack',
                'carrier_id' => 5,
                'packaging_type_id' => 4,
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 5,
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 6,
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'YP',
                'rate_code' => 'Package',
                'carrier_id' => 5,
                'packaging_type_id' => 7,
            ),
            16 => 
            array (
                'id' => 20,
                'code' => 'PAL',
                'rate_code' => 'Pallet',
                'carrier_id' => 10,
                'packaging_type_id' => 3,
            ),
            17 => 
            array (
                'id' => 21,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 3,
                'packaging_type_id' => 1,
            ),
            18 => 
            array (
                'id' => 22,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 3,
                'packaging_type_id' => 2,
            ),
            19 => 
            array (
                'id' => 23,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 3,
                'packaging_type_id' => 3,
            ),
            20 => 
            array (
                'id' => 24,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 4,
                'packaging_type_id' => 1,
            ),
            21 => 
            array (
                'id' => 25,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 4,
                'packaging_type_id' => 2,
            ),
            22 => 
            array (
                'id' => 26,
                'code' => '01',
                'rate_code' => 'Pallet',
                'carrier_id' => 4,
                'packaging_type_id' => 3,
            ),
            23 => 
            array (
                'id' => 30,
                'code' => '01',
                'rate_code' => 'Pallet',
                'carrier_id' => 6,
                'packaging_type_id' => 3,
            ),
            24 => 
            array (
                'id' => 31,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 6,
                'packaging_type_id' => 2,
            ),
            25 => 
            array (
                'id' => 32,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 6,
                'packaging_type_id' => 1,
            ),
            26 => 
            array (
                'id' => 33,
                'code' => '01',
                'rate_code' => 'Pallet',
                'carrier_id' => 7,
                'packaging_type_id' => 3,
            ),
            27 => 
            array (
                'id' => 34,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 7,
                'packaging_type_id' => 2,
            ),
            28 => 
            array (
                'id' => 35,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 7,
                'packaging_type_id' => 1,
            ),
            29 => 
            array (
                'id' => 36,
                'code' => '01',
                'rate_code' => 'Pallet',
                'carrier_id' => 8,
                'packaging_type_id' => 3,
            ),
            30 => 
            array (
                'id' => 37,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 8,
                'packaging_type_id' => 2,
            ),
            31 => 
            array (
                'id' => 38,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 8,
                'packaging_type_id' => 1,
            ),
            32 => 
            array (
                'id' => 39,
                'code' => '01',
                'rate_code' => 'Pallet',
                'carrier_id' => 9,
                'packaging_type_id' => 3,
            ),
            33 => 
            array (
                'id' => 40,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 9,
                'packaging_type_id' => 2,
            ),
            34 => 
            array (
                'id' => 41,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 9,
                'packaging_type_id' => 1,
            ),
            35 => 
            array (
                'id' => 42,
                'code' => 'HPA',
                'rate_code' => 'Pallet',
                'carrier_id' => 10,
                'packaging_type_id' => 2,
            ),
            36 => 
            array (
                'id' => 43,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 1,
                'packaging_type_id' => 1,
            ),
            37 => 
            array (
                'id' => 44,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 1,
                'packaging_type_id' => 2,
            ),
            38 => 
            array (
                'id' => 45,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 1,
                'packaging_type_id' => 3,
            ),
            39 => 
            array (
                'id' => 46,
                'code' => '01',
                'rate_code' => 'Letter',
                'carrier_id' => 3,
                'packaging_type_id' => 8,
            ),
            40 => 
            array (
                'id' => 47,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 11,
                'packaging_type_id' => 1,
            ),
            41 => 
            array (
                'id' => 48,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 12,
                'packaging_type_id' => 1,
            ),
            42 => 
            array (
                'id' => 49,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 3,
                'packaging_type_id' => 5,
            ),
            43 => 
            array (
                'id' => 50,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 4,
                'packaging_type_id' => 5,
            ),
            44 => 
            array (
                'id' => 52,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 6,
                'packaging_type_id' => 5,
            ),
            45 => 
            array (
                'id' => 53,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 7,
                'packaging_type_id' => 5,
            ),
            46 => 
            array (
                'id' => 54,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 8,
                'packaging_type_id' => 5,
            ),
            47 => 
            array (
                'id' => 55,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 9,
                'packaging_type_id' => 5,
            ),
            48 => 
            array (
                'id' => 56,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 11,
                'packaging_type_id' => 5,
            ),
            49 => 
            array (
                'id' => 57,
                'code' => '01',
                'rate_code' => 'Package',
                'carrier_id' => 12,
                'packaging_type_id' => 5,
            ),
        ));
        
        
    }
}