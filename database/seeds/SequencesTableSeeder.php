<?php

use Illuminate\Database\Seeder;

class SequencesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('sequences')->insert(array(
            array('code' => 'CONSIGNMENT', 'start_number' => '1000000000', 'finish_number' => '1999999999', 'next_available' => '1000000000', 'comment' => 'Consignment Numbers'),
            array('code' => 'MANIFEST', 'start_number' => '1', 'finish_number' => '', 'next_available' => '1', 'comment' => 'Manifest Numbers')
        ));
    }

}
