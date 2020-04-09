<?php

use Illuminate\Database\Seeder;

class ShipReasonsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ship_reasons')->delete();
        
        \DB::table('ship_reasons')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'sold',
                'description' => 'Goods Sold',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'documents',
                'description' => 'Documents',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'gift',
                'description' => 'Unsolicited gift',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'repair',
                'description' => 'Repair/ Warranty',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'sample',
                'description' => 'Commercial Sample',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'personal',
                'description' => 'Personal Effects',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'return',
                'description' => 'Return Shipment',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'temp',
                'description' => 'Temporary Export',
            ),
        ));
        
        
    }
}