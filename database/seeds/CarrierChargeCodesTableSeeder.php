<?php

use Illuminate\Database\Seeder;

class CarrierChargeCodesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('carrier_charge_codes')->delete();
        
        \DB::table('carrier_charge_codes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '001',
                'description' => 'Declared Value Charge',
                'scs_code' => 'DVF',
                'carrier_id' => 2,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '002',
                'description' => 'Saturday Delivery Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '003',
                'description' => 'Saturday Pickup Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '004',
                'description' => 'No Account Number Used for Billing',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '005',
            'description' => 'Alaska or Hawaii (Metro delivery)',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '006',
            'description' => 'Alaska/Hawaii (Non-Metro delivery)',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '007',
                'description' => 'Recipient Address Correction Charge',
                'scs_code' => 'ADF',
                'carrier_id' => 2,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '008',
                'description' => 'Inaccessible Dangerous Goods',
                'scs_code' => 'HAZ',
                'carrier_id' => 2,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => '009',
                'description' => 'Other Charges',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => '010',
                'description' => 'Fuel Surcharge',
                'scs_code' => 'FSC',
                'carrier_id' => 2,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => '011',
                'description' => 'Pickup Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'code' => '012',
                'description' => 'Accessible Dangerous Goods',
                'scs_code' => 'HAZ',
                'carrier_id' => 2,
            ),
            12 => 
            array (
                'id' => 13,
                'code' => '013',
                'description' => 'Constant Surveillance Service Requested',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            13 => 
            array (
                'id' => 14,
                'code' => '014',
                'description' => 'Service Failure Credit',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            14 => 
            array (
                'id' => 15,
                'code' => '015',
                'description' => 'POD Service Credit',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            15 => 
            array (
                'id' => 16,
                'code' => '016',
                'description' => 'Service Credit',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            16 => 
            array (
                'id' => 17,
                'code' => '017',
                'description' => 'Package Status Credit',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            17 => 
            array (
                'id' => 18,
                'code' => '018',
                'description' => 'Late Delivery',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            18 => 
            array (
                'id' => 19,
                'code' => '019',
                'description' => 'Incorrect Billing Account Number Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            19 => 
            array (
                'id' => 20,
                'code' => '020',
                'description' => 'Invalid Bill Shipper Account Number Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            20 => 
            array (
                'id' => 21,
                'code' => '021',
                'description' => 'C.O.D. Fee',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            21 => 
            array (
                'id' => 22,
                'code' => '022',
                'description' => 'Residential Delivery Surcharge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            22 => 
            array (
                'id' => 23,
                'code' => '023',
                'description' => 'H3 Pickup Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            23 => 
            array (
                'id' => 24,
                'code' => '024',
                'description' => 'H3 Delivery Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            24 => 
            array (
                'id' => 25,
                'code' => '025',
                'description' => 'OFS/F2 Heavy Weight Inside Pickup Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            25 => 
            array (
                'id' => 26,
                'code' => '026',
                'description' => 'OFS/F2 Heavy Weight Inside Delivery Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            26 => 
            array (
                'id' => 27,
                'code' => '027',
                'description' => 'OFS/F2 Heavy Weight Residential Pickup Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            27 => 
            array (
                'id' => 28,
                'code' => '028',
                'description' => 'OFS/F2 Heavy Weight Residential Delivery Char',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            28 => 
            array (
                'id' => 29,
                'code' => '029',
                'description' => 'OFS/F2 Heavy Weight Delivery Reattempt Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            29 => 
            array (
                'id' => 30,
                'code' => '030',
                'description' => 'OFS/F2 Heavy Weight Extra Labor Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            30 => 
            array (
                'id' => 31,
                'code' => '031',
                'description' => 'OFS/F2 Heavy Weight Single Shipment Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            31 => 
            array (
                'id' => 32,
                'code' => '032',
                'description' => 'OFS/F2 Heavy Weight Reconsignment Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            32 => 
            array (
                'id' => 33,
                'code' => '033',
                'description' => 'OFS/F2 Heavy Weight Mark and Tag Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            33 => 
            array (
                'id' => 34,
                'code' => '034',
                'description' => 'Dry Ice',
                'scs_code' => 'DIF',
                'carrier_id' => 2,
            ),
            34 => 
            array (
                'id' => 35,
                'code' => '035',
                'description' => 'FedEx Corporation Audit Indicator',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            35 => 
            array (
                'id' => 36,
                'code' => '036',
                'description' => 'Hold at Station',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            36 => 
            array (
                'id' => 37,
                'code' => '037',
                'description' => 'Bundle Number',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            37 => 
            array (
                'id' => 38,
                'code' => '038',
                'description' => 'Week Day Delivery',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            38 => 
            array (
                'id' => 39,
                'code' => '039',
                'description' => 'Hold at Station Heavy Weight',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            39 => 
            array (
                'id' => 40,
                'code' => '040',
                'description' => 'Drop Off Discount',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            40 => 
            array (
                'id' => 41,
                'code' => '041',
                'description' => 'Overweight',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            41 => 
            array (
                'id' => 42,
                'code' => '042',
                'description' => 'Out of Pickup Area',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            42 => 
            array (
                'id' => 43,
                'code' => '043',
                'description' => 'Out of Delivery Area',
                'scs_code' => 'OOA',
                'carrier_id' => 2,
            ),
            43 => 
            array (
                'id' => 44,
                'code' => '044',
                'description' => 'Financial Document Option',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            44 => 
            array (
                'id' => 45,
                'code' => '045',
                'description' => 'Broker Selection Option',
                'scs_code' => 'BSO',
                'carrier_id' => 2,
            ),
            45 => 
            array (
                'id' => 46,
                'code' => '046',
                'description' => 'Cut Flowers',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            46 => 
            array (
                'id' => 47,
                'code' => '047',
                'description' => 'Argentina Broker Fee',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            47 => 
            array (
                'id' => 48,
                'code' => '048',
                'description' => 'Argentina Phito Fee',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            48 => 
            array (
                'id' => 49,
                'code' => '049',
                'description' => 'Argentina Inase Fee',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            49 => 
            array (
                'id' => 50,
                'code' => '050',
                'description' => 'Freight Charge',
                'scs_code' => 'FRT',
                'carrier_id' => 2,
            ),
            50 => 
            array (
                'id' => 51,
                'code' => '051',
                'description' => 'Cash Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            51 => 
            array (
                'id' => 52,
                'code' => '052',
                'description' => 'Original Customs Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            52 => 
            array (
                'id' => 53,
                'code' => '053',
                'description' => 'Rebill Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            53 => 
            array (
                'id' => 54,
                'code' => '054',
            'description' => 'CST (Canadian Sales Tax), Additional Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            54 => 
            array (
                'id' => 55,
                'code' => '055',
            'description' => 'Rebill CST (Canadian Sales Tax), Additional D',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            55 => 
            array (
                'id' => 56,
                'code' => '056',
                'description' => 'FedEx Additional Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            56 => 
            array (
                'id' => 57,
                'code' => '057',
                'description' => 'Rebill FedEx Additional Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            57 => 
            array (
                'id' => 58,
                'code' => '058',
            'description' => 'Cash VAT (Value Added Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            58 => 
            array (
                'id' => 59,
                'code' => '059',
            'description' => 'Original VAT (Value Added Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            59 => 
            array (
                'id' => 60,
                'code' => '060',
            'description' => 'Rebill VAT (Value Added Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            60 => 
            array (
                'id' => 61,
                'code' => '061',
            'description' => 'FedEx Additional VAT (Value Added Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            61 => 
            array (
                'id' => 62,
                'code' => '062',
            'description' => 'Rebill FedEx Additional VAT (Value Added Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            62 => 
            array (
                'id' => 63,
                'code' => '063',
                'description' => 'Puerto Rico Country Tax',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            63 => 
            array (
                'id' => 64,
                'code' => '064',
                'description' => 'Intangible Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            64 => 
            array (
                'id' => 65,
                'code' => '065',
                'description' => 'Section Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            65 => 
            array (
                'id' => 66,
                'code' => '066',
                'description' => 'Informal Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            66 => 
            array (
                'id' => 67,
                'code' => '067',
                'description' => 'Formal Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            67 => 
            array (
                'id' => 68,
                'code' => '068',
                'description' => 'HAWB Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            68 => 
            array (
                'id' => 69,
                'code' => '069',
                'description' => '1/60th Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            69 => 
            array (
                'id' => 70,
                'code' => '070',
                'description' => 'Bond Fee Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            70 => 
            array (
                'id' => 71,
                'code' => '071',
                'description' => 'TSUSA Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            71 => 
            array (
                'id' => 72,
                'code' => '072',
                'description' => 'Missing Document Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            72 => 
            array (
                'id' => 73,
                'code' => '073',
                'description' => 'Sum Additional Invoice Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            73 => 
            array (
                'id' => 74,
                'code' => '074',
                'description' => 'Advancement Fee Duty',
                'scs_code' => 'ADM',
                'carrier_id' => 2,
            ),
            74 => 
            array (
                'id' => 75,
                'code' => '075',
                'description' => 'Government Document Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            75 => 
            array (
                'id' => 76,
                'code' => '076',
                'description' => 'Post Entry Service Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            76 => 
            array (
                'id' => 77,
                'code' => '077',
                'description' => 'COMM Reimbursement Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            77 => 
            array (
                'id' => 78,
                'code' => '078',
                'description' => 'Duty Excise Charge',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            78 => 
            array (
                'id' => 79,
                'code' => '079',
                'description' => 'Additional Tax Administration Duty - Denmark',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            79 => 
            array (
                'id' => 80,
                'code' => '080',
                'description' => 'Additional Tax Administration Duty - Belgium',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            80 => 
            array (
                'id' => 81,
                'code' => '081',
                'description' => 'Additional Tax Administration Duty - Luxembou',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            81 => 
            array (
                'id' => 82,
                'code' => '082',
                'description' => 'Additional Tax Administration',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            82 => 
            array (
                'id' => 83,
                'code' => '083',
                'description' => 'Additional Tax Administration Duty - Switzerl',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            83 => 
            array (
                'id' => 84,
                'code' => '084',
                'description' => 'GST Singapore Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            84 => 
            array (
                'id' => 85,
                'code' => '085',
                'description' => 'Marca Da Bolla',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            85 => 
            array (
                'id' => 86,
                'code' => '086',
                'description' => 'GST Tax Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            86 => 
            array (
                'id' => 87,
                'code' => '087',
                'description' => 'Special Assessment Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            87 => 
            array (
                'id' => 88,
                'code' => '088',
                'description' => 'Customs Processing Fee Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            88 => 
            array (
                'id' => 89,
                'code' => '089',
                'description' => '1/1000 Charge Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            89 => 
            array (
                'id' => 90,
                'code' => '090',
                'description' => 'Additional Tax Administration Duty - Korea',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            90 => 
            array (
                'id' => 91,
                'code' => '091',
                'description' => 'TVA Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            91 => 
            array (
                'id' => 92,
                'code' => '092',
                'description' => 'Austrian Payor Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            92 => 
            array (
                'id' => 93,
                'code' => '093',
                'description' => 'Antidumping Duty',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            93 => 
            array (
                'id' => 94,
                'code' => '094',
                'description' => 'Additional Tax Administration Duty - France',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            94 => 
            array (
                'id' => 95,
                'code' => '095',
                'description' => 'Additional Tax Administration  Duty - Italy',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            95 => 
            array (
                'id' => 96,
                'code' => '096',
                'description' => 'Taiwan VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            96 => 
            array (
                'id' => 97,
                'code' => '097',
                'description' => 'Intangible Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            97 => 
            array (
                'id' => 98,
                'code' => '098',
                'description' => 'Section Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            98 => 
            array (
                'id' => 99,
                'code' => '099',
                'description' => 'Informal Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            99 => 
            array (
                'id' => 100,
                'code' => '100',
                'description' => 'Formal Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            100 => 
            array (
                'id' => 101,
                'code' => '101',
                'description' => 'HAWB Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            101 => 
            array (
                'id' => 102,
                'code' => '102',
                'description' => '1/60th Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            102 => 
            array (
                'id' => 103,
                'code' => '103',
                'description' => 'Storage or Bond Fee VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            103 => 
            array (
                'id' => 104,
                'code' => '104',
                'description' => 'TSUSA Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            104 => 
            array (
                'id' => 105,
                'code' => '105',
                'description' => 'Missing Document Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            105 => 
            array (
                'id' => 106,
                'code' => '106',
                'description' => 'Sum Additional Invoice VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            106 => 
            array (
                'id' => 107,
                'code' => '107',
                'description' => 'Advancement Fee VAT',
                'scs_code' => 'ADM',
                'carrier_id' => 2,
            ),
            107 => 
            array (
                'id' => 108,
                'code' => '108',
                'description' => 'Government Document Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            108 => 
            array (
                'id' => 109,
                'code' => '109',
                'description' => 'Post Entry Service VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            109 => 
            array (
                'id' => 110,
                'code' => '110',
                'description' => 'COMM Reimbursement Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            110 => 
            array (
                'id' => 111,
                'code' => '111',
                'description' => 'VAT Excise Charge',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            111 => 
            array (
                'id' => 112,
                'code' => '112',
                'description' => 'VAT Excise Charge',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            112 => 
            array (
                'id' => 113,
                'code' => '113',
                'description' => 'Additional Tax Administration VAT- Denmark',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            113 => 
            array (
                'id' => 114,
                'code' => '114',
                'description' => 'Additional Tax Administration VAT- Belgium',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            114 => 
            array (
                'id' => 115,
                'code' => '115',
                'description' => 'Additional Tax Administration VAT - Luxembour',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            115 => 
            array (
                'id' => 116,
                'code' => '116',
                'description' => 'Additional Tax Administration VAT - Austria',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            116 => 
            array (
                'id' => 117,
                'code' => '117',
                'description' => 'Additional Tax Administration VAT - Switzerla',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            117 => 
            array (
                'id' => 118,
                'code' => '118',
                'description' => 'GST Singapore VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            118 => 
            array (
                'id' => 119,
                'code' => '119',
                'description' => 'Marca Da Bolla VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            119 => 
            array (
                'id' => 120,
                'code' => '120',
                'description' => 'GST Tax VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            120 => 
            array (
                'id' => 121,
                'code' => '121',
                'description' => 'Special Assessment Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            121 => 
            array (
                'id' => 122,
                'code' => '122',
                'description' => 'Customs Processing Fee VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            122 => 
            array (
                'id' => 123,
                'code' => '123',
                'description' => '1/1000 Charge VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            123 => 
            array (
                'id' => 124,
                'code' => '124',
                'description' => 'Additional Tax Administration VAT - Korea',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            124 => 
            array (
                'id' => 125,
                'code' => '125',
                'description' => 'TVA VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            125 => 
            array (
                'id' => 126,
                'code' => '126',
                'description' => 'Austrian Payor VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            126 => 
            array (
                'id' => 127,
                'code' => '127',
                'description' => 'Antidumping Duty VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            127 => 
            array (
                'id' => 128,
                'code' => '128',
                'description' => 'Additional Tax Administration VAT - France',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            128 => 
            array (
                'id' => 129,
                'code' => '129',
                'description' => 'Additional Tax Administration VAT - Italy',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            129 => 
            array (
                'id' => 130,
                'code' => '130',
                'description' => 'Additional Tax Administration VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            130 => 
            array (
                'id' => 131,
                'code' => '131',
            'description' => 'PST AB (Alberta Provincial Sales Tax)',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            131 => 
            array (
                'id' => 132,
                'code' => '132',
                'description' => 'PST BC (British Columbia Provincial Sales Tax',
                    'scs_code' => 'CDV',
                    'carrier_id' => 2,
                ),
                132 => 
                array (
                    'id' => 133,
                    'code' => '133',
                'description' => 'PST MB (Manitoba Provincial Sales Tax)',
                    'scs_code' => 'CDV',
                    'carrier_id' => 2,
                ),
                133 => 
                array (
                    'id' => 134,
                    'code' => '134',
                'description' => 'PST NB (New Brunswick Provincial Sales Tax)',
                    'scs_code' => 'CDV',
                    'carrier_id' => 2,
                ),
                134 => 
                array (
                    'id' => 135,
                    'code' => '135',
                'description' => 'PST NF  (Newfoundland Provincial Sales Tax)',
                    'scs_code' => 'CDV',
                    'carrier_id' => 2,
                ),
                135 => 
                array (
                    'id' => 136,
                    'code' => '136',
                    'description' => 'PST NT (Northwest Territories Provincial Sale',
                        'scs_code' => 'CDV',
                        'carrier_id' => 2,
                    ),
                    136 => 
                    array (
                        'id' => 137,
                        'code' => '137',
                    'description' => 'PST NS (Nova Scotia Provincial Sales Tax)',
                        'scs_code' => 'CDV',
                        'carrier_id' => 2,
                    ),
                    137 => 
                    array (
                        'id' => 138,
                        'code' => '138',
                    'description' => 'PST ON (Ontario Provincial Sales Tax)',
                        'scs_code' => 'CDV',
                        'carrier_id' => 2,
                    ),
                    138 => 
                    array (
                        'id' => 139,
                        'code' => '139',
                        'description' => 'PST PE (Prince Edward Island Provincial Sales',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        139 => 
                        array (
                            'id' => 140,
                            'code' => '140',
                        'description' => 'PST PQ (Quebec Provincial Sales Tax)',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        140 => 
                        array (
                            'id' => 141,
                            'code' => '141',
                        'description' => 'PST SK (Saskatchewan Provincial Sales Tax)',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        141 => 
                        array (
                            'id' => 142,
                            'code' => '142',
                        'description' => 'PST YK (Yukon Provincial Sales Tax)',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        142 => 
                        array (
                            'id' => 143,
                            'code' => '150',
                            'description' => 'Non Document Charge',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        143 => 
                        array (
                            'id' => 144,
                            'code' => '157',
                            'description' => 'Low Item Weight',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        144 => 
                        array (
                            'id' => 145,
                            'code' => '161',
                        'description' => 'QST (Quebec Sales Tax) Charge ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        145 => 
                        array (
                            'id' => 146,
                            'code' => '162',
                            'description' => 'Canada GST Freight ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        146 => 
                        array (
                            'id' => 147,
                            'code' => '163',
                            'description' => 'Mexico IVA Freight',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        147 => 
                        array (
                            'id' => 148,
                            'code' => '164',
                            'description' => 'Taiwan VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        148 => 
                        array (
                            'id' => 149,
                            'code' => '165',
                            'description' => 'Grenada VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        149 => 
                        array (
                            'id' => 150,
                            'code' => '166',
                            'description' => 'Venezuela VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        150 => 
                        array (
                            'id' => 151,
                            'code' => '170',
                            'description' => 'Belgium VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        151 => 
                        array (
                            'id' => 152,
                            'code' => '171',
                            'description' => 'Luxembourg VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        152 => 
                        array (
                            'id' => 153,
                            'code' => '172',
                            'description' => 'Germany VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        153 => 
                        array (
                            'id' => 154,
                            'code' => '173',
                            'description' => 'Great Britain VAT ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        154 => 
                        array (
                            'id' => 155,
                            'code' => '174',
                            'description' => 'Italy VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        155 => 
                        array (
                            'id' => 156,
                            'code' => '175',
                            'description' => 'Netherlands VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        156 => 
                        array (
                            'id' => 157,
                            'code' => '176',
                            'description' => 'France VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        157 => 
                        array (
                            'id' => 158,
                            'code' => '177',
                            'description' => 'Austria VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        158 => 
                        array (
                            'id' => 159,
                            'code' => '178',
                            'description' => 'Ireland VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        159 => 
                        array (
                            'id' => 160,
                            'code' => '179',
                            'description' => 'Sweden VAT ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        160 => 
                        array (
                            'id' => 161,
                            'code' => '180',
                            'description' => 'Denmark VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        161 => 
                        array (
                            'id' => 162,
                            'code' => '181',
                            'description' => 'Finland VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        162 => 
                        array (
                            'id' => 163,
                            'code' => '182',
                            'description' => 'Greece VAT ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        163 => 
                        array (
                            'id' => 164,
                            'code' => '183',
                            'description' => 'Spain VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        164 => 
                        array (
                            'id' => 165,
                            'code' => '184',
                            'description' => 'Portugal VAT ',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        165 => 
                        array (
                            'id' => 166,
                            'code' => '185',
                            'description' => 'Discount Amount',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        166 => 
                        array (
                            'id' => 167,
                            'code' => '186',
                            'description' => 'Memphis Discount Amount',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        167 => 
                        array (
                            'id' => 168,
                            'code' => '187',
                            'description' => 'Dropoff Discount',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        168 => 
                        array (
                            'id' => 169,
                            'code' => '188',
                            'description' => 'Rebate',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        169 => 
                        array (
                            'id' => 170,
                            'code' => '189',
                            'description' => 'Bermuda Terminal Fee',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        170 => 
                        array (
                            'id' => 171,
                            'code' => '190',
                            'description' => 'Bundle Number',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        171 => 
                        array (
                            'id' => 172,
                            'code' => '191',
                            'description' => 'Canadian Duty GST/QS',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        172 => 
                        array (
                            'id' => 173,
                            'code' => '192',
                            'description' => 'Canadian GST/QST Tax',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        173 => 
                        array (
                            'id' => 174,
                            'code' => '193',
                            'description' => 'Freight Other',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        174 => 
                        array (
                            'id' => 175,
                            'code' => '194',
                            'description' => 'Duty Other',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        175 => 
                        array (
                            'id' => 176,
                            'code' => '195',
                            'description' => 'Tax Other',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        176 => 
                        array (
                            'id' => 177,
                            'code' => '196',
                            'description' => 'Duty/Tax Surcharges',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        177 => 
                        array (
                            'id' => 178,
                            'code' => '197',
                            'description' => 'Service Other',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        178 => 
                        array (
                            'id' => 179,
                            'code' => '198',
                            'description' => 'Rebill Fee',
                            'scs_code' => 'MIS',
                            'carrier_id' => 2,
                        ),
                        179 => 
                        array (
                            'id' => 180,
                            'code' => '202',
                        'description' => 'HST (Harmonized Sales Tax) - Duty',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        180 => 
                        array (
                            'id' => 181,
                            'code' => '203',
                        'description' => 'HST (Harmonized Sales Tax) - VAT',
                            'scs_code' => 'CDV',
                            'carrier_id' => 2,
                        ),
                        181 => 
                        array (
                            'id' => 182,
                            'code' => '204',
                            'description' => 'Canadian HST NB (New Brunswick Harmonized Sal',
                                'scs_code' => 'CDV',
                                'carrier_id' => 2,
                            ),
                            182 => 
                            array (
                                'id' => 183,
                                'code' => '205',
                                'description' => 'Canadian HST NF (Newfoundland Harmonized Sale',
                                    'scs_code' => 'CDV',
                                    'carrier_id' => 2,
                                ),
                                183 => 
                                array (
                                    'id' => 184,
                                    'code' => '206',
                                    'description' => 'Canadian HST NS (Nova Scotia Harmonized Sales',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    184 => 
                                    array (
                                        'id' => 185,
                                        'code' => '207',
                                        'description' => 'UAE GPA',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    185 => 
                                    array (
                                        'id' => 186,
                                        'code' => '208',
                                        'description' => 'India Service Tax',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    186 => 
                                    array (
                                        'id' => 187,
                                        'code' => '209',
                                        'description' => 'Thailand VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    187 => 
                                    array (
                                        'id' => 188,
                                        'code' => '210',
                                        'description' => 'IPFS Dropoff',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    188 => 
                                    array (
                                        'id' => 189,
                                        'code' => '211',
                                        'description' => 'IPFS HAL',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    189 => 
                                    array (
                                        'id' => 190,
                                        'code' => '212',
                                        'description' => 'IPFS BSO',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    190 => 
                                    array (
                                        'id' => 191,
                                        'code' => '213',
                                        'description' => 'Oversize Package',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    191 => 
                                    array (
                                        'id' => 192,
                                        'code' => '214',
                                        'description' => 'Out of Pickup Zone',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    192 => 
                                    array (
                                        'id' => 193,
                                        'code' => '215',
                                        'description' => 'Bermuda Terminal Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    193 => 
                                    array (
                                        'id' => 194,
                                        'code' => '216',
                                        'description' => 'Payment/Credit',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    194 => 
                                    array (
                                        'id' => 195,
                                        'code' => '217',
                                        'description' => 'Sunday Pickup',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    195 => 
                                    array (
                                        'id' => 196,
                                        'code' => '219',
                                        'description' => 'Invalid Third Party Account Number Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    196 => 
                                    array (
                                        'id' => 197,
                                        'code' => '220',
                                        'description' => 'Local Tax Charge',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    197 => 
                                    array (
                                        'id' => 198,
                                        'code' => '221',
                                        'description' => 'Maximum Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    198 => 
                                    array (
                                        'id' => 199,
                                        'code' => '222',
                                        'description' => 'VAT Advance Fee Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    199 => 
                                    array (
                                        'id' => 200,
                                        'code' => '223',
                                        'description' => 'Corporate Purchasing Card',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    200 => 
                                    array (
                                        'id' => 201,
                                        'code' => '224',
                                        'description' => 'Credit Card Decline Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    201 => 
                                    array (
                                        'id' => 202,
                                        'code' => '225',
                                        'description' => 'Liftgate Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    202 => 
                                    array (
                                        'id' => 203,
                                        'code' => '226',
                                        'description' => 'Priority Alert',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    203 => 
                                    array (
                                        'id' => 204,
                                        'code' => '228',
                                        'description' => 'Memphis Rebate',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    204 => 
                                    array (
                                        'id' => 205,
                                        'code' => '229',
                                        'description' => 'Emerge, Consolidation',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    205 => 
                                    array (
                                        'id' => 206,
                                        'code' => '230',
                                        'description' => 'Delivery Area Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    206 => 
                                    array (
                                        'id' => 207,
                                        'code' => '231',
                                        'description' => 'MBG Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    207 => 
                                    array (
                                        'id' => 208,
                                        'code' => '232',
                                        'description' => 'Duty and Tax Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    208 => 
                                    array (
                                        'id' => 209,
                                        'code' => '233',
                                        'description' => 'Weight Change Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    209 => 
                                    array (
                                        'id' => 210,
                                        'code' => '234',
                                        'description' => 'Handling Change Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    210 => 
                                    array (
                                        'id' => 211,
                                        'code' => '235',
                                        'description' => 'Service Change Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    211 => 
                                    array (
                                        'id' => 212,
                                        'code' => '236',
                                        'description' => 'Delivery Change Waiver',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    212 => 
                                    array (
                                        'id' => 213,
                                        'code' => '237',
                                        'description' => 'Extra Hours Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    213 => 
                                    array (
                                        'id' => 214,
                                        'code' => '238',
                                        'description' => 'VAT Advance Fee - Denmark - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    214 => 
                                    array (
                                        'id' => 215,
                                        'code' => '239',
                                        'description' => 'VAT Advance Fee - Sweden - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    215 => 
                                    array (
                                        'id' => 216,
                                        'code' => '240',
                                        'description' => 'VAT Advance Fee - Thailand - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    216 => 
                                    array (
                                        'id' => 217,
                                        'code' => '241',
                                        'description' => 'Customs Fee - Thailand - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    217 => 
                                    array (
                                        'id' => 218,
                                        'code' => '242',
                                        'description' => 'Customs Clearance Fee - Thailand - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    218 => 
                                    array (
                                        'id' => 219,
                                        'code' => '243',
                                        'description' => 'VAT Customs Clearance - Thailand - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    219 => 
                                    array (
                                        'id' => 220,
                                        'code' => '244',
                                        'description' => 'VAT Cash Customer - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    220 => 
                                    array (
                                        'id' => 221,
                                        'code' => '245',
                                        'description' => 'VAT Advance Fee - Spain - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    221 => 
                                    array (
                                        'id' => 222,
                                        'code' => '246',
                                        'description' => 'VAT Advance Fee - Denmark - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    222 => 
                                    array (
                                        'id' => 223,
                                        'code' => '247',
                                        'description' => 'VAT Advance Fee - Sweden - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    223 => 
                                    array (
                                        'id' => 224,
                                        'code' => '248',
                                        'description' => 'VAT Advance Fee - Thailand - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    224 => 
                                    array (
                                        'id' => 225,
                                        'code' => '249',
                                        'description' => 'Customs Fee - Thailand - VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    225 => 
                                    array (
                                        'id' => 226,
                                        'code' => '250',
                                        'description' => 'Customs Clearance Fee - Thailand - VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    226 => 
                                    array (
                                        'id' => 227,
                                        'code' => '251',
                                        'description' => 'VAT Customs Clearance - Thailand',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    227 => 
                                    array (
                                        'id' => 228,
                                        'code' => '252',
                                        'description' => 'VAT Cash Customer - Thailand',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    228 => 
                                    array (
                                        'id' => 229,
                                        'code' => '253',
                                        'description' => 'Thailand Customs Fee - Freight',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    229 => 
                                    array (
                                        'id' => 230,
                                        'code' => '254',
                                        'description' => 'Thailand Customs Clearance Fee - Freight',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    230 => 
                                    array (
                                        'id' => 231,
                                        'code' => '255',
                                        'description' => 'Sweden VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    231 => 
                                    array (
                                        'id' => 232,
                                        'code' => '256',
                                        'description' => 'Denmark VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    232 => 
                                    array (
                                        'id' => 233,
                                        'code' => '257',
                                        'description' => 'Earned Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    233 => 
                                    array (
                                        'id' => 234,
                                        'code' => '258',
                                        'description' => 'Grace Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    234 => 
                                    array (
                                        'id' => 235,
                                        'code' => '259',
                                        'description' => 'Australia GST - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    235 => 
                                    array (
                                        'id' => 236,
                                        'code' => '260',
                                        'description' => 'Australia GST - VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    236 => 
                                    array (
                                        'id' => 237,
                                        'code' => '261',
                                        'description' => 'Australia GST - Freight',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    237 => 
                                    array (
                                        'id' => 238,
                                        'code' => '262',
                                    'description' => 'Discount (V) Volume Incentive',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    238 => 
                                    array (
                                        'id' => 239,
                                        'code' => '263',
                                    'description' => 'Discount (P) Performance Pricing',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    239 => 
                                    array (
                                        'id' => 240,
                                        'code' => '264',
                                    'description' => 'Declared Value > $0 (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    240 => 
                                    array (
                                        'id' => 241,
                                        'code' => '265',
                                        'description' => 'Credit – Ground',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    241 => 
                                    array (
                                        'id' => 242,
                                        'code' => '266',
                                        'description' => 'Credit –  Home Delivery',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    242 => 
                                    array (
                                        'id' => 243,
                                        'code' => '267',
                                        'description' => 'Automatic Proof of Delivery',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    243 => 
                                    array (
                                        'id' => 244,
                                        'code' => '268',
                                        'description' => 'Additional Handling',
                                        'scs_code' => 'ADH',
                                        'carrier_id' => 2,
                                    ),
                                    244 => 
                                    array (
                                        'id' => 245,
                                        'code' => '269',
                                        'description' => 'Extra Service Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    245 => 
                                    array (
                                        'id' => 246,
                                        'code' => '270',
                                        'description' => 'Overweight > 150 lbs.',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    246 => 
                                    array (
                                        'id' => 247,
                                        'code' => '271',
                                        'description' => 'Home Delivery Signature Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    247 => 
                                    array (
                                        'id' => 248,
                                        'code' => '272',
                                    'description' => 'Address Correction (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    248 => 
                                    array (
                                        'id' => 249,
                                        'code' => '273',
                                    'description' => 'Residential Delivery (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    249 => 
                                    array (
                                        'id' => 250,
                                        'code' => '274',
                                    'description' => 'Residential Delivery - Rural (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    250 => 
                                    array (
                                        'id' => 251,
                                        'code' => '275',
                                    'description' => 'Hazardous Material (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    251 => 
                                    array (
                                        'id' => 252,
                                        'code' => '276',
                                        'description' => 'Home Delivery Residential Delivery Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    252 => 
                                    array (
                                        'id' => 253,
                                        'code' => '277',
                                        'description' => 'Home Delivery Residential Rural Delivery Serv',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    253 => 
                                    array (
                                        'id' => 254,
                                        'code' => '278',
                                        'description' => 'Cash C.O.D. Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    254 => 
                                    array (
                                        'id' => 255,
                                        'code' => '279',
                                        'description' => 'Cash C.O.D. High Intensity Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    255 => 
                                    array (
                                        'id' => 256,
                                        'code' => '280',
                                        'description' => 'Cash C.O.D. Extra Difference Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    256 => 
                                    array (
                                        'id' => 257,
                                        'code' => '281',
                                        'description' => 'Electronic C.O.D. – 24 hrs.',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    257 => 
                                    array (
                                        'id' => 258,
                                        'code' => '282',
                                        'description' => 'Electronic C.O.D. – 48 hrs.',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    258 => 
                                    array (
                                        'id' => 259,
                                        'code' => '283',
                                        'description' => 'Proof of Delivery Advantage Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    259 => 
                                    array (
                                        'id' => 260,
                                        'code' => '284',
                                        'description' => 'FedEx Ground Home Delivery Out of Service Are',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    260 => 
                                    array (
                                        'id' => 261,
                                        'code' => '285',
                                        'description' => 'FedEx Ground Home Delivery of Hazardous Mater',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    261 => 
                                    array (
                                        'id' => 262,
                                        'code' => '286',
                                        'description' => 'C.O.D. Fee - Ground',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    262 => 
                                    array (
                                        'id' => 263,
                                        'code' => '287',
                                        'description' => 'FedEx Ground Home Delivery of a Pkg > 70 lbs.',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    263 => 
                                    array (
                                        'id' => 264,
                                        'code' => '288',
                                        'description' => 'FedEx Ground Home Delivery COD Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    264 => 
                                    array (
                                        'id' => 265,
                                        'code' => '289',
                                        'description' => 'Call Tag',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    265 => 
                                    array (
                                        'id' => 266,
                                        'code' => '290',
                                    'description' => 'A.O.D. - Acknowledgment of Delivery (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    266 => 
                                    array (
                                        'id' => 267,
                                        'code' => '291',
                                        'description' => 'Multiweight Address Correction',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    267 => 
                                    array (
                                        'id' => 268,
                                        'code' => '292',
                                        'description' => 'Home Delivery Date Certain Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    268 => 
                                    array (
                                        'id' => 269,
                                        'code' => '293',
                                        'description' => 'Home Delivery Appointment Delivery Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    269 => 
                                    array (
                                        'id' => 270,
                                        'code' => '294',
                                        'description' => 'FedEx Ground Home Delivery Forced Appointment',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    270 => 
                                    array (
                                        'id' => 271,
                                        'code' => '295',
                                        'description' => 'Home Delivery Evening Service',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    271 => 
                                    array (
                                        'id' => 272,
                                        'code' => '296',
                                        'description' => 'Residential - Customer Level',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    272 => 
                                    array (
                                        'id' => 273,
                                        'code' => '297',
                                        'description' => 'Weekly ECOD 24-hour',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    273 => 
                                    array (
                                        'id' => 274,
                                        'code' => '298',
                                        'description' => 'Weekly ECOD 48-hour',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    274 => 
                                    array (
                                        'id' => 275,
                                        'code' => '299',
                                    'description' => 'Quickship (Partnership)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    275 => 
                                    array (
                                        'id' => 276,
                                        'code' => '300',
                                        'description' => 'POD Advantage Weekly Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    276 => 
                                    array (
                                        'id' => 277,
                                        'code' => '301',
                                        'description' => 'Host to Host Project Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    277 => 
                                    array (
                                        'id' => 278,
                                        'code' => '302',
                                        'description' => 'Host to Host Installation Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    278 => 
                                    array (
                                        'id' => 279,
                                        'code' => '303',
                                        'description' => 'Host to Host Communication Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    279 => 
                                    array (
                                        'id' => 280,
                                        'code' => '304',
                                        'description' => 'Host to Host Network Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    280 => 
                                    array (
                                        'id' => 281,
                                        'code' => '305',
                                        'description' => 'Weekly Service Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    281 => 
                                    array (
                                        'id' => 282,
                                        'code' => '306',
                                        'description' => 'Call Tag – Package Level',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    282 => 
                                    array (
                                        'id' => 283,
                                        'code' => '308',
                                        'description' => 'Home Delivery Date Certain Service - Invoice ',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    283 => 
                                    array (
                                        'id' => 284,
                                        'code' => '309',
                                        'description' => 'Home Delivery Appointment Delivery Service - ',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    284 => 
                                    array (
                                        'id' => 285,
                                        'code' => '310',
                                        'description' => 'Home Delivery Evening Service - Invoice Level',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    285 => 
                                    array (
                                        'id' => 286,
                                        'code' => '311',
                                    'description' => 'Fuel Surcharge (Ground)',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    286 => 
                                    array (
                                        'id' => 287,
                                        'code' => '312',
                                        'description' => 'Additional Handling Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    287 => 
                                    array (
                                        'id' => 288,
                                        'code' => '313',
                                        'description' => 'New Zealand GST - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    288 => 
                                    array (
                                        'id' => 289,
                                        'code' => '314',
                                        'description' => 'New Zealand GST - VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    289 => 
                                    array (
                                        'id' => 290,
                                        'code' => '315',
                                        'description' => 'Automation Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    290 => 
                                    array (
                                        'id' => 291,
                                        'code' => '316',
                                        'description' => 'Regular Pickup/Dropoff Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    291 => 
                                    array (
                                        'id' => 292,
                                        'code' => '317',
                                        'description' => 'Zone Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    292 => 
                                    array (
                                        'id' => 293,
                                        'code' => '318',
                                        'description' => 'Zip to Zip Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    293 => 
                                    array (
                                        'id' => 294,
                                        'code' => '319',
                                        'description' => 'Day of Week Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    294 => 
                                    array (
                                        'id' => 295,
                                        'code' => '320',
                                        'description' => 'Guatemala IVA Freight',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    295 => 
                                    array (
                                        'id' => 296,
                                        'code' => '321',
                                        'description' => 'Dominican Republic ITIBIS',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    296 => 
                                    array (
                                        'id' => 297,
                                        'code' => '322',
                                        'description' => 'Duty/Tax Advance Fee',
                                        'scs_code' => 'ADM',
                                        'carrier_id' => 2,
                                    ),
                                    297 => 
                                    array (
                                        'id' => 298,
                                        'code' => '330',
                                        'description' => 'Net Returns Transmission Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    298 => 
                                    array (
                                        'id' => 299,
                                        'code' => '331',
                                    'description' => 'Ground Out-of-Cycle (Supplemental) Weight Cor',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    299 => 
                                    array (
                                        'id' => 300,
                                        'code' => '333',
                                        'description' => 'Day & Pickup/Dropoff Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    300 => 
                                    array (
                                        'id' => 301,
                                        'code' => '334',
                                        'description' => 'Day & Zone Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    301 => 
                                    array (
                                        'id' => 302,
                                        'code' => '335',
                                        'description' => 'Day & Zip Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    302 => 
                                    array (
                                        'id' => 303,
                                        'code' => '336',
                                        'description' => 'Pickup/Dropoff & Zone Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    303 => 
                                    array (
                                        'id' => 304,
                                        'code' => '337',
                                        'description' => 'Pickup/Dropoff & Zip Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    304 => 
                                    array (
                                        'id' => 305,
                                        'code' => '338',
                                        'description' => 'Linehaul Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    305 => 
                                    array (
                                        'id' => 306,
                                        'code' => '340',
                                        'description' => 'Consolidated Returns Polybag',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    306 => 
                                    array (
                                        'id' => 307,
                                        'code' => '341',
                                        'description' => 'Consolidated Returns Package',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    307 => 
                                    array (
                                        'id' => 308,
                                        'code' => '342',
                                        'description' => 'Consolidated Returns Package 4x4x6–4x8x12',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    308 => 
                                    array (
                                        'id' => 309,
                                        'code' => '343',
                                        'description' => 'Consolidated Returns Package 6x6x10–6x6x16',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    309 => 
                                    array (
                                        'id' => 310,
                                        'code' => '344',
                                        'description' => 'Consolidated Returns Package 8x10x12–10x12x16',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    310 => 
                                    array (
                                        'id' => 311,
                                        'code' => '345',
                                        'description' => 'Consolidated Returns Package Fill Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    311 => 
                                    array (
                                        'id' => 312,
                                        'code' => '346',
                                        'description' => 'Consolidated Returns Oversize Package Charge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    312 => 
                                    array (
                                        'id' => 313,
                                        'code' => '347',
                                        'description' => 'Returns Manager',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    313 => 
                                    array (
                                        'id' => 314,
                                        'code' => '348',
                                        'description' => 'ATF Entries',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    314 => 
                                    array (
                                        'id' => 315,
                                        'code' => '350',
                                        'description' => 'Additional Line Items',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    315 => 
                                    array (
                                        'id' => 316,
                                        'code' => '351',
                                        'description' => 'Food & Drug Admin',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    316 => 
                                    array (
                                        'id' => 317,
                                        'code' => '352',
                                        'description' => 'Fish & Wildlife Proc',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    317 => 
                                    array (
                                        'id' => 318,
                                        'code' => '353',
                                        'description' => 'Dept of Defense Entries',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    318 => 
                                    array (
                                        'id' => 319,
                                        'code' => '354',
                                        'description' => 'Live Entry Processing',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    319 => 
                                    array (
                                        'id' => 320,
                                        'code' => '355',
                                        'description' => 'Customized Proc Account',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    320 => 
                                    array (
                                        'id' => 321,
                                        'code' => '356',
                                        'description' => 'Russia Pickup Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    321 => 
                                    array (
                                        'id' => 322,
                                        'code' => '357',
                                        'description' => 'Complete MBG Bonus Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    322 => 
                                    array (
                                        'id' => 323,
                                        'code' => '358',
                                        'description' => 'Delivery Day Bonus Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    323 => 
                                    array (
                                        'id' => 324,
                                        'code' => '359',
                                        'description' => '60 Minute Bonus Discount',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    324 => 
                                    array (
                                        'id' => 325,
                                        'code' => '375',
                                        'description' => 'Security Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    325 => 
                                    array (
                                        'id' => 326,
                                        'code' => '376',
                                        'description' => 'Security Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    326 => 
                                    array (
                                        'id' => 327,
                                        'code' => '377',
                                        'description' => 'Advance Fee - Mexico - Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    327 => 
                                    array (
                                        'id' => 328,
                                        'code' => '378',
                                        'description' => 'Advance Fee - Mexico - VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    328 => 
                                    array (
                                        'id' => 329,
                                        'code' => '379',
                                        'description' => 'Norway Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    329 => 
                                    array (
                                        'id' => 330,
                                        'code' => '380',
                                        'description' => 'Norway VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    330 => 
                                    array (
                                        'id' => 331,
                                        'code' => '381',
                                        'description' => 'DSP License',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    331 => 
                                    array (
                                        'id' => 332,
                                        'code' => '382',
                                        'description' => 'DEA Permit',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    332 => 
                                    array (
                                        'id' => 333,
                                        'code' => '383',
                                        'description' => 'Export Clearance',
                                        'scs_code' => 'CLR',
                                        'carrier_id' => 2,
                                    ),
                                    333 => 
                                    array (
                                        'id' => 334,
                                        'code' => '384',
                                        'description' => 'Carnet Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    334 => 
                                    array (
                                        'id' => 335,
                                        'code' => '385',
                                        'description' => 'In Bond Shipment',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    335 => 
                                    array (
                                        'id' => 336,
                                        'code' => '386',
                                        'description' => 'Piece Count Verification',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    336 => 
                                    array (
                                        'id' => 337,
                                        'code' => '387',
                                        'description' => 'Appointment Delivery Surcharge',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    337 => 
                                    array (
                                        'id' => 338,
                                        'code' => '388',
                                        'description' => 'Switzerland VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    338 => 
                                    array (
                                        'id' => 339,
                                        'code' => '389',
                                        'description' => 'Ireland VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    339 => 
                                    array (
                                        'id' => 340,
                                        'code' => '390',
                                        'description' => 'Netherlands VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    340 => 
                                    array (
                                        'id' => 341,
                                        'code' => '391',
                                        'description' => 'UK VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    341 => 
                                    array (
                                        'id' => 342,
                                        'code' => '392',
                                        'description' => 'Australia VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    342 => 
                                    array (
                                        'id' => 343,
                                        'code' => '393',
                                        'description' => 'Argentina VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    343 => 
                                    array (
                                        'id' => 344,
                                        'code' => '394',
                                        'description' => 'Columbia VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    344 => 
                                    array (
                                        'id' => 345,
                                        'code' => '395',
                                        'description' => 'Dominican Republic VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    345 => 
                                    array (
                                        'id' => 346,
                                        'code' => '396',
                                        'description' => 'Guatemala VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    346 => 
                                    array (
                                        'id' => 347,
                                        'code' => '397',
                                        'description' => 'Jamaica VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    347 => 
                                    array (
                                        'id' => 348,
                                        'code' => '398',
                                        'description' => 'Venezuela VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    348 => 
                                    array (
                                        'id' => 349,
                                        'code' => '399',
                                        'description' => 'New Zealand VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    349 => 
                                    array (
                                        'id' => 350,
                                        'code' => '400',
                                        'description' => 'Account Security Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    350 => 
                                    array (
                                        'id' => 351,
                                        'code' => '401',
                                        'description' => 'After Hours Clearance Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    351 => 
                                    array (
                                        'id' => 352,
                                        'code' => '402',
                                        'description' => 'Business Number Registration',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    352 => 
                                    array (
                                        'id' => 353,
                                        'code' => '403',
                                        'description' => 'Clearance End Use Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    353 => 
                                    array (
                                        'id' => 354,
                                        'code' => '404',
                                        'description' => 'Customized Service Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    354 => 
                                    array (
                                        'id' => 355,
                                        'code' => '405',
                                        'description' => 'Duty Referral Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    355 => 
                                    array (
                                        'id' => 356,
                                        'code' => '406',
                                        'description' => 'Electronic Entry Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    356 => 
                                    array (
                                        'id' => 357,
                                        'code' => '407',
                                        'description' => 'Entry Copy Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    357 => 
                                    array (
                                        'id' => 358,
                                        'code' => '408',
                                        'description' => 'Entry Corrections Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    358 => 
                                    array (
                                        'id' => 359,
                                        'code' => '409',
                                        'description' => 'Entry Form Prevalidation Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    359 => 
                                    array (
                                        'id' => 360,
                                        'code' => '410',
                                        'description' => 'Individual Entry Form Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    360 => 
                                    array (
                                        'id' => 361,
                                        'code' => '411',
                                        'description' => 'DT Claim Amend Litigation Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    361 => 
                                    array (
                                        'id' => 362,
                                        'code' => '412',
                                        'description' => 'Low Value Entry Exception Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    362 => 
                                    array (
                                        'id' => 363,
                                        'code' => '413',
                                        'description' => 'Personal Effects Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    363 => 
                                    array (
                                        'id' => 364,
                                        'code' => '414',
                                        'description' => 'Returned Goods Fee',
                                        'scs_code' => 'RTN',
                                        'carrier_id' => 2,
                                    ),
                                    364 => 
                                    array (
                                        'id' => 365,
                                        'code' => '415',
                                        'description' => 'Temporary Import Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    365 => 
                                    array (
                                        'id' => 366,
                                        'code' => '416',
                                        'description' => 'Trade Gate Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    366 => 
                                    array (
                                        'id' => 367,
                                        'code' => '417',
                                        'description' => 'Urgent AWB Clearance Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    367 => 
                                    array (
                                        'id' => 368,
                                        'code' => '418',
                                        'description' => 'Custody Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    368 => 
                                    array (
                                        'id' => 369,
                                        'code' => '419',
                                        'description' => 'Handling Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    369 => 
                                    array (
                                        'id' => 370,
                                        'code' => '420',
                                        'description' => 'Refrigeration Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    370 => 
                                    array (
                                        'id' => 371,
                                        'code' => '421',
                                        'description' => 'Storage Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    371 => 
                                    array (
                                        'id' => 372,
                                        'code' => '422',
                                        'description' => 'Airport Transfer Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    372 => 
                                    array (
                                        'id' => 373,
                                        'code' => '423',
                                        'description' => 'Clearance Non-FedEx Transportation',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    373 => 
                                    array (
                                        'id' => 374,
                                        'code' => '424',
                                        'description' => 'Transfer In Bond Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    374 => 
                                    array (
                                        'id' => 375,
                                        'code' => '425',
                                        'description' => 'Processing Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    375 => 
                                    array (
                                        'id' => 376,
                                        'code' => '426',
                                        'description' => 'BSO AWB Revalidation Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    376 => 
                                    array (
                                        'id' => 377,
                                        'code' => '427',
                                        'description' => 'Fax Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    377 => 
                                    array (
                                        'id' => 378,
                                        'code' => '428',
                                        'description' => 'Low Value Document Exception Handling Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    378 => 
                                    array (
                                        'id' => 379,
                                        'code' => '429',
                                        'description' => 'Prepayment Postal Transfer Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    379 => 
                                    array (
                                        'id' => 380,
                                        'code' => '430',
                                        'description' => 'Import Permit Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    380 => 
                                    array (
                                        'id' => 381,
                                        'code' => '431',
                                        'description' => 'Ministry of Agriculture Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    381 => 
                                    array (
                                        'id' => 382,
                                        'code' => '432',
                                        'description' => 'Other Gov’t Agency Charge Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    382 => 
                                    array (
                                        'id' => 383,
                                        'code' => '433',
                                        'description' => 'Quarantine Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    383 => 
                                    array (
                                        'id' => 384,
                                        'code' => '444',
                                        'description' => 'Cayman Island Stamp Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    384 => 
                                    array (
                                        'id' => 385,
                                        'code' => '446',
                                        'description' => 'Argentina Export Duty',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    385 => 
                                    array (
                                        'id' => 386,
                                        'code' => '904',
                                        'description' => 'Special Handling',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    386 => 
                                    array (
                                        'id' => 387,
                                        'code' => '687',
                                        'description' => 'Additional Handling Charge',
                                        'scs_code' => 'ADH',
                                        'carrier_id' => 2,
                                    ),
                                    387 => 
                                    array (
                                        'id' => 388,
                                        'code' => '901',
                                        'description' => 'On Demand Care',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    388 => 
                                    array (
                                        'id' => 389,
                                        'code' => 'RTN',
                                        'description' => 'Standard Undeliverable Return',
                                        'scs_code' => 'FRT',
                                        'carrier_id' => 3,
                                    ),
                                    389 => 
                                    array (
                                        'id' => 390,
                                        'code' => 'ADJ',
                                        'description' => 'Post Delivery Adjustment',
                                        'scs_code' => 'RES',
                                        'carrier_id' => 3,
                                    ),
                                    390 => 
                                    array (
                                        'id' => 391,
                                        'code' => 'LPS',
                                        'description' => 'Large Package Surcharge',
                                        'scs_code' => 'LPS',
                                        'carrier_id' => 3,
                                    ),
                                    391 => 
                                    array (
                                        'id' => 392,
                                        'code' => 'AHC',
                                        'description' => 'Additional Handling Charge',
                                        'scs_code' => 'ADH',
                                        'carrier_id' => 3,
                                    ),
                                    392 => 
                                    array (
                                        'id' => 393,
                                        'code' => 'F/D',
                                        'description' => 'Duty and Tax Forwarding Surcharge',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 3,
                                    ),
                                    393 => 
                                    array (
                                        'id' => 394,
                                        'code' => 'FSC',
                                        'description' => 'Fuel Surcharge',
                                        'scs_code' => 'FSC',
                                        'carrier_id' => 3,
                                    ),
                                    394 => 
                                    array (
                                        'id' => 395,
                                        'code' => 'SHP',
                                        'description' => 'Freight Charge',
                                        'scs_code' => 'FRT',
                                        'carrier_id' => 3,
                                    ),
                                    395 => 
                                    array (
                                        'id' => 396,
                                        'code' => 'MIS',
                                        'description' => 'Miscellaneous Charges',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    396 => 
                                    array (
                                        'id' => 397,
                                        'code' => 'DCS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    397 => 
                                    array (
                                        'id' => 398,
                                        'code' => 'ESD',
                                        'description' => 'Extended Area Surcharge',
                                        'scs_code' => 'OOA',
                                        'carrier_id' => 3,
                                    ),
                                    398 => 
                                    array (
                                        'id' => 399,
                                        'code' => 'SAT',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    399 => 
                                    array (
                                        'id' => 400,
                                        'code' => 'RES',
                                        'description' => 'Residential Surcharge',
                                        'scs_code' => 'RES',
                                        'carrier_id' => 3,
                                    ),
                                    400 => 
                                    array (
                                        'id' => 401,
                                        'code' => 'LDS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    401 => 
                                    array (
                                        'id' => 402,
                                        'code' => '482',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    402 => 
                                    array (
                                        'id' => 403,
                                        'code' => 'OVR',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    403 => 
                                    array (
                                        'id' => 404,
                                        'code' => '405',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    404 => 
                                    array (
                                        'id' => 405,
                                        'code' => '206',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    405 => 
                                    array (
                                        'id' => 406,
                                        'code' => 'OML',
                                        'description' => 'Over Max Length',
                                        'scs_code' => 'OSS',
                                        'carrier_id' => 3,
                                    ),
                                    406 => 
                                    array (
                                        'id' => 407,
                                        'code' => '482',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    407 => 
                                    array (
                                        'id' => 408,
                                        'code' => 'GLP',
                                        'description' => 'Late Payment Fee',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    408 => 
                                    array (
                                        'id' => 409,
                                        'code' => 'HIS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    409 => 
                                    array (
                                        'id' => 410,
                                        'code' => '826',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    410 => 
                                    array (
                                        'id' => 411,
                                        'code' => '412',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    411 => 
                                    array (
                                        'id' => 412,
                                        'code' => '513',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    412 => 
                                    array (
                                        'id' => 413,
                                        'code' => '512',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    413 => 
                                    array (
                                        'id' => 414,
                                        'code' => '410',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    414 => 
                                    array (
                                        'id' => 415,
                                        'code' => 'ART',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    415 => 
                                    array (
                                        'id' => 416,
                                        'code' => '979',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    416 => 
                                    array (
                                        'id' => 417,
                                        'code' => '426',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    417 => 
                                    array (
                                        'id' => 418,
                                        'code' => '214',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    418 => 
                                    array (
                                        'id' => 419,
                                        'code' => '688',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    419 => 
                                    array (
                                        'id' => 420,
                                        'code' => '892',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    420 => 
                                    array (
                                        'id' => 421,
                                        'code' => 'LSC',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    421 => 
                                    array (
                                        'id' => 422,
                                        'code' => 'CHB',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    422 => 
                                    array (
                                        'id' => 423,
                                        'code' => '420',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    423 => 
                                    array (
                                        'id' => 424,
                                        'code' => '231',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    424 => 
                                    array (
                                        'id' => 425,
                                        'code' => '212',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    425 => 
                                    array (
                                        'id' => 426,
                                        'code' => '462',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    426 => 
                                    array (
                                        'id' => 427,
                                        'code' => '447',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    427 => 
                                    array (
                                        'id' => 428,
                                        'code' => '515',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    428 => 
                                    array (
                                        'id' => 429,
                                        'code' => '824',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    429 => 
                                    array (
                                        'id' => 430,
                                        'code' => '495',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    430 => 
                                    array (
                                        'id' => 431,
                                        'code' => '822',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    431 => 
                                    array (
                                        'id' => 432,
                                        'code' => 'ADS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    432 => 
                                    array (
                                        'id' => 433,
                                        'code' => '900',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    433 => 
                                    array (
                                        'id' => 434,
                                        'code' => '202',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    434 => 
                                    array (
                                        'id' => 435,
                                        'code' => '514',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    435 => 
                                    array (
                                        'id' => 436,
                                        'code' => '516',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    436 => 
                                    array (
                                        'id' => 437,
                                        'code' => 'APF',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    437 => 
                                    array (
                                        'id' => 438,
                                        'code' => '695',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    438 => 
                                    array (
                                        'id' => 439,
                                        'code' => '912',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    439 => 
                                    array (
                                        'id' => 440,
                                        'code' => 'APG',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    440 => 
                                    array (
                                        'id' => 441,
                                        'code' => 'ICE',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    441 => 
                                    array (
                                        'id' => 442,
                                        'code' => '305',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    442 => 
                                    array (
                                        'id' => 443,
                                        'code' => '458',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    443 => 
                                    array (
                                        'id' => 444,
                                        'code' => 'AFP',
                                        'description' => 'Alternative Address Request',
                                        'scs_code' => 'AAR',
                                        'carrier_id' => 3,
                                    ),
                                    444 => 
                                    array (
                                        'id' => 445,
                                        'code' => 'ASP',
                                        'description' => 'Alternative address request',
                                        'scs_code' => 'AAR',
                                        'carrier_id' => 3,
                                    ),
                                    445 => 
                                    array (
                                        'id' => 446,
                                        'code' => 'ASW',
                                        'description' => 'Alternative address request',
                                        'scs_code' => 'AAR',
                                        'carrier_id' => 3,
                                    ),
                                    446 => 
                                    array (
                                        'id' => 447,
                                        'code' => 'OSP',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    447 => 
                                    array (
                                        'id' => 448,
                                        'code' => 'AFW',
                                        'description' => 'Alternative Address Request',
                                        'scs_code' => 'AAR',
                                        'carrier_id' => 3,
                                    ),
                                    448 => 
                                    array (
                                        'id' => 449,
                                        'code' => '611',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    449 => 
                                    array (
                                        'id' => 450,
                                        'code' => '606',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    450 => 
                                    array (
                                        'id' => 451,
                                        'code' => 'EVS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'DVF',
                                        'carrier_id' => 3,
                                    ),
                                    451 => 
                                    array (
                                        'id' => 452,
                                        'code' => '886',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    452 => 
                                    array (
                                        'id' => 453,
                                        'code' => 'BKT',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    453 => 
                                    array (
                                        'id' => 454,
                                        'code' => 'BKS',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    454 => 
                                    array (
                                        'id' => 455,
                                        'code' => 'FRT',
                                        'description' => 'FREIGHT CHARGE',
                                        'scs_code' => 'FRT',
                                        'carrier_id' => 5,
                                    ),
                                    455 => 
                                    array (
                                        'id' => 456,
                                        'code' => 'FF',
                                        'description' => 'FUEL SURCHARGE',
                                        'scs_code' => 'FSC',
                                        'carrier_id' => 5,
                                    ),
                                    456 => 
                                    array (
                                        'id' => 457,
                                        'code' => 'YB',
                                        'description' => 'OVER SIZED PIECE',
                                        'scs_code' => 'ADH',
                                        'carrier_id' => 5,
                                    ),
                                    457 => 
                                    array (
                                        'id' => 458,
                                        'code' => 'OO',
                                        'description' => 'REMOTE AREA DELIVERY',
                                        'scs_code' => 'OOA',
                                        'carrier_id' => 5,
                                    ),
                                    458 => 
                                    array (
                                        'id' => 459,
                                        'code' => 'MA',
                                        'description' => 'ADDRESS CORRECTION',
                                        'scs_code' => 'ADF',
                                        'carrier_id' => 5,
                                    ),
                                    459 => 
                                    array (
                                        'id' => 460,
                                        'code' => 'DD',
                                        'description' => 'DUTIES TAXES ADMIN FEE',
                                        'scs_code' => 'DDF',
                                        'carrier_id' => 5,
                                    ),
                                    460 => 
                                    array (
                                        'id' => 463,
                                        'code' => 'XB',
                                        'description' => 'IMPORT EXPORT TAXES',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    461 => 
                                    array (
                                        'id' => 464,
                                        'code' => 'XX',
                                        'description' => 'IMPORT EXPORT DUTIES',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    462 => 
                                    array (
                                        'id' => 465,
                                        'code' => 'VAT',
                                        'description' => 'FREIGHT VAT',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    463 => 
                                    array (
                                        'id' => 467,
                                        'code' => 'OT1',
                                        'description' => 'OTHER CHARGE 1',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    464 => 
                                    array (
                                        'id' => 469,
                                        'code' => 'OT2',
                                        'description' => 'OTHER CHARGE 2',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    465 => 
                                    array (
                                        'id' => 470,
                                        'code' => 'WF',
                                        'description' => 'POST CLEARANCE MODIFICATION',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    466 => 
                                    array (
                                        'id' => 471,
                                        'code' => '659',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    467 => 
                                    array (
                                        'id' => 472,
                                        'code' => 'BNY',
                                        'description' => 'Indian Tax charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    468 => 
                                    array (
                                        'id' => 473,
                                        'code' => 'BNX',
                                        'description' => 'Indian Tax charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    469 => 
                                    array (
                                        'id' => 474,
                                        'code' => 'BSH',
                                        'description' => 'Indian Tax charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    470 => 
                                    array (
                                        'id' => 475,
                                        'code' => 'BSI',
                                        'description' => 'Indian Tax charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    471 => 
                                    array (
                                        'id' => 476,
                                        'code' => 'BRY',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    472 => 
                                    array (
                                        'id' => 477,
                                        'code' => 'BRZ',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    473 => 
                                    array (
                                        'id' => 478,
                                        'code' => 'BOA',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    474 => 
                                    array (
                                        'id' => 479,
                                        'code' => 'BOB',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    475 => 
                                    array (
                                        'id' => 480,
                                        'code' => 'BQA',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    476 => 
                                    array (
                                        'id' => 481,
                                        'code' => 'BPZ',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    477 => 
                                    array (
                                        'id' => 482,
                                        'code' => 'BSK',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    478 => 
                                    array (
                                        'id' => 483,
                                        'code' => 'BSL',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    479 => 
                                    array (
                                        'id' => 484,
                                        'code' => 'ERL',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    480 => 
                                    array (
                                        'id' => 485,
                                        'code' => 'BPO',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    481 => 
                                    array (
                                        'id' => 486,
                                        'code' => 'BTO',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    482 => 
                                    array (
                                        'id' => 487,
                                        'code' => 'BTP',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    483 => 
                                    array (
                                        'id' => 488,
                                        'code' => 'YY',
                                        'description' => 'OVER WEIGHT PIECE',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    484 => 
                                    array (
                                        'id' => 489,
                                        'code' => 'BWL',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    485 => 
                                    array (
                                        'id' => 490,
                                        'code' => 'BWM',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    486 => 
                                    array (
                                        'id' => 491,
                                        'code' => '828',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 2,
                                    ),
                                    487 => 
                                    array (
                                        'id' => 492,
                                        'code' => 'XJ',
                                        'description' => 'TRADE ZONE PROCESS',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    488 => 
                                    array (
                                        'id' => 493,
                                        'code' => 'WP',
                                        'description' => 'EXPORTER VALIDATION',
                                        'scs_code' => 'EVF',
                                        'carrier_id' => 5,
                                    ),
                                    489 => 
                                    array (
                                        'id' => 494,
                                        'code' => 'CB',
                                        'description' => 'RESTRICTED DESTINATION',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                    490 => 
                                    array (
                                        'id' => 495,
                                        'code' => 'XE',
                                        'description' => 'MERCHANDISE PROCESS',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    491 => 
                                    array (
                                        'id' => 496,
                                        'code' => 'WK',
                                        'description' => 'BONDED STORAGE',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    492 => 
                                    array (
                                        'id' => 497,
                                        'code' => 'XA',
                                        'description' => 'ADDITIONAL DUTY',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 5,
                                    ),
                                    493 => 
                                    array (
                                        'id' => 498,
                                        'code' => 'SVC',
                                        'description' => 'Unknown',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 3,
                                    ),
                                    494 => 
                                    array (
                                        'id' => 499,
                                        'code' => 'BXJ',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    495 => 
                                    array (
                                        'id' => 500,
                                        'code' => 'BXK',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    496 => 
                                    array (
                                        'id' => 501,
                                        'code' => 'BWY',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    497 => 
                                    array (
                                        'id' => 502,
                                        'code' => 'BWX',
                                        'description' => 'Indian Tax Charges',
                                        'scs_code' => 'CDV',
                                        'carrier_id' => 2,
                                    ),
                                    498 => 
                                    array (
                                        'id' => 503,
                                        'code' => 'WB',
                                        'description' => 'DUTIES TAXES ADMIN FEE',
                                        'scs_code' => 'DDF',
                                        'carrier_id' => 5,
                                    ),
                                    499 => 
                                    array (
                                        'id' => 504,
                                        'code' => 'OB',
                                        'description' => 'REMOTE AREA PICKUP',
                                        'scs_code' => 'MIS',
                                        'carrier_id' => 5,
                                    ),
                                ));
        \DB::table('carrier_charge_codes')->insert(array (
            0 => 
            array (
                'id' => 505,
                'code' => 'XK',
                'description' => 'Regulatory Charges',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            1 => 
            array (
                'id' => 506,
                'code' => 'OSW',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 3,
            ),
            2 => 
            array (
                'id' => 507,
                'code' => 'OFW',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 3,
            ),
            3 => 
            array (
                'id' => 508,
                'code' => '977',
                'description' => 'Zambia VAT - Freight',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            4 => 
            array (
                'id' => 509,
                'code' => 'FRT',
                'description' => 'FREIGHT CHARGE',
                'scs_code' => 'FRT',
                'carrier_id' => 12,
            ),
            5 => 
            array (
                'id' => 510,
                'code' => 'PD',
                'description' => 'DATA ENTRY',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            6 => 
            array (
                'id' => 511,
                'code' => 'YC',
                'description' => 'NON STACKABLE PALLET',
                'scs_code' => 'ADH',
                'carrier_id' => 5,
            ),
            7 => 
            array (
                'id' => 512,
                'code' => '631',
                'description' => 'VAT on CBS fee TR',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            8 => 
            array (
                'id' => 513,
                'code' => 'ALP',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 3,
            ),
            9 => 
            array (
                'id' => 514,
                'code' => 'CA',
                'description' => 'ELEVATED RISK',
                'scs_code' => 'ERF',
                'carrier_id' => 5,
            ),
            10 => 
            array (
                'id' => 515,
                'code' => '610',
                'description' => 'Unknown',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            11 => 
            array (
                'id' => 516,
                'code' => 'AA',
                'description' => 'SATURDAY DELIVERY',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            12 => 
            array (
                'id' => 517,
                'code' => 'WO',
                'description' => 'EXPORT DECLARATION',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            13 => 
            array (
                'id' => 518,
                'code' => 'II',
                'description' => 'INSURANCE',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            14 => 
            array (
                'id' => 519,
                'code' => 'YK',
                'description' => 'PREMIUM 12:00',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            15 => 
            array (
                'id' => 520,
                'code' => 'SAH',
                'description' => 'Seasonal Additional Handling',
                'scs_code' => 'ADH',
                'carrier_id' => 3,
            ),
            16 => 
            array (
                'id' => 521,
                'code' => 'SLP',
                'description' => 'Seasonal Large Package Surcharge',
                'scs_code' => 'LPS',
                'carrier_id' => 3,
            ),
            17 => 
            array (
                'id' => 522,
                'code' => 'OFP',
                'description' => 'Alternative Address Request',
                'scs_code' => 'AAR',
                'carrier_id' => 3,
            ),
            18 => 
            array (
                'id' => 523,
                'code' => 'SOV',
                'description' => 'Over Max Length',
                'scs_code' => 'OSS',
                'carrier_id' => 3,
            ),
            19 => 
            array (
                'id' => 524,
                'code' => 'BSB',
                'description' => 'Unknown',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            20 => 
            array (
                'id' => 525,
                'code' => 'BSC',
                'description' => 'Unknown',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            21 => 
            array (
                'id' => 526,
                'code' => 'WE',
                'description' => 'MULTILINE ENTRY',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            22 => 
            array (
                'id' => 527,
                'code' => 'QA',
                'description' => 'DEDICATED PICKUP',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            23 => 
            array (
                'id' => 528,
                'code' => 'BTL',
                'description' => 'India Vat',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            24 => 
            array (
                'id' => 529,
                'code' => 'BTM',
                'description' => 'India Vat',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            25 => 
            array (
                'id' => 530,
                'code' => 'BRW',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            26 => 
            array (
                'id' => 531,
                'code' => 'BRV',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            27 => 
            array (
                'id' => 532,
                'code' => 'YI',
                'description' => 'PREMIUM 9:00',
                'scs_code' => 'MIS',
                'carrier_id' => 5,
            ),
            28 => 
            array (
                'id' => 533,
                'code' => 'BOG',
                'description' => 'Unknown',
                'scs_code' => 'MIS',
                'carrier_id' => 2,
            ),
            29 => 
            array (
                'id' => 534,
                'code' => 'FRT',
                'description' => 'Freight Charge',
                'scs_code' => 'FRT',
                'carrier_id' => 4,
            ),
            30 => 
            array (
                'id' => 535,
                'code' => 'FSC',
                'description' => 'Fuel Surcharge',
                'scs_code' => 'FSC',
                'carrier_id' => 4,
            ),
            31 => 
            array (
                'id' => 536,
                'code' => 'CDV',
                'description' => 'Great Britain VAT',
                'scs_code' => 'CDV',
                'carrier_id' => 4,
            ),
            32 => 
            array (
                'id' => 537,
                'code' => 'MIS',
                'description' => 'Additional Charge',
                'scs_code' => 'MIS',
                'carrier_id' => 4,
            ),
            33 => 
            array (
                'id' => 538,
                'code' => 'IB',
                'description' => 'EXTENDED LIABILITY',
                'scs_code' => 'DVF',
                'carrier_id' => 5,
            ),
            34 => 
            array (
                'id' => 539,
                'code' => 'INS',
                'description' => 'Insurance',
                'scs_code' => 'INS',
                'carrier_id' => 4,
            ),
            35 => 
            array (
                'id' => 540,
                'code' => 'RES',
                'description' => 'Residential Surcharge',
                'scs_code' => 'RES',
                'carrier_id' => 4,
            ),
            36 => 
            array (
                'id' => 541,
                'code' => 'ADH',
                'description' => 'Special Handling Charge',
                'scs_code' => 'ADH',
                'carrier_id' => 4,
            ),
            37 => 
            array (
                'id' => 542,
                'code' => 'CVN',
                'description' => 'Merchandising processing fee',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            38 => 
            array (
                'id' => 543,
                'code' => '168',
                'description' => 'BS VAT on Adv & Anc Svc Fees',
                'scs_code' => 'CDV',
                'carrier_id' => 2,
            ),
            39 => 
            array (
                'id' => 544,
                'code' => 'FRT',
                'description' => 'FREIGHT CHARGE',
                'scs_code' => 'FRT',
                'carrier_id' => 14,
            ),
            40 => 
            array (
                'id' => 545,
                'code' => 'FSC',
                'description' => 'FUEL SURCHARGE',
                'scs_code' => 'FSC',
                'carrier_id' => 14,
            ),
        ));
        
        
    }
}