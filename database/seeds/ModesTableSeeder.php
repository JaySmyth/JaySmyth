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
        DB::table('modes')->insert([
            ['name' => 'courier', 'label' => 'Courier'],
            ['name' => 'air', 'label' => 'Air Freight'],
            ['name' => 'road', 'label' => 'Road Freight'],
            ['name' => 'sea', 'label' => 'Sea Freight'],
        ]);
    }
}
