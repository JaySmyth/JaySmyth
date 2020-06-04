<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('states')->delete();
        
        \DB::table('states')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'AL',
                'alpha_code' => 'ALABAMA',
                'name' => 'Alabama',
                'country_code' => 'US',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'AR',
                'alpha_code' => 'ARKANSAS',
                'name' => 'Arkansas',
                'country_code' => 'US',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'CT',
                'alpha_code' => 'CONNECTICUT',
                'name' => 'Connecticut',
                'country_code' => 'US',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'FL',
                'alpha_code' => 'FLORIDA',
                'name' => 'Florida',
                'country_code' => 'US',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'ID',
                'alpha_code' => 'IDAHO',
                'name' => 'Idaho',
                'country_code' => 'US',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'IA',
                'alpha_code' => 'IOWA',
                'name' => 'Iowa',
                'country_code' => 'US',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'LA',
                'alpha_code' => 'LOUISIANA',
                'name' => 'Louisiana',
                'country_code' => 'US',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'MA',
                'alpha_code' => 'MASSACHUSETTS',
                'name' => 'Massachusetts',
                'country_code' => 'US',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'MS',
                'alpha_code' => 'MISSISSIPPI',
                'name' => 'Mississippi',
                'country_code' => 'US',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'NE',
                'alpha_code' => 'NEBRASKA',
                'name' => 'Nebraska',
                'country_code' => 'US',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'NJ',
                'alpha_code' => 'NEWJERSEY',
                'name' => 'New Jersey',
                'country_code' => 'US',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'NC',
                'alpha_code' => 'NORTHCAROLINA',
                'name' => 'North Carolina',
                'country_code' => 'US',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'OK',
                'alpha_code' => 'OKLAHOMA',
                'name' => 'Oklahoma',
                'country_code' => 'US',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'RI',
                'alpha_code' => 'RHODEISLAND',
                'name' => 'Rhode Island',
                'country_code' => 'US',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'TN',
                'alpha_code' => 'TENNESSEE',
                'name' => 'Tennessee',
                'country_code' => 'US',
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'VT',
                'alpha_code' => 'VERMONT',
                'name' => 'Vermont',
                'country_code' => 'US',
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'WV',
                'alpha_code' => 'WESTVIRGINIA',
                'name' => 'West Virginia',
                'country_code' => 'US',
            ),
            17 => 
            array (
                'id' => 18,
                'code' => 'AK',
                'alpha_code' => 'ALASKA',
                'name' => 'Alaska',
                'country_code' => 'US',
            ),
            18 => 
            array (
                'id' => 19,
                'code' => 'CA',
                'alpha_code' => 'CALIFORNIA',
                'name' => 'California',
                'country_code' => 'US',
            ),
            19 => 
            array (
                'id' => 20,
                'code' => 'DC',
                'alpha_code' => 'DISTCOLUMBIA',
                'name' => 'Dist. Columbia',
                'country_code' => 'US',
            ),
            20 => 
            array (
                'id' => 21,
                'code' => 'GA',
                'alpha_code' => 'GEORGIA',
                'name' => 'Georgia',
                'country_code' => 'US',
            ),
            21 => 
            array (
                'id' => 22,
                'code' => 'IL',
                'alpha_code' => 'ILLINOIS',
                'name' => 'Illinois',
                'country_code' => 'US',
            ),
            22 => 
            array (
                'id' => 23,
                'code' => 'KS',
                'alpha_code' => 'KANSAS',
                'name' => 'Kansas',
                'country_code' => 'US',
            ),
            23 => 
            array (
                'id' => 24,
                'code' => 'ME',
                'alpha_code' => 'MAINE',
                'name' => 'Maine',
                'country_code' => 'US',
            ),
            24 => 
            array (
                'id' => 25,
                'code' => 'MI',
                'alpha_code' => 'MICHIGAN',
                'name' => 'Michigan',
                'country_code' => 'US',
            ),
            25 => 
            array (
                'id' => 26,
                'code' => 'MO',
                'alpha_code' => 'MISSOURI',
                'name' => 'Missouri',
                'country_code' => 'US',
            ),
            26 => 
            array (
                'id' => 27,
                'code' => 'NV',
                'alpha_code' => 'NEVADA',
                'name' => 'Nevada',
                'country_code' => 'US',
            ),
            27 => 
            array (
                'id' => 28,
                'code' => 'NM',
                'alpha_code' => 'NEWMEXICO',
                'name' => 'New Mexico',
                'country_code' => 'US',
            ),
            28 => 
            array (
                'id' => 29,
                'code' => 'ND',
                'alpha_code' => 'NORTHDAKOTA',
                'name' => 'North Dakota',
                'country_code' => 'US',
            ),
            29 => 
            array (
                'id' => 30,
                'code' => 'OR',
                'alpha_code' => 'OREGON',
                'name' => 'Oregon',
                'country_code' => 'US',
            ),
            30 => 
            array (
                'id' => 31,
                'code' => 'SC',
                'alpha_code' => 'SOUTHCAROLINA',
                'name' => 'South Carolina',
                'country_code' => 'US',
            ),
            31 => 
            array (
                'id' => 32,
                'code' => 'TX',
                'alpha_code' => 'TEXAS',
                'name' => 'Texas',
                'country_code' => 'US',
            ),
            32 => 
            array (
                'id' => 33,
                'code' => 'VA',
                'alpha_code' => 'VIRGINIA',
                'name' => 'Virginia',
                'country_code' => 'US',
            ),
            33 => 
            array (
                'id' => 34,
                'code' => 'WI',
                'alpha_code' => 'WISCONSIN',
                'name' => 'Wisconsin',
                'country_code' => 'US',
            ),
            34 => 
            array (
                'id' => 35,
                'code' => 'AZ',
                'alpha_code' => 'ARIZONA',
                'name' => 'Arizona',
                'country_code' => 'US',
            ),
            35 => 
            array (
                'id' => 36,
                'code' => 'CO',
                'alpha_code' => 'COLORADO',
                'name' => 'Colorado',
                'country_code' => 'US',
            ),
            36 => 
            array (
                'id' => 37,
                'code' => 'DE',
                'alpha_code' => 'DELAWARE',
                'name' => 'Delaware',
                'country_code' => 'US',
            ),
            37 => 
            array (
                'id' => 38,
                'code' => 'HI',
                'alpha_code' => 'HAWAII',
                'name' => 'Hawaii',
                'country_code' => 'US',
            ),
            38 => 
            array (
                'id' => 39,
                'code' => 'IN',
                'alpha_code' => 'INDIANA',
                'name' => 'Indiana',
                'country_code' => 'US',
            ),
            39 => 
            array (
                'id' => 40,
                'code' => 'KY',
                'alpha_code' => 'KENTUCKY',
                'name' => 'Kentucky',
                'country_code' => 'US',
            ),
            40 => 
            array (
                'id' => 41,
                'code' => 'MD',
                'alpha_code' => 'MARYLAND',
                'name' => 'Maryland',
                'country_code' => 'US',
            ),
            41 => 
            array (
                'id' => 42,
                'code' => 'MN',
                'alpha_code' => 'MINNESOTA',
                'name' => 'Minnesota',
                'country_code' => 'US',
            ),
            42 => 
            array (
                'id' => 43,
                'code' => 'MT',
                'alpha_code' => 'MONTANA',
                'name' => 'Montana',
                'country_code' => 'US',
            ),
            43 => 
            array (
                'id' => 44,
                'code' => 'NH',
                'alpha_code' => 'NEWHAMPSHIRE',
                'name' => 'New Hampshire',
                'country_code' => 'US',
            ),
            44 => 
            array (
                'id' => 45,
                'code' => 'NY',
                'alpha_code' => 'NEWYORK',
                'name' => 'New York',
                'country_code' => 'US',
            ),
            45 => 
            array (
                'id' => 46,
                'code' => 'OH',
                'alpha_code' => 'OHIO',
                'name' => 'Ohio',
                'country_code' => 'US',
            ),
            46 => 
            array (
                'id' => 47,
                'code' => 'PA',
                'alpha_code' => 'PENNSYLVANIA',
                'name' => 'Pennsylvania',
                'country_code' => 'US',
            ),
            47 => 
            array (
                'id' => 48,
                'code' => 'SD',
                'alpha_code' => 'SOUTHDAKOTA',
                'name' => 'South Dakota',
                'country_code' => 'US',
            ),
            48 => 
            array (
                'id' => 49,
                'code' => 'UT',
                'alpha_code' => 'UTAH',
                'name' => 'Utah',
                'country_code' => 'US',
            ),
            49 => 
            array (
                'id' => 50,
                'code' => 'WA',
                'alpha_code' => 'WASHINGTON',
                'name' => 'Washington',
                'country_code' => 'US',
            ),
            50 => 
            array (
                'id' => 51,
                'code' => 'WY',
                'alpha_code' => 'WYOMING',
                'name' => 'Wyoming',
                'country_code' => 'US',
            ),
            51 => 
            array (
                'id' => 52,
                'code' => 'AB',
                'alpha_code' => 'ALBERTA',
                'name' => 'Alberta',
                'country_code' => 'CA',
            ),
            52 => 
            array (
                'id' => 53,
                'code' => 'LB',
                'alpha_code' => 'LABRADOR',
                'name' => 'Labrador',
                'country_code' => 'CA',
            ),
            53 => 
            array (
                'id' => 54,
                'code' => 'NB',
                'alpha_code' => 'NEWBRUNSWICK',
                'name' => 'New Brunswick',
                'country_code' => 'CA',
            ),
            54 => 
            array (
                'id' => 55,
                'code' => 'NS',
                'alpha_code' => 'NOVASCOTIA',
                'name' => 'Nova Scotia',
                'country_code' => 'CA',
            ),
            55 => 
            array (
                'id' => 56,
                'code' => 'NW',
                'alpha_code' => 'NORTHWESTTERR',
                'name' => 'North West Terr.',
                'country_code' => 'CA',
            ),
            56 => 
            array (
                'id' => 57,
                'code' => 'PE',
                'alpha_code' => 'PRINCEEDWARDIS',
                'name' => 'Prince Edward Is.',
                'country_code' => 'CA',
            ),
            57 => 
            array (
                'id' => 58,
                'code' => 'SK',
                'alpha_code' => 'SASKATCHEWEN',
                'name' => 'Saskatchewen',
                'country_code' => 'CA',
            ),
            58 => 
            array (
                'id' => 59,
                'code' => 'BC',
                'alpha_code' => 'BRITISHCOLUMBIA',
                'name' => 'British Columbia',
                'country_code' => 'CA',
            ),
            59 => 
            array (
                'id' => 60,
                'code' => 'MB',
                'alpha_code' => 'MANITOBA',
                'name' => 'Manitoba',
                'country_code' => 'CA',
            ),
            60 => 
            array (
                'id' => 61,
                'code' => 'NF',
                'alpha_code' => 'NEWFOUNDLAND',
                'name' => 'Newfoundland',
                'country_code' => 'CA',
            ),
            61 => 
            array (
                'id' => 62,
                'code' => 'NU',
                'alpha_code' => 'NUNAVUT',
                'name' => 'Nunavut',
                'country_code' => 'CA',
            ),
            62 => 
            array (
                'id' => 63,
                'code' => 'ON',
                'alpha_code' => 'ONTARIO',
                'name' => 'Ontario',
                'country_code' => 'CA',
            ),
            63 => 
            array (
                'id' => 64,
                'code' => 'QC',
                'alpha_code' => 'QUEBEC',
                'name' => 'Quebec',
                'country_code' => 'CA',
            ),
            64 => 
            array (
                'id' => 65,
                'code' => 'YU',
                'alpha_code' => 'YUKON',
                'name' => 'Yukon',
                'country_code' => 'CA',
            ),
            65 => 
            array (
                'id' => 66,
                'code' => 'PR',
                'alpha_code' => 'PUERTORICO',
                'name' => 'Puerto Rico',
                'country_code' => 'PR',
            ),
            66 => 
            array (
                'id' => 67,
                'code' => 'AP',
                'alpha_code' => 'ARMEDPACIFIC',
                'name' => 'Armed Forces Pacific',
                'country_code' => 'US',
            ),
        ));
        
        
    }
}