<?php

use Illuminate\Database\Seeder;

class CarrierChargeCodesTableSeeder extends Seeder
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
"code":"001",
"description":"Declared Value Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":2,
"code":"002",
"description":"Saturday Delivery Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":3,
"code":"003",
"description":"Saturday Pickup Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":4,
"code":"004",
"description":"No Account Number Used for Billing",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":5,
"code":"005",
"description":"Alaska or Hawaii (Metro delivery)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":6,
"code":"006",
"description":"Alaska/Hawaii (Non-Metro delivery)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":7,
"code":"007",
"description":"Recipient Address Correction Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":8,
"code":"008",
"description":"Inaccessible Dangerous Goods",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":9,
"code":"009",
"description":"Other Charges",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":10,
"code":"010",
"description":"Fuel Surcharge",
"scs_code":"FSC",
"carrier_id":2
},
{ 
"id":11,
"code":"011",
"description":"Pickup Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":12,
"code":"012",
"description":"Accessible Dangerous Goods",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":13,
"code":"013",
"description":"Constant Surveillance Service Requested",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":14,
"code":"014",
"description":"Service Failure Credit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":15,
"code":"015",
"description":"POD Service Credit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":16,
"code":"016",
"description":"Service Credit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":17,
"code":"017",
"description":"Package Status Credit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":18,
"code":"018",
"description":"Late Delivery",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":19,
"code":"019",
"description":"Incorrect Billing Account Number Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":20,
"code":"020",
"description":"Invalid Bill Shipper Account Number Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":21,
"code":"021",
"description":"C.O.D. Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":22,
"code":"022",
"description":"Residential Delivery Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":23,
"code":"023",
"description":"H3 Pickup Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":24,
"code":"024",
"description":"H3 Delivery Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":25,
"code":"025",
"description":"OFS/F2 Heavy Weight Inside Pickup Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":26,
"code":"026",
"description":"OFS/F2 Heavy Weight Inside Delivery Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":27,
"code":"027",
"description":"OFS/F2 Heavy Weight Residential Pickup Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":28,
"code":"028",
"description":"OFS/F2 Heavy Weight Residential Delivery Char",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":29,
"code":"029",
"description":"OFS/F2 Heavy Weight Delivery Reattempt Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":30,
"code":"030",
"description":"OFS/F2 Heavy Weight Extra Labor Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":31,
"code":"031",
"description":"OFS/F2 Heavy Weight Single Shipment Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":32,
"code":"032",
"description":"OFS/F2 Heavy Weight Reconsignment Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":33,
"code":"033",
"description":"OFS/F2 Heavy Weight Mark and Tag Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":34,
"code":"034",
"description":"Dry Ice",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":35,
"code":"035",
"description":"FedEx Corporation Audit Indicator",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":36,
"code":"036",
"description":"Hold at Station",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":37,
"code":"037",
"description":"Bundle Number",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":38,
"code":"038",
"description":"Week Day Delivery",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":39,
"code":"039",
"description":"Hold at Station Heavy Weight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":40,
"code":"040",
"description":"Drop Off Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":41,
"code":"041",
"description":"Overweight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":42,
"code":"042",
"description":"Out of Pickup Area",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":43,
"code":"043",
"description":"Out of Delivery Area",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":44,
"code":"044",
"description":"Financial Document Option",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":45,
"code":"045",
"description":"Broker Selection Option",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":46,
"code":"046",
"description":"Cut Flowers",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":47,
"code":"047",
"description":"Argentina Broker Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":48,
"code":"048",
"description":"Argentina Phito Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":49,
"code":"049",
"description":"Argentina Inase Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":50,
"code":"050",
"description":"Freight Charge",
"scs_code":"FRT",
"carrier_id":2
},
{ 
"id":51,
"code":"051",
"description":"Cash Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":52,
"code":"052",
"description":"Original Customs Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":53,
"code":"053",
"description":"Rebill Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":54,
"code":"054",
"description":"CST (Canadian Sales Tax), Additional Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":55,
"code":"055",
"description":"Rebill CST (Canadian Sales Tax), Additional D",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":56,
"code":"056",
"description":"FedEx Additional Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":57,
"code":"057",
"description":"Rebill FedEx Additional Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":58,
"code":"058",
"description":"Cash VAT (Value Added Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":59,
"code":"059",
"description":"Original VAT (Value Added Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":60,
"code":"060",
"description":"Rebill VAT (Value Added Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":61,
"code":"061",
"description":"FedEx Additional VAT (Value Added Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":62,
"code":"062",
"description":"Rebill FedEx Additional VAT (Value Added Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":63,
"code":"063",
"description":"Puerto Rico Country Tax",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":64,
"code":"064",
"description":"Intangible Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":65,
"code":"065",
"description":"Section Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":66,
"code":"066",
"description":"Informal Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":67,
"code":"067",
"description":"Formal Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":68,
"code":"068",
"description":"HAWB Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":69,
"code":"069",
"description":"1/60th Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":70,
"code":"070",
"description":"Bond Fee Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":71,
"code":"071",
"description":"TSUSA Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":72,
"code":"072",
"description":"Missing Document Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":73,
"code":"073",
"description":"Sum Additional Invoice Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":74,
"code":"074",
"description":"Advancement Fee Duty",
"scs_code":"ADM",
"carrier_id":2
},
{ 
"id":75,
"code":"075",
"description":"Government Document Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":76,
"code":"076",
"description":"Post Entry Service Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":77,
"code":"077",
"description":"COMM Reimbursement Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":78,
"code":"078",
"description":"Duty Excise Charge",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":79,
"code":"079",
"description":"Additional Tax Administration Duty - Denmark",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":80,
"code":"080",
"description":"Additional Tax Administration Duty - Belgium",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":81,
"code":"081",
"description":"Additional Tax Administration Duty - Luxembou",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":82,
"code":"082",
"description":"Additional Tax Administration",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":83,
"code":"083",
"description":"Additional Tax Administration Duty - Switzerl",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":84,
"code":"084",
"description":"GST Singapore Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":85,
"code":"085",
"description":"Marca Da Bolla",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":86,
"code":"086",
"description":"GST Tax Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":87,
"code":"087",
"description":"Special Assessment Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":88,
"code":"088",
"description":"Customs Processing Fee Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":89,
"code":"089",
"description":"1/1000 Charge Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":90,
"code":"090",
"description":"Additional Tax Administration Duty - Korea",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":91,
"code":"091",
"description":"TVA Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":92,
"code":"092",
"description":"Austrian Payor Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":93,
"code":"093",
"description":"Antidumping Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":94,
"code":"094",
"description":"Additional Tax Administration Duty - France",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":95,
"code":"095",
"description":"Additional Tax Administration  Duty - Italy",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":96,
"code":"096",
"description":"Taiwan VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":97,
"code":"097",
"description":"Intangible Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":98,
"code":"098",
"description":"Section Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":99,
"code":"099",
"description":"Informal Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":100,
"code":"100",
"description":"Formal Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":101,
"code":"101",
"description":"HAWB Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":102,
"code":"102",
"description":"1/60th Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":103,
"code":"103",
"description":"Storage or Bond Fee VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":104,
"code":"104",
"description":"TSUSA Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":105,
"code":"105",
"description":"Missing Document Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":106,
"code":"106",
"description":"Sum Additional Invoice VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":107,
"code":"107",
"description":"Advancement Fee VAT",
"scs_code":"ADM",
"carrier_id":2
},
{ 
"id":108,
"code":"108",
"description":"Government Document Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":109,
"code":"109",
"description":"Post Entry Service VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":110,
"code":"110",
"description":"COMM Reimbursement Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":111,
"code":"111",
"description":"VAT Excise Charge",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":112,
"code":"112",
"description":"VAT Excise Charge",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":113,
"code":"113",
"description":"Additional Tax Administration VAT- Denmark",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":114,
"code":"114",
"description":"Additional Tax Administration VAT- Belgium",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":115,
"code":"115",
"description":"Additional Tax Administration VAT - Luxembour",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":116,
"code":"116",
"description":"Additional Tax Administration VAT - Austria",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":117,
"code":"117",
"description":"Additional Tax Administration VAT - Switzerla",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":118,
"code":"118",
"description":"GST Singapore VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":119,
"code":"119",
"description":"Marca Da Bolla VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":120,
"code":"120",
"description":"GST Tax VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":121,
"code":"121",
"description":"Special Assessment Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":122,
"code":"122",
"description":"Customs Processing Fee VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":123,
"code":"123",
"description":"1/1000 Charge VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":124,
"code":"124",
"description":"Additional Tax Administration VAT - Korea",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":125,
"code":"125",
"description":"TVA VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":126,
"code":"126",
"description":"Austrian Payor VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":127,
"code":"127",
"description":"Antidumping Duty VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":128,
"code":"128",
"description":"Additional Tax Administration VAT - France",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":129,
"code":"129",
"description":"Additional Tax Administration VAT - Italy",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":130,
"code":"130",
"description":"Additional Tax Administration VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":131,
"code":"131",
"description":"PST AB (Alberta Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":132,
"code":"132",
"description":"PST BC (British Columbia Provincial Sales Tax",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":133,
"code":"133",
"description":"PST MB (Manitoba Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":134,
"code":"134",
"description":"PST NB (New Brunswick Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":135,
"code":"135",
"description":"PST NF  (Newfoundland Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":136,
"code":"136",
"description":"PST NT (Northwest Territories Provincial Sale",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":137,
"code":"137",
"description":"PST NS (Nova Scotia Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":138,
"code":"138",
"description":"PST ON (Ontario Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":139,
"code":"139",
"description":"PST PE (Prince Edward Island Provincial Sales",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":140,
"code":"140",
"description":"PST PQ (Quebec Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":141,
"code":"141",
"description":"PST SK (Saskatchewan Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":142,
"code":"142",
"description":"PST YK (Yukon Provincial Sales Tax)",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":143,
"code":"150",
"description":"Non Document Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":144,
"code":"157",
"description":"Low Item Weight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":145,
"code":"161",
"description":"QST (Quebec Sales Tax) Charge ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":146,
"code":"162",
"description":"Canada GST Freight ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":147,
"code":"163",
"description":"Mexico IVA Freight",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":148,
"code":"164",
"description":"Taiwan VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":149,
"code":"165",
"description":"Grenada VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":150,
"code":"166",
"description":"Venezuela VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":151,
"code":"170",
"description":"Belgium VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":152,
"code":"171",
"description":"Luxembourg VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":153,
"code":"172",
"description":"Germany VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":154,
"code":"173",
"description":"Great Britain VAT ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":155,
"code":"174",
"description":"Italy VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":156,
"code":"175",
"description":"Netherlands VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":157,
"code":"176",
"description":"France VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":158,
"code":"177",
"description":"Austria VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":159,
"code":"178",
"description":"Ireland VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":160,
"code":"179",
"description":"Sweden VAT ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":161,
"code":"180",
"description":"Denmark VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":162,
"code":"181",
"description":"Finland VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":163,
"code":"182",
"description":"Greece VAT ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":164,
"code":"183",
"description":"Spain VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":165,
"code":"184",
"description":"Portugal VAT ",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":166,
"code":"185",
"description":"Discount Amount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":167,
"code":"186",
"description":"Memphis Discount Amount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":168,
"code":"187",
"description":"Dropoff Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":169,
"code":"188",
"description":"Rebate",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":170,
"code":"189",
"description":"Bermuda Terminal Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":171,
"code":"190",
"description":"Bundle Number",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":172,
"code":"191",
"description":"Canadian Duty GST/QS",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":173,
"code":"192",
"description":"Canadian GST/QST Tax",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":174,
"code":"193",
"description":"Freight Other",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":175,
"code":"194",
"description":"Duty Other",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":176,
"code":"195",
"description":"Tax Other",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":177,
"code":"196",
"description":"Duty/Tax Surcharges",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":178,
"code":"197",
"description":"Service Other",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":179,
"code":"198",
"description":"Rebill Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":180,
"code":"202",
"description":"HST (Harmonized Sales Tax) - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":181,
"code":"203",
"description":"HST (Harmonized Sales Tax) - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":182,
"code":"204",
"description":"Canadian HST NB (New Brunswick Harmonized Sal",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":183,
"code":"205",
"description":"Canadian HST NF (Newfoundland Harmonized Sale",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":184,
"code":"206",
"description":"Canadian HST NS (Nova Scotia Harmonized Sales",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":185,
"code":"207",
"description":"UAE GPA",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":186,
"code":"208",
"description":"India Service Tax",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":187,
"code":"209",
"description":"Thailand VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":188,
"code":"210",
"description":"IPFS Dropoff",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":189,
"code":"211",
"description":"IPFS HAL",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":190,
"code":"212",
"description":"IPFS BSO",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":191,
"code":"213",
"description":"Oversize Package",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":192,
"code":"214",
"description":"Out of Pickup Zone",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":193,
"code":"215",
"description":"Bermuda Terminal Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":194,
"code":"216",
"description":"Payment/Credit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":195,
"code":"217",
"description":"Sunday Pickup",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":196,
"code":"219",
"description":"Invalid Third Party Account Number Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":197,
"code":"220",
"description":"Local Tax Charge",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":198,
"code":"221",
"description":"Maximum Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":199,
"code":"222",
"description":"VAT Advance Fee Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":200,
"code":"223",
"description":"Corporate Purchasing Card",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":201,
"code":"224",
"description":"Credit Card Decline Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":202,
"code":"225",
"description":"Liftgate Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":203,
"code":"226",
"description":"Priority Alert",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":204,
"code":"228",
"description":"Memphis Rebate",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":205,
"code":"229",
"description":"Emerge, Consolidation",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":206,
"code":"230",
"description":"Delivery Area Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":207,
"code":"231",
"description":"MBG Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":208,
"code":"232",
"description":"Duty and Tax Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":209,
"code":"233",
"description":"Weight Change Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":210,
"code":"234",
"description":"Handling Change Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":211,
"code":"235",
"description":"Service Change Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":212,
"code":"236",
"description":"Delivery Change Waiver",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":213,
"code":"237",
"description":"Extra Hours Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":214,
"code":"238",
"description":"VAT Advance Fee - Denmark - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":215,
"code":"239",
"description":"VAT Advance Fee - Sweden - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":216,
"code":"240",
"description":"VAT Advance Fee - Thailand - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":217,
"code":"241",
"description":"Customs Fee - Thailand - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":218,
"code":"242",
"description":"Customs Clearance Fee - Thailand - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":219,
"code":"243",
"description":"VAT Customs Clearance - Thailand - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":220,
"code":"244",
"description":"VAT Cash Customer - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":221,
"code":"245",
"description":"VAT Advance Fee - Spain - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":222,
"code":"246",
"description":"VAT Advance Fee - Denmark - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":223,
"code":"247",
"description":"VAT Advance Fee - Sweden - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":224,
"code":"248",
"description":"VAT Advance Fee - Thailand - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":225,
"code":"249",
"description":"Customs Fee - Thailand - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":226,
"code":"250",
"description":"Customs Clearance Fee - Thailand - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":227,
"code":"251",
"description":"VAT Customs Clearance - Thailand",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":228,
"code":"252",
"description":"VAT Cash Customer - Thailand",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":229,
"code":"253",
"description":"Thailand Customs Fee - Freight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":230,
"code":"254",
"description":"Thailand Customs Clearance Fee - Freight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":231,
"code":"255",
"description":"Sweden VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":232,
"code":"256",
"description":"Denmark VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":233,
"code":"257",
"description":"Earned Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":234,
"code":"258",
"description":"Grace Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":235,
"code":"259",
"description":"Australia GST - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":236,
"code":"260",
"description":"Australia GST - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":237,
"code":"261",
"description":"Australia GST - Freight",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":238,
"code":"262",
"description":"Discount (V) Volume Incentive",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":239,
"code":"263",
"description":"Discount (P) Performance Pricing",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":240,
"code":"264",
"description":"Declared Value > $0 (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":241,
"code":"265",
"description":"Credit – Ground",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":242,
"code":"266",
"description":"Credit –  Home Delivery",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":243,
"code":"267",
"description":"Automatic Proof of Delivery",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":244,
"code":"268",
"description":"Additional Handling",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":245,
"code":"269",
"description":"Extra Service Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":246,
"code":"270",
"description":"Overweight > 150 lbs.",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":247,
"code":"271",
"description":"Home Delivery Signature Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":248,
"code":"272",
"description":"Address Correction (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":249,
"code":"273",
"description":"Residential Delivery (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":250,
"code":"274",
"description":"Residential Delivery - Rural (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":251,
"code":"275",
"description":"Hazardous Material (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":252,
"code":"276",
"description":"Home Delivery Residential Delivery Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":253,
"code":"277",
"description":"Home Delivery Residential Rural Delivery Serv",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":254,
"code":"278",
"description":"Cash C.O.D. Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":255,
"code":"279",
"description":"Cash C.O.D. High Intensity Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":256,
"code":"280",
"description":"Cash C.O.D. Extra Difference Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":257,
"code":"281",
"description":"Electronic C.O.D. – 24 hrs.",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":258,
"code":"282",
"description":"Electronic C.O.D. – 48 hrs.",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":259,
"code":"283",
"description":"Proof of Delivery Advantage Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":260,
"code":"284",
"description":"FedEx Ground Home Delivery Out of Service Are",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":261,
"code":"285",
"description":"FedEx Ground Home Delivery of Hazardous Mater",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":262,
"code":"286",
"description":"C.O.D. Fee - Ground",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":263,
"code":"287",
"description":"FedEx Ground Home Delivery of a Pkg > 70 lbs.",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":264,
"code":"288",
"description":"FedEx Ground Home Delivery COD Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":265,
"code":"289",
"description":"Call Tag",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":266,
"code":"290",
"description":"A.O.D. - Acknowledgment of Delivery (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":267,
"code":"291",
"description":"Multiweight Address Correction",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":268,
"code":"292",
"description":"Home Delivery Date Certain Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":269,
"code":"293",
"description":"Home Delivery Appointment Delivery Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":270,
"code":"294",
"description":"FedEx Ground Home Delivery Forced Appointment",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":271,
"code":"295",
"description":"Home Delivery Evening Service",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":272,
"code":"296",
"description":"Residential - Customer Level",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":273,
"code":"297",
"description":"Weekly ECOD 24-hour",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":274,
"code":"298",
"description":"Weekly ECOD 48-hour",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":275,
"code":"299",
"description":"Quickship (Partnership)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":276,
"code":"300",
"description":"POD Advantage Weekly Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":277,
"code":"301",
"description":"Host to Host Project Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":278,
"code":"302",
"description":"Host to Host Installation Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":279,
"code":"303",
"description":"Host to Host Communication Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":280,
"code":"304",
"description":"Host to Host Network Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":281,
"code":"305",
"description":"Weekly Service Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":282,
"code":"306",
"description":"Call Tag – Package Level",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":283,
"code":"308",
"description":"Home Delivery Date Certain Service - Invoice ",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":284,
"code":"309",
"description":"Home Delivery Appointment Delivery Service - ",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":285,
"code":"310",
"description":"Home Delivery Evening Service - Invoice Level",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":286,
"code":"311",
"description":"Fuel Surcharge (Ground)",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":287,
"code":"312",
"description":"Additional Handling Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":288,
"code":"313",
"description":"New Zealand GST - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":289,
"code":"314",
"description":"New Zealand GST - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":290,
"code":"315",
"description":"Automation Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":291,
"code":"316",
"description":"Regular Pickup/Dropoff Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":292,
"code":"317",
"description":"Zone Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":293,
"code":"318",
"description":"Zip to Zip Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":294,
"code":"319",
"description":"Day of Week Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":295,
"code":"320",
"description":"Guatemala IVA Freight",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":296,
"code":"321",
"description":"Dominican Republic ITIBIS",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":297,
"code":"322",
"description":"Duty/Tax Advance Fee",
"scs_code":"ADM",
"carrier_id":2
},
{ 
"id":298,
"code":"330",
"description":"Net Returns Transmission Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":299,
"code":"331",
"description":"Ground Out-of-Cycle (Supplemental) Weight Cor",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":300,
"code":"333",
"description":"Day & Pickup/Dropoff Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":301,
"code":"334",
"description":"Day & Zone Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":302,
"code":"335",
"description":"Day & Zip Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":303,
"code":"336",
"description":"Pickup/Dropoff & Zone Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":304,
"code":"337",
"description":"Pickup/Dropoff & Zip Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":305,
"code":"338",
"description":"Linehaul Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":306,
"code":"340",
"description":"Consolidated Returns Polybag",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":307,
"code":"341",
"description":"Consolidated Returns Package",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":308,
"code":"342",
"description":"Consolidated Returns Package 4x4x6–4x8x12",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":309,
"code":"343",
"description":"Consolidated Returns Package 6x6x10–6x6x16",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":310,
"code":"344",
"description":"Consolidated Returns Package 8x10x12–10x12x16",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":311,
"code":"345",
"description":"Consolidated Returns Package Fill Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":312,
"code":"346",
"description":"Consolidated Returns Oversize Package Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":313,
"code":"347",
"description":"Returns Manager",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":314,
"code":"348",
"description":"ATF Entries",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":315,
"code":"350",
"description":"Additional Line Items",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":316,
"code":"351",
"description":"Food & Drug Admin",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":317,
"code":"352",
"description":"Fish & Wildlife Proc",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":318,
"code":"353",
"description":"Dept of Defense Entries",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":319,
"code":"354",
"description":"Live Entry Processing",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":320,
"code":"355",
"description":"Customized Proc Account",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":321,
"code":"356",
"description":"Russia Pickup Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":322,
"code":"357",
"description":"Complete MBG Bonus Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":323,
"code":"358",
"description":"Delivery Day Bonus Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":324,
"code":"359",
"description":"60 Minute Bonus Discount",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":325,
"code":"375",
"description":"Security Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":326,
"code":"376",
"description":"Security Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":327,
"code":"377",
"description":"Advance Fee - Mexico - Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":328,
"code":"378",
"description":"Advance Fee - Mexico - VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":329,
"code":"379",
"description":"Norway Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":330,
"code":"380",
"description":"Norway VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":331,
"code":"381",
"description":"DSP License",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":332,
"code":"382",
"description":"DEA Permit",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":333,
"code":"383",
"description":"Export Clearance",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":334,
"code":"384",
"description":"Carnet Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":335,
"code":"385",
"description":"In Bond Shipment",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":336,
"code":"386",
"description":"Piece Count Verification",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":337,
"code":"387",
"description":"Appointment Delivery Surcharge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":338,
"code":"388",
"description":"Switzerland VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":339,
"code":"389",
"description":"Ireland VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":340,
"code":"390",
"description":"Netherlands VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":341,
"code":"391",
"description":"UK VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":342,
"code":"392",
"description":"Australia VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":343,
"code":"393",
"description":"Argentina VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":344,
"code":"394",
"description":"Columbia VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":345,
"code":"395",
"description":"Dominican Republic VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":346,
"code":"396",
"description":"Guatemala VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":347,
"code":"397",
"description":"Jamaica VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":348,
"code":"398",
"description":"Venezuela VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":349,
"code":"399",
"description":"New Zealand VAT",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":350,
"code":"400",
"description":"Account Security Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":351,
"code":"401",
"description":"After Hours Clearance Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":352,
"code":"402",
"description":"Business Number Registration",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":353,
"code":"403",
"description":"Clearance End Use Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":354,
"code":"404",
"description":"Customized Service Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":355,
"code":"405",
"description":"Duty Referral Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":356,
"code":"406",
"description":"Electronic Entry Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":357,
"code":"407",
"description":"Entry Copy Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":358,
"code":"408",
"description":"Entry Corrections Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":359,
"code":"409",
"description":"Entry Form Prevalidation Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":360,
"code":"410",
"description":"Individual Entry Form Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":361,
"code":"411",
"description":"DT Claim Amend Litigation Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":362,
"code":"412",
"description":"Low Value Entry Exception Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":363,
"code":"413",
"description":"Personal Effects Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":364,
"code":"414",
"description":"Returned Goods Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":365,
"code":"415",
"description":"Temporary Import Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":366,
"code":"416",
"description":"Trade Gate Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":367,
"code":"417",
"description":"Urgent AWB Clearance Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":368,
"code":"418",
"description":"Custody Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":369,
"code":"419",
"description":"Handling Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":370,
"code":"420",
"description":"Refrigeration Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":371,
"code":"421",
"description":"Storage Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":372,
"code":"422",
"description":"Airport Transfer Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":373,
"code":"423",
"description":"Clearance Non-FedEx Transportation",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":374,
"code":"424",
"description":"Transfer In Bond Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":375,
"code":"425",
"description":"Processing Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":376,
"code":"426",
"description":"BSO AWB Revalidation Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":377,
"code":"427",
"description":"Fax Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":378,
"code":"428",
"description":"Low Value Document Exception Handling Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":379,
"code":"429",
"description":"Prepayment Postal Transfer Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":380,
"code":"430",
"description":"Import Permit Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":381,
"code":"431",
"description":"Ministry of Agriculture Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":382,
"code":"432",
"description":"Other Gov’t Agency Charge Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":383,
"code":"433",
"description":"Quarantine Fee",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":384,
"code":"444",
"description":"Cayman Island Stamp Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":385,
"code":"446",
"description":"Argentina Export Duty",
"scs_code":"CDV",
"carrier_id":2
},
{ 
"id":386,
"code":"904",
"description":"Special Handling",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":387,
"code":"687",
"description":"Additional Handling Charge",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":388,
"code":"901",
"description":"On Demand Care",
"scs_code":"MIS",
"carrier_id":2
},
{ 
"id":389,
"code":"RTN",
"description":"Standard Undeliverable Return",
"scs_code":"MIS",
"carrier_id":3
},
{ 
"id":390,
"code":"ADJ",
"description":"Post Delivery Adjustment",
"scs_code":"MIS",
"carrier_id":3
},
{ 
"id":391,
"code":"LPS",
"description":"Large Package Surcharge",
"scs_code":"MIS",
"carrier_id":3
},
{ 
"id":392,
"code":"AHC",
"description":"Additional Handling Charge",
"scs_code":"MIS",
"carrier_id":3
},
{ 
"id":393,
"code":"F/D",
"description":"Duty and Tax Forwarding Surcharge",
"scs_code":"CDV",
"carrier_id":3
},
{ 
"id":394,
"code":"FSC",
"description":"Fuel Surcharge",
"scs_code":"FSC",
"carrier_id":3
},
{ 
"id":395,
"code":"SHP",
"description":"Freight Charge",
"scs_code":"FRT",
"carrier_id":3
},
{ 
"id":396,
"code":"MIS",
"description":"Miscellaneous Charges",
"scs_code":"MIS",
"carrier_id":3
}]';
        $charges = json_decode($json_string, true);

        // Modify a few records
        DB::table('carrier_charge_codes')->insert($charges);
    }

}
