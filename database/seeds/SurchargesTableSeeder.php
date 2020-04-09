<?php

use Illuminate\Database\Seeder;

class SurchargesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('surcharges')->delete();
        
        \DB::table('surcharges')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'UPS Supplier Surcharges',
                'type' => 'c',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'DHL Domestic Supplier Surcharges',
                'type' => 'c',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'DHL International Supplier Surcharges',
                'type' => 'c',
            ),
            3 => 
            array (
                'id' => 4,
            'name' => 'Fedex Supplier Surcharges (Intl)',
                'type' => 'c',
            ),
            4 => 
            array (
                'id' => 5,
            'name' => 'TNT Supplier Surcharges (Domestic)',
                'type' => 'c',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Primary Freight Surcharges',
                'type' => 'c',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'USG Surcharges',
                'type' => 'c',
            ),
            7 => 
            array (
                'id' => 8,
            'name' => 'TNT Supplier Surcharges (Intl)',
                'type' => 'c',
            ),
            8 => 
            array (
                'id' => 9,
            'name' => 'Fedex Supplier Surcharges (Domestic)',
                'type' => 'c',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'IFS Domestic Surcharges',
                'type' => 's',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'IFS Intl Surcharges',
                'type' => 's',
            ),
            11 => 
            array (
                'id' => 12,
            'name' => 'IFS Surcharges (TNT - Domestic)',
                'type' => 's',
            ),
            12 => 
            array (
                'id' => 13,
            'name' => 'IFS Surcharges (USG)',
                'type' => 's',
            ),
            13 => 
            array (
                'id' => 14,
            'name' => 'IFS Surcharges (UK48)',
                'type' => 's',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Express Freight Domestic',
                'type' => 'c',
            ),
            15 => 
            array (
                'id' => 16,
            'name' => 'IFS Domestic Surcharges (UPS)',
                'type' => 's',
            ),
            16 => 
            array (
                'id' => 17,
            'name' => 'IFS Intl Surcharges (UPS)',
                'type' => 's',
            ),
        ));
        
        
    }
}