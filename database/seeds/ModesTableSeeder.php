<?php

use Illuminate\Database\Seeder;

class ModesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('modes')->insert(array(
            array('name' => 'courier', 'label' => 'Courier'),
            array('name' => 'air', 'label' => 'Air Freight'),
            array('name' => 'road', 'label' => 'Road Freight'),
            array('name' => 'sea', 'label' => 'Sea Freight')
        ));
    }

}
