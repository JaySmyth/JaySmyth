<?php

use Illuminate\Database\Seeder;

class VatCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert some dummy records
        $json_string = '[{"id":1,"code":"z","percent":0,"from_date":"2016-01-01","to_date":"2050-12-31","created_at":null,"updated_at":null},{"id":2,"code":"e","percent":0,"from_date":"2016-01-01","to_date":"2050-12-31","created_at":null,"updated_at":null},{"id":3,"code":"1","percent":20,"from_date":"2016-01-01","to_date":"2050-12-31","created_at":null,"updated_at":null}]';
        $data = json_decode($json_string, true);

        // Disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vat_codes')->delete();

        // Modify a few records
        DB::table('vat_codes')->insert($data);

        // Enable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
