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
        DB::table('departments')->insert(array(
            array('code' => 'IFCEX', 'name' => 'Courier (Export)'),
            array('code' => 'IFCIM', 'name' => 'Courier (Import)'),
            array('code' => 'IFFRD', 'name' => 'Road (Domestic)'),
            array('code' => 'IFFRI', 'name' => 'Road Import'),
            array('code' => 'IFFRX', 'name' => 'Road Export'),
            array('code' => 'IFFAI', 'name' => 'Air Import'),
            array('code' => 'IFFAX', 'name' => 'Air Export'),
            array('code' => 'IFFSI', 'name' => 'Sea Import'),
            array('code' => 'IFFSX', 'name' => 'Sea Export'),
            array('code' => 'IFCUK', 'name' => 'Courier (UK)'),
        ));
    }
}
