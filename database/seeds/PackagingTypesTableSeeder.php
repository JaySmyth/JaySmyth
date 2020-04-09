<?php

use Illuminate\Database\Seeder;

class PackagingTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('packaging_types')->delete();
        
        \DB::table('packaging_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'CTN',
                'name' => 'Carton',
                'mode_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'HPA',
                'name' => 'Half Pallet',
                'mode_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'PAL',
                'name' => 'Pallet',
                'mode_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'PAK',
                'name' => 'Carrier Supplied PAK',
                'mode_id' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'BOX',
                'name' => 'Fedex Supplied Box',
                'mode_id' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'BOX15',
                'name' => 'Fedex Supplied 15Kg Box',
                'mode_id' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'BOX25',
                'name' => 'Fedex Supplied 25Kg Box',
                'mode_id' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'ENV',
            'name' => 'Carrier Supplied Envelope (Fedex/ DHL)',
                'mode_id' => 1,
            ),
        ));
        
        
    }
}