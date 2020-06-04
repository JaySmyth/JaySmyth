<?php

use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["id" => 1,"name" => "Geoff Mc Dowell","company_name" => "Texam Ltd","address1" => "Blaris Ind Est","address2" => "28 Altona Road","address3" => "","city" => "Lisburn","state" => "Antrim","postcode" => "BT27 5QB","country_code" => "GB","telephone" => "028 9267 4137","email" => "g.mcdowell@texam.ltd.uk","type" => "c","definition" => "sender","account_number" => "","company_id" => 1,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 2,"name" => "Caroline Gordon","company_name" => "Kingspan Environmental Ltd","address1" => "MR BRENDAN AULD","address2" => "40 LISNATAYLOR ROAD","address3" => "NUTTS CORNER","city" => "CRUMLIN","state" => "ANTRIM","postcode" => "BT29 4YD","country_code" => "GB","telephone" => "07848453655","email" => "cgordon@antrim.ifsgroup.com","type" => "r","definition" => "sender","account_number" => "","company_id" => 1,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 3,"name" => "Christina Geiger","company_name" => "Bonhams","address1" => "580 Madison Avenue","address2" => "Manhattan","address3" => "","city" => "Manhattan","state" => "NY","postcode" => "10022","country_code" => "US","telephone" => "12126449001","email" => "","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 2,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 4,"name" => "Simon","company_name" => "Mobile Vms Ltd","address1" => "Greenbank Ind Estate","address2" => "","address3" => "","city" => "Newry","state" => "DOWN","postcode" => "BT34 2PB","country_code" => "GB","telephone" => "02830440001","email" => "","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 2,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 5,"name" => "Beverlery","company_name" => "Boc Ltd  ","address1" => "St Helens","address2" => "Washway Lane","address3" => "St Helens","city" => "St Helens","state" => "MERSEYSIDE","postcode" => "WA10 6PA","country_code" => "GB","telephone" => "01483244290","email" => "","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 3,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 6,"name" => "Miss Grazia Toboreli","company_name" => "Olmetto Spa","address1" => "Via Roma No. 2","address2" => "22026 Maslianico (como)","address3" => "","city" => "Maslianico","state" => "","postcode" => "22026","country_code" => "IT","telephone" => "1","email" => "","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 4,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 7,"name" => "Kelly Mcdonnell","company_name" => "Fairgreen Motor Factors","address1" => "Fairgreen Motor Factors","address2" => "Drogheda","address3" => "","city" => "Drogheda","state" => "Louth","postcode" => "","country_code" => "IE","telephone" => "07928112637","email" => "info@risingstardesigns.co.uk","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 1,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $data[] = ["id" => 8,"name" => "Fao Denise O Neill","company_name" => "Londonderry Armshotel","address1" => "20 Harbour Road","address2" => "Carnlough","address3" => "","city" => "Derry","state" => "LONDONDERRY","postcode" => "BT44 0EU","country_code" => "GB","telephone" => "02828885255","email" => "","type" => "c","definition" => "recipient","account_number" => null,"company_id" => 1,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];


        // Modify a few records
        DB::table('addresses')->insert($data);
    }
}
