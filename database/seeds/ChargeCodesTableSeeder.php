<?php

use Illuminate\Database\Seeder;

class ChargeCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["id" => 1,"code" => "FRT","description" => "Freight Charge","scs_code" => "FRT"];
        $data[] = ["id" => 2,"code" => "FUEL","description" => "Fuel Surcharge","scs_code" => "FSC"];
        $data[] = ["id" => 3,"code" => "MIS","description" => "Misc charges","scs_code" => "MIS"];
        $data[] = ["id" => 4,"code" => "OSP","description" => "Oversize Piece Surcharge","scs_code" => "ADH"];
        $data[] = ["id" => 5,"code" => "OWP","description" => "Overweight Piece Surcharge","scs_code" => "ADH"];
        $data[] = ["id" => 6,"code" => "LPS","description" => "Large Package surcharge","scs_code" => "LPS"];
        $data[] = ["id" => 7,"code" => "DISC","description" => "Discount","scs_code" => "MIS"];
        $data[] = ["id" => 8,"code" => "ADH","description" => "Additional Handling","scs_code" => "ADH"];
        $data[] = ["id" => 9,"code" => "OOA","description" => "Out of Area","scs_code" => "OOA"];
        $data[] = ["id" => 10,"code" => "RES","description" => "Residential Surcharge","scs_code" => "RES"];
        $data[] = ["id" => 12,"code" => "COR","description" => "Address Correction","scs_code" => "MIS"];
        $data[] = ["id" => 13,"code" => "BRO","description" => "Handover to Broker","scs_code" => "MIS"];
        $data[] = ["id" => 14,"code" => "DTP","description" => "Duties & Taxes Paid","scs_code" => "MIS"];
        $data[] = ["id" => 15,"code" => "ICE","description" => "Dry Ice","scs_code" => "MIS"];
        $data[] = ["id" => 16,"code" => "LIA","description" => "Extended Liability","scs_code" => "MIS"];
        $data[] = ["id" => 17,"code" => "RAD","description" => "Remote Area Delivery","scs_code" => "OOA"];
        $data[] = ["id" => 18,"code" => "RAP","description" => "Remote Area Pickup","scs_code" => "OOA"];
        $data[] = ["id" => 19,"code" => "EAD","description" => "Extended Area Delivery","scs_code" => "OOA"];
        $data[] = ["id" => 20,"code" => "ADG","description" => "Accessible DG","scs_code" => "HAZ"];
        $data[] = ["id" => 21,"code" => "IDG","description" => "Inaccessible DG","scs_code" => "HAZ"];
        $data[] = ["id" => 22,"code" => "EQT","description" => "Excepted Qty DG","scs_code" => "HAZ"];
        $data[] = ["id" => 23,"code" => "EAS","description" => "Extended Area Surcharge","scs_code" => "OOA"];
        $data[] = ["id" => 24,"code" => "MAX","description" => "Over Max Limits","scs_code" => "ADH"];
        $data[] = ["id" => 25,"code" => "RAS","description" => "Remote Area Service","scs_code" => "OOA"];
        $data[] = ["id" => 26,"code" => "INS","description" => "Insurance","scs_code" => "INS"];

        // Modify a few records
        DB::table('charge_codes')->insert($data);
    }
}
