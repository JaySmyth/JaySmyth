<?php

use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert some dummy records
        DB::table('terms')->insert([
            ['description' => 'Ex Works', 'code' => 'EXW', 'mode_id' => 1],
            ['description' => 'Free Carrier', 'code' => 'FCA', 'mode_id' => 1],
            ['description' => 'Carriage Paid To', 'code' => 'CPT', 'mode_id' => 1],
            ['description' => 'Carriage & Ins Paid to', 'code' => 'CIP', 'mode_id' => 1],
            ['description' => 'Delivered At Terminal', 'code' => 'DAT', 'mode_id' => 1],
            ['description' => 'Delivered At Place', 'code' => 'DAP', 'mode_id' => 1],
            ['description' => 'Delivered Duty Paid', 'code' => 'DDP', 'mode_id' => 1],
            ['description' => 'Free Alongside Ship', 'code' => 'FAS', 'mode_id' => 4],
            ['description' => 'Free On Board', 'code' => 'FOB', 'mode_id' => 4],
            ['description' => 'Cost & Freight', 'code' => 'CFR', 'mode_id' => 4],
            ['description' => 'Cost, Insurance & Freight', 'code' => 'CIF', 'mode_id' => 4],
        ]);
    }
}
