<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('departments')->insert([
            ['code' => 'IFCEX', 'name' => 'Courier (Export)'],
            ['code' => 'IFCIM', 'name' => 'Courier (Import)'],
            ['code' => 'IFFRD', 'name' => 'Road (Domestic)'],
            ['code' => 'IFFRI', 'name' => 'Road Import'],
            ['code' => 'IFFRX', 'name' => 'Road Export'],
            ['code' => 'IFFAI', 'name' => 'Air Import'],
            ['code' => 'IFFAX', 'name' => 'Air Export'],
            ['code' => 'IFFSI', 'name' => 'Sea Import'],
            ['code' => 'IFFSX', 'name' => 'Sea Export'],
            ['code' => 'IFCUK', 'name' => 'Courier (UK)'],
        ]);
    }
}
