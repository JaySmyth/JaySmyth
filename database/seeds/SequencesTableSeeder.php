<?php

use Illuminate\Database\Seeder;

class SequencesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sequences')->delete();
        
        \DB::table('sequences')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'CONSIGNMENT',
                'start_number' => 1000000000,
                'finish_number' => 1999999999,
                'next_available' => 1001078691,
                'comment' => 'Consignment Numbers',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'MANIFEST',
                'start_number' => 1,
                'finish_number' => 0,
                'next_available' => 5365,
                'comment' => 'Manifest Numbers',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'SEA',
                'start_number' => 10000000,
                'finish_number' => 1999999999,
                'next_available' => 10000270,
                'comment' => 'Sea Freight Shipments',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'DHLMAIL',
                'start_number' => 0,
                'finish_number' => 0,
                'next_available' => 0,
                'comment' => 'DHL Global Mail',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'DRIVER',
                'start_number' => 1000,
                'finish_number' => 1999999999,
                'next_available' => 15798,
                'comment' => 'Driver Manifests',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'JOB',
                'start_number' => 10000000,
                'finish_number' => 1999999999,
                'next_available' => 10637867,
                'comment' => 'Transport Jobs',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'EXPCONSIGNMENT',
                'start_number' => 338501,
                'finish_number' => 343500,
                'next_available' => 340148,
                'comment' => 'Express Freight Consignment',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'EXPNICONSIGNMENT',
                'start_number' => 1,
                'finish_number' => 999,
                'next_available' => 2,
                'comment' => 'Express Freight NI Consignment',
            ),
        ));
        
        
    }
}