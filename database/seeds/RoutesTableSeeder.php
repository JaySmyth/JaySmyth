<?php

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('routes')->delete();
        
        \DB::table('routes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ANT',
                'name' => 'IFS Antrim Depot',
                'depot_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'BFS',
                'name' => 'Belfast International Airport',
                'depot_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'MIA',
                'name' => 'ECX Miami',
                'depot_id' => 3,
            ),
        ));
        
        
    }
}