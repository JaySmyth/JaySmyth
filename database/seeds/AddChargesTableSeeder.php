<?php

use Illuminate\Database\Seeder;

class AddChargesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = ["id" => 1,"name" => "Supplier Surcharges (Carrier UPS)","type" => "c"];
        $data[] = ["id" => 2,"name" => "Supplier Surcharges (Carrier DHL Domestic)","type" => "c"];
        $data[] = ["id" => 3,"name" => "Supplier Surcharges (Carrier DHL International)","type" => "c"];
        $data[] = ["id" => 4,"name" => "Supplier Surcharges (Carrier Fedex)","type" => "c"];
        $data[] = ["id" => 5,"name" => "Supplier Surcharges (Carrier TNT)","type" => "c"];
        $data[] = ["id" => 10,"name" => "IFS Domestic Surcharges","type" => "s"];
        $data[] = ["id" => 11,"name" => "IFS Intl Surcharges","type" => "s"];
        $data[] = ["id" => 12,"name" => "IFS Domestic Surcharges (Euro)","type" => "s"];
        $data[] = ["id" => 13,"name" => "IFS Intl Surcharges (Euro)","type" => "s"];
        $data[] = ["id" => 101,"name" => "IFS Domestic Surcharges (No Residential)","type" => "s"];
        $data[] = ["id" => 102,"name" => "IFS Intl Surcharges (No Residential)","type" => "s"];
        $data[] = ["id" => 103,"name" => "IFS Domestic Surcharges (No Residential - Euro)","type" => "s"];
        $data[] = ["id" => 104,"name" => "IFS Intl Surcharges (No Residential - Euro)","type" => "s"];

        // Modify a few records
        DB::table('add_charges')->insert($data);
    }
}
