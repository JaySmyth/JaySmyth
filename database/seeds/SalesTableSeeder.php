<?php

use Illuminate\Database\Seeder;

class SalesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales')->insert([
            ['name' => 'House'],
            ['name' => 'Gerry Heaney'],
            ['name' => 'Mark Johnston'],
            ['name' => 'Graeme Hanna']
        ]);
    }

}
