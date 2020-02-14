<?php

use Illuminate\Database\Seeder;

class PrintFormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('print_formats')->insert([
            ['code' => 'A4', 'name' => 'A4', 'width' => 210, 'height' => 297],
            ['code' => '6X4', 'name' => '6x4 Label', 'width' => 102, 'height' => 153],
            ['code' => '6-6X4', 'name' => '6.6x4 Label', 'width' => 102, 'height' => 167.64],
            ['code' => 'FEDEX', 'name' => 'FedEx Label', 'width' => 102, 'height' => 167.64],
            ['code' => 'LETTER', 'name' => 'Letter (US)', 'width' => 216, 'height' => 279],
        ]);
    }
}
