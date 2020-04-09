<?php

use Illuminate\Database\Seeder;

class VehiclesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('vehicles')->delete();
        
        \DB::table('vehicles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'registration' => 'GXZ 5210',
                'type' => '4.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'registration' => 'GXZ 5207',
                'type' => '4.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'registration' => 'GXZ 5209',
                'type' => '4.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'registration' => 'GXZ 5208',
                'type' => '4.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'registration' => 'KU64 SXS',
                'type' => '7.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'registration' => 'KU64 SXO',
                'type' => '7.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'registration' => 'SHZ 9858',
                'type' => '4.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'registration' => 'IRZ 6583',
                'type' => '17.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'registration' => 'KU64 SXT',
                'type' => '7.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'registration' => 'IRZ 6582',
                'type' => '12T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'registration' => 'KU64 SXR',
                'type' => '7.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'registration' => 'IRZ 6584',
                'type' => '17.5T',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'registration' => 'SXI 108',
                'type' => '40FT',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'registration' => 'SXI 107',
                'type' => '40FT',
                'enabled' => 1,
                'depot_id' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'registration' => 'SUB 0001',
                'type' => 'N/A',
                'enabled' => 1,
                'depot_id' => 1,
            ),
        ));
        
        
    }
}