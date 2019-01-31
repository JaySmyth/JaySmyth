<?php

use Illuminate\Database\Seeder;

class CPCTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_string = '[{ 
"id":1,
"code":"4000000",
"description":"Home Use",
"vat_status":"Duty & VAT Paid",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":2,
"code":"4000002",
"description":"Air Worthyness",
"vat_status":"Potential Duty",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":3,
"code":"4000023",
"description":"End Use - Ships",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":4,
"code":"4000024",
"description":"End Use",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":5,
"code":"4000C07",
"description":"Low Value",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":6,
"code":"4000C33",
"description":"Goods for Test",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":7,
"code":"4071000",
"description":"Goods Removed from W/H ",
"vat_status":"Duty & VAT Paid",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":8,
"code":"4100000",
"description":"IPR Drawback",
"vat_status":"Potential Duty",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":9,
"code":"4171000",
"description":"Goods Removed from Warehouse Drawback",
"vat_status":"Duty & VAT Paid",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":10,
"code":"5100000",
"description":"IPR Suspension",
"vat_status":"Potential Duty",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":11,
"code":"5100001",
"description":"IPR Suspension - Specific Auth",
"vat_status":"Potential Duty",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":12,
"code":"5100A04",
"description":"IPR UNDER FULL AUTHORISATION",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":13,
"code":"5171000",
"description":"Goods Removed From W/H IPR ",
"vat_status":"Potential Duty",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":14,
"code":"5300D18",
"description":"Temporary Import",
"vat_status":"Duty & VAT Paid",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":15,
"code":"6123F01",
"description":"British Return Goods",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":16,
"code":"7100000",
"description":"Warehousing",
"vat_status":"Duty & VAT Exempt",
"duty_type":"",
"insert_allowed":"0"
},
{ 
"id":17,
"code":"Blank",
"description":"Blank",
"vat_status":"Duty & VAT Paid",
"duty_type":"",
"insert_allowed":"0"
}]';

        $cpc = json_decode($json_string, true);

        // Modify a few records
        DB::table('customs_procedure_codes')->insert($cpc);
    }

}
