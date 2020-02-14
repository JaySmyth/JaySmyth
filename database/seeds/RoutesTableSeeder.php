<?php

use Illuminate\Database\Seeder;

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('routes')->insert([
            ['code' => 'ANT', 'name' => 'IFS Antrim Depot', 'depot_id' => 1],
            ['code' => 'BFS', 'name' => 'Belfast International Airport', 'depot_id' => 1],
            ['code' => 'MIA', 'name' => 'ECX Miami', 'depot_id' => 3],
        ]);
    }
}
