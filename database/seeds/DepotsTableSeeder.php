<?php

use Illuminate\Database\Seeder;

class DepotsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depots')->insert([
            ['code' => 'ANT', 'name' => 'IFS Antrim', 'city' => 'Antrim', 'state' => 'County Antrim', 'postcode' => 'BT41 4QE', 'country_code' => 'GB', 'localisation_id' => 1],
            ['code' => 'LON', 'name' => 'IFS London', 'city' => 'London', 'state' => 'London', 'postcode' => '', 'country_code' => 'GB', 'localisation_id' => 1],
            ['code' => 'MIA', 'name' => 'ECX Miami', 'city' => 'Miami', 'state' => 'FL', 'postcode' => '', 'country_code' => 'US', 'localisation_id' => 2],
            ['code' => 'OLD', 'name' => 'Redundant', 'city' => 'n/a', 'state' => 'n/a', 'postcode' => '', 'country_code' => 'GB', 'localisation_id' => 1],
        ]);
    }

}
