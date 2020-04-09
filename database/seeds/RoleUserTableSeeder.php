<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'role_id' => 9,
                'user_id' => 1,
            ),
            1 => 
            array (
                'role_id' => 10,
                'user_id' => 1,
            ),
            2 => 
            array (
                'role_id' => 13,
                'user_id' => 1,
            ),
            3 => 
            array (
                'role_id' => 4,
                'user_id' => 2,
            ),
            4 => 
            array (
                'role_id' => 4,
                'user_id' => 3,
            ),
            5 => 
            array (
                'role_id' => 10,
                'user_id' => 3,
            ),
            6 => 
            array (
                'role_id' => 10,
                'user_id' => 11,
            ),
            7 => 
            array (
                'role_id' => 16,
                'user_id' => 11,
            ),
            8 => 
            array (
                'role_id' => 5,
                'user_id' => 46,
            ),
            9 => 
            array (
                'role_id' => 10,
                'user_id' => 46,
            ),
            10 => 
            array (
                'role_id' => 13,
                'user_id' => 46,
            ),
            11 => 
            array (
                'role_id' => 5,
                'user_id' => 50,
            ),
            12 => 
            array (
                'role_id' => 10,
                'user_id' => 50,
            ),
            13 => 
            array (
                'role_id' => 9,
                'user_id' => 81,
            ),
            14 => 
            array (
                'role_id' => 10,
                'user_id' => 81,
            ),
            15 => 
            array (
                'role_id' => 9,
                'user_id' => 85,
            ),
            16 => 
            array (
                'role_id' => 10,
                'user_id' => 116,
            ),
            17 => 
            array (
                'role_id' => 16,
                'user_id' => 116,
            ),
            18 => 
            array (
                'role_id' => 8,
                'user_id' => 117,
            ),
            19 => 
            array (
                'role_id' => 4,
                'user_id' => 118,
            ),
            20 => 
            array (
                'role_id' => 10,
                'user_id' => 123,
            ),
            21 => 
            array (
                'role_id' => 15,
                'user_id' => 123,
            ),
            22 => 
            array (
                'role_id' => 4,
                'user_id' => 125,
            ),
            23 => 
            array (
                'role_id' => 5,
                'user_id' => 128,
            ),
            24 => 
            array (
                'role_id' => 10,
                'user_id' => 128,
            ),
            25 => 
            array (
                'role_id' => 9,
                'user_id' => 129,
            ),
            26 => 
            array (
                'role_id' => 10,
                'user_id' => 129,
            ),
            27 => 
            array (
                'role_id' => 7,
                'user_id' => 133,
            ),
            28 => 
            array (
                'role_id' => 10,
                'user_id' => 133,
            ),
            29 => 
            array (
                'role_id' => 10,
                'user_id' => 137,
            ),
            30 => 
            array (
                'role_id' => 16,
                'user_id' => 137,
            ),
            31 => 
            array (
                'role_id' => 9,
                'user_id' => 140,
            ),
            32 => 
            array (
                'role_id' => 10,
                'user_id' => 140,
            ),
            33 => 
            array (
                'role_id' => 5,
                'user_id' => 168,
            ),
            34 => 
            array (
                'role_id' => 10,
                'user_id' => 168,
            ),
            35 => 
            array (
                'role_id' => 5,
                'user_id' => 176,
            ),
            36 => 
            array (
                'role_id' => 10,
                'user_id' => 176,
            ),
            37 => 
            array (
                'role_id' => 10,
                'user_id' => 180,
            ),
            38 => 
            array (
                'role_id' => 15,
                'user_id' => 180,
            ),
            39 => 
            array (
                'role_id' => 6,
                'user_id' => 182,
            ),
            40 => 
            array (
                'role_id' => 10,
                'user_id' => 182,
            ),
            41 => 
            array (
                'role_id' => 5,
                'user_id' => 183,
            ),
            42 => 
            array (
                'role_id' => 10,
                'user_id' => 183,
            ),
            43 => 
            array (
                'role_id' => 1,
                'user_id' => 193,
            ),
            44 => 
            array (
                'role_id' => 10,
                'user_id' => 193,
            ),
            45 => 
            array (
                'role_id' => 9,
                'user_id' => 195,
            ),
            46 => 
            array (
                'role_id' => 10,
                'user_id' => 195,
            ),
            47 => 
            array (
                'role_id' => 6,
                'user_id' => 196,
            ),
            48 => 
            array (
                'role_id' => 10,
                'user_id' => 196,
            ),
            49 => 
            array (
                'role_id' => 10,
                'user_id' => 198,
            ),
            50 => 
            array (
                'role_id' => 13,
                'user_id' => 198,
            ),
            51 => 
            array (
                'role_id' => 17,
                'user_id' => 198,
            ),
            52 => 
            array (
                'role_id' => 9,
                'user_id' => 226,
            ),
            53 => 
            array (
                'role_id' => 10,
                'user_id' => 226,
            ),
            54 => 
            array (
                'role_id' => 1,
                'user_id' => 237,
            ),
            55 => 
            array (
                'role_id' => 10,
                'user_id' => 237,
            ),
            56 => 
            array (
                'role_id' => 1,
                'user_id' => 259,
            ),
            57 => 
            array (
                'role_id' => 10,
                'user_id' => 259,
            ),
            58 => 
            array (
                'role_id' => 1,
                'user_id' => 270,
            ),
            59 => 
            array (
                'role_id' => 10,
                'user_id' => 270,
            ),
            60 => 
            array (
                'role_id' => 2,
                'user_id' => 272,
            ),
            61 => 
            array (
                'role_id' => 10,
                'user_id' => 272,
            ),
            62 => 
            array (
                'role_id' => 1,
                'user_id' => 273,
            ),
            63 => 
            array (
                'role_id' => 10,
                'user_id' => 273,
            ),
            64 => 
            array (
                'role_id' => 1,
                'user_id' => 275,
            ),
            65 => 
            array (
                'role_id' => 10,
                'user_id' => 275,
            ),
            66 => 
            array (
                'role_id' => 1,
                'user_id' => 277,
            ),
            67 => 
            array (
                'role_id' => 10,
                'user_id' => 283,
            ),
            68 => 
            array (
                'role_id' => 15,
                'user_id' => 283,
            ),
            69 => 
            array (
                'role_id' => 1,
                'user_id' => 296,
            ),
            70 => 
            array (
                'role_id' => 10,
                'user_id' => 296,
            ),
            71 => 
            array (
                'role_id' => 1,
                'user_id' => 299,
            ),
            72 => 
            array (
                'role_id' => 10,
                'user_id' => 299,
            ),
            73 => 
            array (
                'role_id' => 1,
                'user_id' => 300,
            ),
            74 => 
            array (
                'role_id' => 10,
                'user_id' => 300,
            ),
            75 => 
            array (
                'role_id' => 1,
                'user_id' => 320,
            ),
            76 => 
            array (
                'role_id' => 1,
                'user_id' => 321,
            ),
            77 => 
            array (
                'role_id' => 10,
                'user_id' => 321,
            ),
            78 => 
            array (
                'role_id' => 1,
                'user_id' => 324,
            ),
            79 => 
            array (
                'role_id' => 10,
                'user_id' => 324,
            ),
            80 => 
            array (
                'role_id' => 2,
                'user_id' => 327,
            ),
            81 => 
            array (
                'role_id' => 10,
                'user_id' => 327,
            ),
            82 => 
            array (
                'role_id' => 1,
                'user_id' => 337,
            ),
            83 => 
            array (
                'role_id' => 10,
                'user_id' => 337,
            ),
            84 => 
            array (
                'role_id' => 1,
                'user_id' => 338,
            ),
            85 => 
            array (
                'role_id' => 10,
                'user_id' => 338,
            ),
            86 => 
            array (
                'role_id' => 1,
                'user_id' => 340,
            ),
            87 => 
            array (
                'role_id' => 10,
                'user_id' => 340,
            ),
            88 => 
            array (
                'role_id' => 1,
                'user_id' => 346,
            ),
            89 => 
            array (
                'role_id' => 2,
                'user_id' => 351,
            ),
            90 => 
            array (
                'role_id' => 10,
                'user_id' => 351,
            ),
            91 => 
            array (
                'role_id' => 1,
                'user_id' => 359,
            ),
            92 => 
            array (
                'role_id' => 10,
                'user_id' => 359,
            ),
            93 => 
            array (
                'role_id' => 1,
                'user_id' => 366,
            ),
            94 => 
            array (
                'role_id' => 10,
                'user_id' => 366,
            ),
            95 => 
            array (
                'role_id' => 1,
                'user_id' => 367,
            ),
            96 => 
            array (
                'role_id' => 1,
                'user_id' => 378,
            ),
            97 => 
            array (
                'role_id' => 10,
                'user_id' => 378,
            ),
            98 => 
            array (
                'role_id' => 1,
                'user_id' => 379,
            ),
            99 => 
            array (
                'role_id' => 10,
                'user_id' => 379,
            ),
            100 => 
            array (
                'role_id' => 1,
                'user_id' => 381,
            ),
            101 => 
            array (
                'role_id' => 10,
                'user_id' => 381,
            ),
            102 => 
            array (
                'role_id' => 2,
                'user_id' => 383,
            ),
            103 => 
            array (
                'role_id' => 10,
                'user_id' => 383,
            ),
            104 => 
            array (
                'role_id' => 1,
                'user_id' => 389,
            ),
            105 => 
            array (
                'role_id' => 10,
                'user_id' => 389,
            ),
            106 => 
            array (
                'role_id' => 1,
                'user_id' => 392,
            ),
            107 => 
            array (
                'role_id' => 10,
                'user_id' => 392,
            ),
            108 => 
            array (
                'role_id' => 1,
                'user_id' => 395,
            ),
            109 => 
            array (
                'role_id' => 10,
                'user_id' => 395,
            ),
            110 => 
            array (
                'role_id' => 1,
                'user_id' => 400,
            ),
            111 => 
            array (
                'role_id' => 10,
                'user_id' => 400,
            ),
            112 => 
            array (
                'role_id' => 1,
                'user_id' => 408,
            ),
            113 => 
            array (
                'role_id' => 10,
                'user_id' => 408,
            ),
            114 => 
            array (
                'role_id' => 1,
                'user_id' => 414,
            ),
            115 => 
            array (
                'role_id' => 10,
                'user_id' => 414,
            ),
            116 => 
            array (
                'role_id' => 1,
                'user_id' => 421,
            ),
            117 => 
            array (
                'role_id' => 10,
                'user_id' => 421,
            ),
            118 => 
            array (
                'role_id' => 2,
                'user_id' => 428,
            ),
            119 => 
            array (
                'role_id' => 10,
                'user_id' => 428,
            ),
            120 => 
            array (
                'role_id' => 1,
                'user_id' => 433,
            ),
            121 => 
            array (
                'role_id' => 10,
                'user_id' => 433,
            ),
            122 => 
            array (
                'role_id' => 1,
                'user_id' => 435,
            ),
            123 => 
            array (
                'role_id' => 10,
                'user_id' => 435,
            ),
            124 => 
            array (
                'role_id' => 1,
                'user_id' => 437,
            ),
            125 => 
            array (
                'role_id' => 10,
                'user_id' => 437,
            ),
            126 => 
            array (
                'role_id' => 1,
                'user_id' => 441,
            ),
            127 => 
            array (
                'role_id' => 10,
                'user_id' => 441,
            ),
            128 => 
            array (
                'role_id' => 1,
                'user_id' => 443,
            ),
            129 => 
            array (
                'role_id' => 10,
                'user_id' => 443,
            ),
            130 => 
            array (
                'role_id' => 1,
                'user_id' => 447,
            ),
            131 => 
            array (
                'role_id' => 1,
                'user_id' => 453,
            ),
            132 => 
            array (
                'role_id' => 10,
                'user_id' => 453,
            ),
            133 => 
            array (
                'role_id' => 1,
                'user_id' => 454,
            ),
            134 => 
            array (
                'role_id' => 10,
                'user_id' => 454,
            ),
            135 => 
            array (
                'role_id' => 1,
                'user_id' => 457,
            ),
            136 => 
            array (
                'role_id' => 10,
                'user_id' => 457,
            ),
            137 => 
            array (
                'role_id' => 1,
                'user_id' => 464,
            ),
            138 => 
            array (
                'role_id' => 10,
                'user_id' => 464,
            ),
            139 => 
            array (
                'role_id' => 1,
                'user_id' => 471,
            ),
            140 => 
            array (
                'role_id' => 10,
                'user_id' => 471,
            ),
            141 => 
            array (
                'role_id' => 1,
                'user_id' => 478,
            ),
            142 => 
            array (
                'role_id' => 10,
                'user_id' => 478,
            ),
            143 => 
            array (
                'role_id' => 1,
                'user_id' => 479,
            ),
            144 => 
            array (
                'role_id' => 10,
                'user_id' => 479,
            ),
            145 => 
            array (
                'role_id' => 1,
                'user_id' => 480,
            ),
            146 => 
            array (
                'role_id' => 10,
                'user_id' => 480,
            ),
            147 => 
            array (
                'role_id' => 1,
                'user_id' => 485,
            ),
            148 => 
            array (
                'role_id' => 10,
                'user_id' => 485,
            ),
            149 => 
            array (
                'role_id' => 1,
                'user_id' => 486,
            ),
            150 => 
            array (
                'role_id' => 10,
                'user_id' => 486,
            ),
            151 => 
            array (
                'role_id' => 1,
                'user_id' => 509,
            ),
            152 => 
            array (
                'role_id' => 10,
                'user_id' => 509,
            ),
            153 => 
            array (
                'role_id' => 2,
                'user_id' => 514,
            ),
            154 => 
            array (
                'role_id' => 10,
                'user_id' => 514,
            ),
            155 => 
            array (
                'role_id' => 2,
                'user_id' => 515,
            ),
            156 => 
            array (
                'role_id' => 10,
                'user_id' => 515,
            ),
            157 => 
            array (
                'role_id' => 1,
                'user_id' => 524,
            ),
            158 => 
            array (
                'role_id' => 10,
                'user_id' => 524,
            ),
            159 => 
            array (
                'role_id' => 1,
                'user_id' => 527,
            ),
            160 => 
            array (
                'role_id' => 10,
                'user_id' => 527,
            ),
            161 => 
            array (
                'role_id' => 4,
                'user_id' => 528,
            ),
            162 => 
            array (
                'role_id' => 1,
                'user_id' => 539,
            ),
            163 => 
            array (
                'role_id' => 10,
                'user_id' => 539,
            ),
            164 => 
            array (
                'role_id' => 1,
                'user_id' => 552,
            ),
            165 => 
            array (
                'role_id' => 10,
                'user_id' => 552,
            ),
            166 => 
            array (
                'role_id' => 1,
                'user_id' => 553,
            ),
            167 => 
            array (
                'role_id' => 10,
                'user_id' => 553,
            ),
            168 => 
            array (
                'role_id' => 1,
                'user_id' => 571,
            ),
            169 => 
            array (
                'role_id' => 1,
                'user_id' => 579,
            ),
            170 => 
            array (
                'role_id' => 10,
                'user_id' => 579,
            ),
            171 => 
            array (
                'role_id' => 1,
                'user_id' => 584,
            ),
            172 => 
            array (
                'role_id' => 10,
                'user_id' => 584,
            ),
            173 => 
            array (
                'role_id' => 1,
                'user_id' => 585,
            ),
            174 => 
            array (
                'role_id' => 10,
                'user_id' => 585,
            ),
            175 => 
            array (
                'role_id' => 1,
                'user_id' => 589,
            ),
            176 => 
            array (
                'role_id' => 10,
                'user_id' => 589,
            ),
            177 => 
            array (
                'role_id' => 1,
                'user_id' => 591,
            ),
            178 => 
            array (
                'role_id' => 10,
                'user_id' => 591,
            ),
            179 => 
            array (
                'role_id' => 1,
                'user_id' => 593,
            ),
            180 => 
            array (
                'role_id' => 1,
                'user_id' => 600,
            ),
            181 => 
            array (
                'role_id' => 10,
                'user_id' => 600,
            ),
            182 => 
            array (
                'role_id' => 1,
                'user_id' => 606,
            ),
            183 => 
            array (
                'role_id' => 10,
                'user_id' => 606,
            ),
            184 => 
            array (
                'role_id' => 1,
                'user_id' => 611,
            ),
            185 => 
            array (
                'role_id' => 1,
                'user_id' => 621,
            ),
            186 => 
            array (
                'role_id' => 10,
                'user_id' => 621,
            ),
            187 => 
            array (
                'role_id' => 1,
                'user_id' => 622,
            ),
            188 => 
            array (
                'role_id' => 10,
                'user_id' => 622,
            ),
            189 => 
            array (
                'role_id' => 1,
                'user_id' => 623,
            ),
            190 => 
            array (
                'role_id' => 10,
                'user_id' => 623,
            ),
            191 => 
            array (
                'role_id' => 1,
                'user_id' => 624,
            ),
            192 => 
            array (
                'role_id' => 10,
                'user_id' => 624,
            ),
            193 => 
            array (
                'role_id' => 1,
                'user_id' => 625,
            ),
            194 => 
            array (
                'role_id' => 10,
                'user_id' => 625,
            ),
            195 => 
            array (
                'role_id' => 1,
                'user_id' => 638,
            ),
            196 => 
            array (
                'role_id' => 1,
                'user_id' => 641,
            ),
            197 => 
            array (
                'role_id' => 10,
                'user_id' => 641,
            ),
            198 => 
            array (
                'role_id' => 1,
                'user_id' => 644,
            ),
            199 => 
            array (
                'role_id' => 10,
                'user_id' => 644,
            ),
            200 => 
            array (
                'role_id' => 1,
                'user_id' => 650,
            ),
            201 => 
            array (
                'role_id' => 10,
                'user_id' => 650,
            ),
            202 => 
            array (
                'role_id' => 1,
                'user_id' => 653,
            ),
            203 => 
            array (
                'role_id' => 10,
                'user_id' => 653,
            ),
            204 => 
            array (
                'role_id' => 1,
                'user_id' => 654,
            ),
            205 => 
            array (
                'role_id' => 10,
                'user_id' => 654,
            ),
            206 => 
            array (
                'role_id' => 1,
                'user_id' => 656,
            ),
            207 => 
            array (
                'role_id' => 1,
                'user_id' => 660,
            ),
            208 => 
            array (
                'role_id' => 10,
                'user_id' => 660,
            ),
            209 => 
            array (
                'role_id' => 1,
                'user_id' => 665,
            ),
            210 => 
            array (
                'role_id' => 10,
                'user_id' => 665,
            ),
            211 => 
            array (
                'role_id' => 1,
                'user_id' => 676,
            ),
            212 => 
            array (
                'role_id' => 10,
                'user_id' => 676,
            ),
            213 => 
            array (
                'role_id' => 1,
                'user_id' => 680,
            ),
            214 => 
            array (
                'role_id' => 10,
                'user_id' => 680,
            ),
            215 => 
            array (
                'role_id' => 1,
                'user_id' => 698,
            ),
            216 => 
            array (
                'role_id' => 10,
                'user_id' => 698,
            ),
            217 => 
            array (
                'role_id' => 1,
                'user_id' => 703,
            ),
            218 => 
            array (
                'role_id' => 10,
                'user_id' => 703,
            ),
            219 => 
            array (
                'role_id' => 1,
                'user_id' => 707,
            ),
            220 => 
            array (
                'role_id' => 10,
                'user_id' => 707,
            ),
            221 => 
            array (
                'role_id' => 1,
                'user_id' => 709,
            ),
            222 => 
            array (
                'role_id' => 1,
                'user_id' => 712,
            ),
            223 => 
            array (
                'role_id' => 10,
                'user_id' => 712,
            ),
            224 => 
            array (
                'role_id' => 1,
                'user_id' => 714,
            ),
            225 => 
            array (
                'role_id' => 10,
                'user_id' => 714,
            ),
            226 => 
            array (
                'role_id' => 1,
                'user_id' => 738,
            ),
            227 => 
            array (
                'role_id' => 10,
                'user_id' => 738,
            ),
            228 => 
            array (
                'role_id' => 1,
                'user_id' => 739,
            ),
            229 => 
            array (
                'role_id' => 10,
                'user_id' => 739,
            ),
            230 => 
            array (
                'role_id' => 1,
                'user_id' => 740,
            ),
            231 => 
            array (
                'role_id' => 10,
                'user_id' => 740,
            ),
            232 => 
            array (
                'role_id' => 1,
                'user_id' => 748,
            ),
            233 => 
            array (
                'role_id' => 10,
                'user_id' => 748,
            ),
            234 => 
            array (
                'role_id' => 1,
                'user_id' => 751,
            ),
            235 => 
            array (
                'role_id' => 10,
                'user_id' => 751,
            ),
            236 => 
            array (
                'role_id' => 1,
                'user_id' => 752,
            ),
            237 => 
            array (
                'role_id' => 10,
                'user_id' => 752,
            ),
            238 => 
            array (
                'role_id' => 1,
                'user_id' => 762,
            ),
            239 => 
            array (
                'role_id' => 10,
                'user_id' => 762,
            ),
            240 => 
            array (
                'role_id' => 1,
                'user_id' => 769,
            ),
            241 => 
            array (
                'role_id' => 10,
                'user_id' => 769,
            ),
            242 => 
            array (
                'role_id' => 1,
                'user_id' => 778,
            ),
            243 => 
            array (
                'role_id' => 10,
                'user_id' => 778,
            ),
            244 => 
            array (
                'role_id' => 1,
                'user_id' => 781,
            ),
            245 => 
            array (
                'role_id' => 1,
                'user_id' => 789,
            ),
            246 => 
            array (
                'role_id' => 10,
                'user_id' => 789,
            ),
            247 => 
            array (
                'role_id' => 1,
                'user_id' => 796,
            ),
            248 => 
            array (
                'role_id' => 10,
                'user_id' => 796,
            ),
            249 => 
            array (
                'role_id' => 1,
                'user_id' => 797,
            ),
            250 => 
            array (
                'role_id' => 10,
                'user_id' => 797,
            ),
            251 => 
            array (
                'role_id' => 1,
                'user_id' => 801,
            ),
            252 => 
            array (
                'role_id' => 10,
                'user_id' => 801,
            ),
            253 => 
            array (
                'role_id' => 1,
                'user_id' => 802,
            ),
            254 => 
            array (
                'role_id' => 10,
                'user_id' => 802,
            ),
            255 => 
            array (
                'role_id' => 1,
                'user_id' => 803,
            ),
            256 => 
            array (
                'role_id' => 1,
                'user_id' => 808,
            ),
            257 => 
            array (
                'role_id' => 10,
                'user_id' => 808,
            ),
            258 => 
            array (
                'role_id' => 1,
                'user_id' => 813,
            ),
            259 => 
            array (
                'role_id' => 10,
                'user_id' => 813,
            ),
            260 => 
            array (
                'role_id' => 1,
                'user_id' => 814,
            ),
            261 => 
            array (
                'role_id' => 10,
                'user_id' => 814,
            ),
            262 => 
            array (
                'role_id' => 1,
                'user_id' => 835,
            ),
            263 => 
            array (
                'role_id' => 10,
                'user_id' => 835,
            ),
            264 => 
            array (
                'role_id' => 1,
                'user_id' => 836,
            ),
            265 => 
            array (
                'role_id' => 10,
                'user_id' => 836,
            ),
            266 => 
            array (
                'role_id' => 1,
                'user_id' => 841,
            ),
            267 => 
            array (
                'role_id' => 10,
                'user_id' => 841,
            ),
            268 => 
            array (
                'role_id' => 1,
                'user_id' => 843,
            ),
            269 => 
            array (
                'role_id' => 10,
                'user_id' => 843,
            ),
            270 => 
            array (
                'role_id' => 2,
                'user_id' => 844,
            ),
            271 => 
            array (
                'role_id' => 10,
                'user_id' => 844,
            ),
            272 => 
            array (
                'role_id' => 1,
                'user_id' => 846,
            ),
            273 => 
            array (
                'role_id' => 10,
                'user_id' => 846,
            ),
            274 => 
            array (
                'role_id' => 1,
                'user_id' => 848,
            ),
            275 => 
            array (
                'role_id' => 10,
                'user_id' => 848,
            ),
            276 => 
            array (
                'role_id' => 1,
                'user_id' => 873,
            ),
            277 => 
            array (
                'role_id' => 1,
                'user_id' => 888,
            ),
            278 => 
            array (
                'role_id' => 10,
                'user_id' => 888,
            ),
            279 => 
            array (
                'role_id' => 1,
                'user_id' => 891,
            ),
            280 => 
            array (
                'role_id' => 10,
                'user_id' => 891,
            ),
            281 => 
            array (
                'role_id' => 2,
                'user_id' => 903,
            ),
            282 => 
            array (
                'role_id' => 10,
                'user_id' => 903,
            ),
            283 => 
            array (
                'role_id' => 2,
                'user_id' => 909,
            ),
            284 => 
            array (
                'role_id' => 10,
                'user_id' => 909,
            ),
            285 => 
            array (
                'role_id' => 5,
                'user_id' => 911,
            ),
            286 => 
            array (
                'role_id' => 10,
                'user_id' => 911,
            ),
            287 => 
            array (
                'role_id' => 1,
                'user_id' => 917,
            ),
            288 => 
            array (
                'role_id' => 10,
                'user_id' => 917,
            ),
            289 => 
            array (
                'role_id' => 1,
                'user_id' => 922,
            ),
            290 => 
            array (
                'role_id' => 10,
                'user_id' => 922,
            ),
            291 => 
            array (
                'role_id' => 1,
                'user_id' => 937,
            ),
            292 => 
            array (
                'role_id' => 13,
                'user_id' => 937,
            ),
            293 => 
            array (
                'role_id' => 1,
                'user_id' => 940,
            ),
            294 => 
            array (
                'role_id' => 10,
                'user_id' => 940,
            ),
            295 => 
            array (
                'role_id' => 1,
                'user_id' => 960,
            ),
            296 => 
            array (
                'role_id' => 10,
                'user_id' => 960,
            ),
            297 => 
            array (
                'role_id' => 1,
                'user_id' => 966,
            ),
            298 => 
            array (
                'role_id' => 10,
                'user_id' => 966,
            ),
            299 => 
            array (
                'role_id' => 2,
                'user_id' => 974,
            ),
            300 => 
            array (
                'role_id' => 10,
                'user_id' => 974,
            ),
            301 => 
            array (
                'role_id' => 1,
                'user_id' => 976,
            ),
            302 => 
            array (
                'role_id' => 10,
                'user_id' => 976,
            ),
            303 => 
            array (
                'role_id' => 1,
                'user_id' => 981,
            ),
            304 => 
            array (
                'role_id' => 10,
                'user_id' => 981,
            ),
            305 => 
            array (
                'role_id' => 1,
                'user_id' => 989,
            ),
            306 => 
            array (
                'role_id' => 10,
                'user_id' => 989,
            ),
            307 => 
            array (
                'role_id' => 2,
                'user_id' => 992,
            ),
            308 => 
            array (
                'role_id' => 10,
                'user_id' => 992,
            ),
            309 => 
            array (
                'role_id' => 2,
                'user_id' => 997,
            ),
            310 => 
            array (
                'role_id' => 10,
                'user_id' => 997,
            ),
            311 => 
            array (
                'role_id' => 2,
                'user_id' => 1001,
            ),
            312 => 
            array (
                'role_id' => 10,
                'user_id' => 1001,
            ),
            313 => 
            array (
                'role_id' => 1,
                'user_id' => 1002,
            ),
            314 => 
            array (
                'role_id' => 10,
                'user_id' => 1002,
            ),
            315 => 
            array (
                'role_id' => 1,
                'user_id' => 1007,
            ),
            316 => 
            array (
                'role_id' => 10,
                'user_id' => 1007,
            ),
            317 => 
            array (
                'role_id' => 1,
                'user_id' => 1008,
            ),
            318 => 
            array (
                'role_id' => 10,
                'user_id' => 1008,
            ),
            319 => 
            array (
                'role_id' => 1,
                'user_id' => 1023,
            ),
            320 => 
            array (
                'role_id' => 10,
                'user_id' => 1023,
            ),
            321 => 
            array (
                'role_id' => 2,
                'user_id' => 1037,
            ),
            322 => 
            array (
                'role_id' => 10,
                'user_id' => 1037,
            ),
            323 => 
            array (
                'role_id' => 1,
                'user_id' => 1039,
            ),
            324 => 
            array (
                'role_id' => 10,
                'user_id' => 1039,
            ),
            325 => 
            array (
                'role_id' => 1,
                'user_id' => 1042,
            ),
            326 => 
            array (
                'role_id' => 10,
                'user_id' => 1042,
            ),
            327 => 
            array (
                'role_id' => 1,
                'user_id' => 1050,
            ),
            328 => 
            array (
                'role_id' => 10,
                'user_id' => 1050,
            ),
            329 => 
            array (
                'role_id' => 1,
                'user_id' => 1051,
            ),
            330 => 
            array (
                'role_id' => 10,
                'user_id' => 1051,
            ),
            331 => 
            array (
                'role_id' => 1,
                'user_id' => 1054,
            ),
            332 => 
            array (
                'role_id' => 10,
                'user_id' => 1054,
            ),
            333 => 
            array (
                'role_id' => 1,
                'user_id' => 1058,
            ),
            334 => 
            array (
                'role_id' => 10,
                'user_id' => 1058,
            ),
            335 => 
            array (
                'role_id' => 1,
                'user_id' => 1061,
            ),
            336 => 
            array (
                'role_id' => 1,
                'user_id' => 1071,
            ),
            337 => 
            array (
                'role_id' => 10,
                'user_id' => 1071,
            ),
            338 => 
            array (
                'role_id' => 1,
                'user_id' => 1073,
            ),
            339 => 
            array (
                'role_id' => 10,
                'user_id' => 1073,
            ),
            340 => 
            array (
                'role_id' => 1,
                'user_id' => 1077,
            ),
            341 => 
            array (
                'role_id' => 10,
                'user_id' => 1077,
            ),
            342 => 
            array (
                'role_id' => 1,
                'user_id' => 1084,
            ),
            343 => 
            array (
                'role_id' => 10,
                'user_id' => 1084,
            ),
            344 => 
            array (
                'role_id' => 1,
                'user_id' => 1087,
            ),
            345 => 
            array (
                'role_id' => 10,
                'user_id' => 1087,
            ),
            346 => 
            array (
                'role_id' => 1,
                'user_id' => 1099,
            ),
            347 => 
            array (
                'role_id' => 10,
                'user_id' => 1099,
            ),
            348 => 
            array (
                'role_id' => 1,
                'user_id' => 1105,
            ),
            349 => 
            array (
                'role_id' => 10,
                'user_id' => 1105,
            ),
            350 => 
            array (
                'role_id' => 1,
                'user_id' => 1115,
            ),
            351 => 
            array (
                'role_id' => 2,
                'user_id' => 1129,
            ),
            352 => 
            array (
                'role_id' => 10,
                'user_id' => 1129,
            ),
            353 => 
            array (
                'role_id' => 1,
                'user_id' => 1144,
            ),
            354 => 
            array (
                'role_id' => 10,
                'user_id' => 1144,
            ),
            355 => 
            array (
                'role_id' => 1,
                'user_id' => 1145,
            ),
            356 => 
            array (
                'role_id' => 10,
                'user_id' => 1145,
            ),
            357 => 
            array (
                'role_id' => 1,
                'user_id' => 1153,
            ),
            358 => 
            array (
                'role_id' => 10,
                'user_id' => 1153,
            ),
            359 => 
            array (
                'role_id' => 1,
                'user_id' => 1159,
            ),
            360 => 
            array (
                'role_id' => 1,
                'user_id' => 1161,
            ),
            361 => 
            array (
                'role_id' => 10,
                'user_id' => 1161,
            ),
            362 => 
            array (
                'role_id' => 1,
                'user_id' => 1163,
            ),
            363 => 
            array (
                'role_id' => 10,
                'user_id' => 1163,
            ),
            364 => 
            array (
                'role_id' => 1,
                'user_id' => 1166,
            ),
            365 => 
            array (
                'role_id' => 10,
                'user_id' => 1166,
            ),
            366 => 
            array (
                'role_id' => 1,
                'user_id' => 1172,
            ),
            367 => 
            array (
                'role_id' => 10,
                'user_id' => 1172,
            ),
            368 => 
            array (
                'role_id' => 1,
                'user_id' => 1184,
            ),
            369 => 
            array (
                'role_id' => 10,
                'user_id' => 1184,
            ),
            370 => 
            array (
                'role_id' => 1,
                'user_id' => 1188,
            ),
            371 => 
            array (
                'role_id' => 10,
                'user_id' => 1188,
            ),
            372 => 
            array (
                'role_id' => 1,
                'user_id' => 1190,
            ),
            373 => 
            array (
                'role_id' => 10,
                'user_id' => 1190,
            ),
            374 => 
            array (
                'role_id' => 1,
                'user_id' => 1193,
            ),
            375 => 
            array (
                'role_id' => 10,
                'user_id' => 1193,
            ),
            376 => 
            array (
                'role_id' => 1,
                'user_id' => 1199,
            ),
            377 => 
            array (
                'role_id' => 10,
                'user_id' => 1199,
            ),
            378 => 
            array (
                'role_id' => 1,
                'user_id' => 1201,
            ),
            379 => 
            array (
                'role_id' => 10,
                'user_id' => 1201,
            ),
            380 => 
            array (
                'role_id' => 1,
                'user_id' => 1206,
            ),
            381 => 
            array (
                'role_id' => 10,
                'user_id' => 1206,
            ),
            382 => 
            array (
                'role_id' => 1,
                'user_id' => 1207,
            ),
            383 => 
            array (
                'role_id' => 10,
                'user_id' => 1207,
            ),
            384 => 
            array (
                'role_id' => 2,
                'user_id' => 1208,
            ),
            385 => 
            array (
                'role_id' => 10,
                'user_id' => 1208,
            ),
            386 => 
            array (
                'role_id' => 1,
                'user_id' => 1212,
            ),
            387 => 
            array (
                'role_id' => 10,
                'user_id' => 1212,
            ),
            388 => 
            array (
                'role_id' => 1,
                'user_id' => 1217,
            ),
            389 => 
            array (
                'role_id' => 10,
                'user_id' => 1217,
            ),
            390 => 
            array (
                'role_id' => 1,
                'user_id' => 1227,
            ),
            391 => 
            array (
                'role_id' => 10,
                'user_id' => 1227,
            ),
            392 => 
            array (
                'role_id' => 1,
                'user_id' => 1230,
            ),
            393 => 
            array (
                'role_id' => 10,
                'user_id' => 1230,
            ),
            394 => 
            array (
                'role_id' => 1,
                'user_id' => 1237,
            ),
            395 => 
            array (
                'role_id' => 10,
                'user_id' => 1237,
            ),
            396 => 
            array (
                'role_id' => 1,
                'user_id' => 1240,
            ),
            397 => 
            array (
                'role_id' => 1,
                'user_id' => 1241,
            ),
            398 => 
            array (
                'role_id' => 10,
                'user_id' => 1241,
            ),
            399 => 
            array (
                'role_id' => 1,
                'user_id' => 1242,
            ),
            400 => 
            array (
                'role_id' => 1,
                'user_id' => 1246,
            ),
            401 => 
            array (
                'role_id' => 10,
                'user_id' => 1246,
            ),
            402 => 
            array (
                'role_id' => 1,
                'user_id' => 1247,
            ),
            403 => 
            array (
                'role_id' => 1,
                'user_id' => 1259,
            ),
            404 => 
            array (
                'role_id' => 10,
                'user_id' => 1259,
            ),
            405 => 
            array (
                'role_id' => 2,
                'user_id' => 1261,
            ),
            406 => 
            array (
                'role_id' => 10,
                'user_id' => 1261,
            ),
            407 => 
            array (
                'role_id' => 1,
                'user_id' => 1265,
            ),
            408 => 
            array (
                'role_id' => 10,
                'user_id' => 1265,
            ),
            409 => 
            array (
                'role_id' => 1,
                'user_id' => 1268,
            ),
            410 => 
            array (
                'role_id' => 10,
                'user_id' => 1268,
            ),
            411 => 
            array (
                'role_id' => 1,
                'user_id' => 1274,
            ),
            412 => 
            array (
                'role_id' => 10,
                'user_id' => 1274,
            ),
            413 => 
            array (
                'role_id' => 2,
                'user_id' => 1290,
            ),
            414 => 
            array (
                'role_id' => 10,
                'user_id' => 1290,
            ),
            415 => 
            array (
                'role_id' => 1,
                'user_id' => 1291,
            ),
            416 => 
            array (
                'role_id' => 10,
                'user_id' => 1291,
            ),
            417 => 
            array (
                'role_id' => 1,
                'user_id' => 1296,
            ),
            418 => 
            array (
                'role_id' => 10,
                'user_id' => 1296,
            ),
            419 => 
            array (
                'role_id' => 1,
                'user_id' => 1297,
            ),
            420 => 
            array (
                'role_id' => 10,
                'user_id' => 1297,
            ),
            421 => 
            array (
                'role_id' => 1,
                'user_id' => 1300,
            ),
            422 => 
            array (
                'role_id' => 10,
                'user_id' => 1300,
            ),
            423 => 
            array (
                'role_id' => 1,
                'user_id' => 1305,
            ),
            424 => 
            array (
                'role_id' => 1,
                'user_id' => 1308,
            ),
            425 => 
            array (
                'role_id' => 10,
                'user_id' => 1308,
            ),
            426 => 
            array (
                'role_id' => 1,
                'user_id' => 1311,
            ),
            427 => 
            array (
                'role_id' => 1,
                'user_id' => 1313,
            ),
            428 => 
            array (
                'role_id' => 10,
                'user_id' => 1313,
            ),
            429 => 
            array (
                'role_id' => 4,
                'user_id' => 1317,
            ),
            430 => 
            array (
                'role_id' => 1,
                'user_id' => 1323,
            ),
            431 => 
            array (
                'role_id' => 10,
                'user_id' => 1323,
            ),
            432 => 
            array (
                'role_id' => 1,
                'user_id' => 1325,
            ),
            433 => 
            array (
                'role_id' => 10,
                'user_id' => 1325,
            ),
            434 => 
            array (
                'role_id' => 1,
                'user_id' => 1334,
            ),
            435 => 
            array (
                'role_id' => 10,
                'user_id' => 1334,
            ),
            436 => 
            array (
                'role_id' => 1,
                'user_id' => 1339,
            ),
            437 => 
            array (
                'role_id' => 1,
                'user_id' => 1346,
            ),
            438 => 
            array (
                'role_id' => 10,
                'user_id' => 1346,
            ),
            439 => 
            array (
                'role_id' => 1,
                'user_id' => 1349,
            ),
            440 => 
            array (
                'role_id' => 10,
                'user_id' => 1349,
            ),
            441 => 
            array (
                'role_id' => 1,
                'user_id' => 1358,
            ),
            442 => 
            array (
                'role_id' => 10,
                'user_id' => 1358,
            ),
            443 => 
            array (
                'role_id' => 2,
                'user_id' => 1359,
            ),
            444 => 
            array (
                'role_id' => 10,
                'user_id' => 1359,
            ),
            445 => 
            array (
                'role_id' => 6,
                'user_id' => 1363,
            ),
            446 => 
            array (
                'role_id' => 10,
                'user_id' => 1363,
            ),
            447 => 
            array (
                'role_id' => 1,
                'user_id' => 1366,
            ),
            448 => 
            array (
                'role_id' => 10,
                'user_id' => 1366,
            ),
            449 => 
            array (
                'role_id' => 1,
                'user_id' => 1367,
            ),
            450 => 
            array (
                'role_id' => 10,
                'user_id' => 1367,
            ),
            451 => 
            array (
                'role_id' => 1,
                'user_id' => 1369,
            ),
            452 => 
            array (
                'role_id' => 10,
                'user_id' => 1369,
            ),
            453 => 
            array (
                'role_id' => 1,
                'user_id' => 1371,
            ),
            454 => 
            array (
                'role_id' => 1,
                'user_id' => 1377,
            ),
            455 => 
            array (
                'role_id' => 10,
                'user_id' => 1377,
            ),
            456 => 
            array (
                'role_id' => 2,
                'user_id' => 1379,
            ),
            457 => 
            array (
                'role_id' => 10,
                'user_id' => 1379,
            ),
            458 => 
            array (
                'role_id' => 1,
                'user_id' => 1381,
            ),
            459 => 
            array (
                'role_id' => 10,
                'user_id' => 1381,
            ),
            460 => 
            array (
                'role_id' => 1,
                'user_id' => 1382,
            ),
            461 => 
            array (
                'role_id' => 10,
                'user_id' => 1382,
            ),
            462 => 
            array (
                'role_id' => 1,
                'user_id' => 1385,
            ),
            463 => 
            array (
                'role_id' => 1,
                'user_id' => 1387,
            ),
            464 => 
            array (
                'role_id' => 10,
                'user_id' => 1387,
            ),
            465 => 
            array (
                'role_id' => 1,
                'user_id' => 1390,
            ),
            466 => 
            array (
                'role_id' => 1,
                'user_id' => 1392,
            ),
            467 => 
            array (
                'role_id' => 10,
                'user_id' => 1392,
            ),
            468 => 
            array (
                'role_id' => 1,
                'user_id' => 1401,
            ),
            469 => 
            array (
                'role_id' => 2,
                'user_id' => 1406,
            ),
            470 => 
            array (
                'role_id' => 10,
                'user_id' => 1406,
            ),
            471 => 
            array (
                'role_id' => 1,
                'user_id' => 1407,
            ),
            472 => 
            array (
                'role_id' => 10,
                'user_id' => 1407,
            ),
            473 => 
            array (
                'role_id' => 1,
                'user_id' => 1413,
            ),
            474 => 
            array (
                'role_id' => 10,
                'user_id' => 1413,
            ),
            475 => 
            array (
                'role_id' => 1,
                'user_id' => 1419,
            ),
            476 => 
            array (
                'role_id' => 10,
                'user_id' => 1419,
            ),
            477 => 
            array (
                'role_id' => 2,
                'user_id' => 1421,
            ),
            478 => 
            array (
                'role_id' => 10,
                'user_id' => 1421,
            ),
            479 => 
            array (
                'role_id' => 1,
                'user_id' => 1426,
            ),
            480 => 
            array (
                'role_id' => 10,
                'user_id' => 1426,
            ),
            481 => 
            array (
                'role_id' => 1,
                'user_id' => 1433,
            ),
            482 => 
            array (
                'role_id' => 1,
                'user_id' => 1438,
            ),
            483 => 
            array (
                'role_id' => 10,
                'user_id' => 1438,
            ),
            484 => 
            array (
                'role_id' => 1,
                'user_id' => 1439,
            ),
            485 => 
            array (
                'role_id' => 10,
                'user_id' => 1439,
            ),
            486 => 
            array (
                'role_id' => 1,
                'user_id' => 1441,
            ),
            487 => 
            array (
                'role_id' => 1,
                'user_id' => 1450,
            ),
            488 => 
            array (
                'role_id' => 10,
                'user_id' => 1450,
            ),
            489 => 
            array (
                'role_id' => 1,
                'user_id' => 1456,
            ),
            490 => 
            array (
                'role_id' => 10,
                'user_id' => 1456,
            ),
            491 => 
            array (
                'role_id' => 1,
                'user_id' => 1458,
            ),
            492 => 
            array (
                'role_id' => 10,
                'user_id' => 1458,
            ),
            493 => 
            array (
                'role_id' => 1,
                'user_id' => 1459,
            ),
            494 => 
            array (
                'role_id' => 10,
                'user_id' => 1459,
            ),
            495 => 
            array (
                'role_id' => 1,
                'user_id' => 1460,
            ),
            496 => 
            array (
                'role_id' => 1,
                'user_id' => 1475,
            ),
            497 => 
            array (
                'role_id' => 10,
                'user_id' => 1475,
            ),
            498 => 
            array (
                'role_id' => 1,
                'user_id' => 1477,
            ),
            499 => 
            array (
                'role_id' => 10,
                'user_id' => 1477,
            ),
        ));
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'user_id' => 1485,
            ),
            1 => 
            array (
                'role_id' => 10,
                'user_id' => 1485,
            ),
            2 => 
            array (
                'role_id' => 1,
                'user_id' => 1488,
            ),
            3 => 
            array (
                'role_id' => 1,
                'user_id' => 1492,
            ),
            4 => 
            array (
                'role_id' => 10,
                'user_id' => 1492,
            ),
            5 => 
            array (
                'role_id' => 1,
                'user_id' => 1493,
            ),
            6 => 
            array (
                'role_id' => 10,
                'user_id' => 1493,
            ),
            7 => 
            array (
                'role_id' => 1,
                'user_id' => 1494,
            ),
            8 => 
            array (
                'role_id' => 10,
                'user_id' => 1494,
            ),
            9 => 
            array (
                'role_id' => 1,
                'user_id' => 1496,
            ),
            10 => 
            array (
                'role_id' => 10,
                'user_id' => 1496,
            ),
            11 => 
            array (
                'role_id' => 1,
                'user_id' => 1498,
            ),
            12 => 
            array (
                'role_id' => 10,
                'user_id' => 1498,
            ),
            13 => 
            array (
                'role_id' => 1,
                'user_id' => 1499,
            ),
            14 => 
            array (
                'role_id' => 10,
                'user_id' => 1499,
            ),
            15 => 
            array (
                'role_id' => 1,
                'user_id' => 1501,
            ),
            16 => 
            array (
                'role_id' => 10,
                'user_id' => 1501,
            ),
            17 => 
            array (
                'role_id' => 1,
                'user_id' => 1502,
            ),
            18 => 
            array (
                'role_id' => 10,
                'user_id' => 1502,
            ),
            19 => 
            array (
                'role_id' => 1,
                'user_id' => 1506,
            ),
            20 => 
            array (
                'role_id' => 10,
                'user_id' => 1506,
            ),
            21 => 
            array (
                'role_id' => 1,
                'user_id' => 1507,
            ),
            22 => 
            array (
                'role_id' => 10,
                'user_id' => 1507,
            ),
            23 => 
            array (
                'role_id' => 1,
                'user_id' => 1513,
            ),
            24 => 
            array (
                'role_id' => 10,
                'user_id' => 1513,
            ),
            25 => 
            array (
                'role_id' => 1,
                'user_id' => 1516,
            ),
            26 => 
            array (
                'role_id' => 10,
                'user_id' => 1516,
            ),
            27 => 
            array (
                'role_id' => 1,
                'user_id' => 1517,
            ),
            28 => 
            array (
                'role_id' => 10,
                'user_id' => 1517,
            ),
            29 => 
            array (
                'role_id' => 1,
                'user_id' => 1532,
            ),
            30 => 
            array (
                'role_id' => 10,
                'user_id' => 1532,
            ),
            31 => 
            array (
                'role_id' => 1,
                'user_id' => 1535,
            ),
            32 => 
            array (
                'role_id' => 10,
                'user_id' => 1535,
            ),
            33 => 
            array (
                'role_id' => 2,
                'user_id' => 1541,
            ),
            34 => 
            array (
                'role_id' => 10,
                'user_id' => 1541,
            ),
            35 => 
            array (
                'role_id' => 1,
                'user_id' => 1545,
            ),
            36 => 
            array (
                'role_id' => 10,
                'user_id' => 1545,
            ),
            37 => 
            array (
                'role_id' => 2,
                'user_id' => 1549,
            ),
            38 => 
            array (
                'role_id' => 6,
                'user_id' => 1550,
            ),
            39 => 
            array (
                'role_id' => 10,
                'user_id' => 1550,
            ),
            40 => 
            array (
                'role_id' => 1,
                'user_id' => 1551,
            ),
            41 => 
            array (
                'role_id' => 10,
                'user_id' => 1551,
            ),
            42 => 
            array (
                'role_id' => 1,
                'user_id' => 1554,
            ),
            43 => 
            array (
                'role_id' => 10,
                'user_id' => 1554,
            ),
            44 => 
            array (
                'role_id' => 1,
                'user_id' => 1555,
            ),
            45 => 
            array (
                'role_id' => 10,
                'user_id' => 1555,
            ),
            46 => 
            array (
                'role_id' => 1,
                'user_id' => 1556,
            ),
            47 => 
            array (
                'role_id' => 10,
                'user_id' => 1556,
            ),
            48 => 
            array (
                'role_id' => 1,
                'user_id' => 1559,
            ),
            49 => 
            array (
                'role_id' => 10,
                'user_id' => 1559,
            ),
            50 => 
            array (
                'role_id' => 1,
                'user_id' => 1575,
            ),
            51 => 
            array (
                'role_id' => 10,
                'user_id' => 1575,
            ),
            52 => 
            array (
                'role_id' => 1,
                'user_id' => 1577,
            ),
            53 => 
            array (
                'role_id' => 10,
                'user_id' => 1577,
            ),
            54 => 
            array (
                'role_id' => 1,
                'user_id' => 1579,
            ),
            55 => 
            array (
                'role_id' => 10,
                'user_id' => 1579,
            ),
            56 => 
            array (
                'role_id' => 10,
                'user_id' => 1581,
            ),
            57 => 
            array (
                'role_id' => 15,
                'user_id' => 1581,
            ),
            58 => 
            array (
                'role_id' => 1,
                'user_id' => 1585,
            ),
            59 => 
            array (
                'role_id' => 10,
                'user_id' => 1585,
            ),
            60 => 
            array (
                'role_id' => 1,
                'user_id' => 1588,
            ),
            61 => 
            array (
                'role_id' => 10,
                'user_id' => 1588,
            ),
            62 => 
            array (
                'role_id' => 1,
                'user_id' => 1593,
            ),
            63 => 
            array (
                'role_id' => 10,
                'user_id' => 1593,
            ),
            64 => 
            array (
                'role_id' => 1,
                'user_id' => 1594,
            ),
            65 => 
            array (
                'role_id' => 10,
                'user_id' => 1594,
            ),
            66 => 
            array (
                'role_id' => 1,
                'user_id' => 1596,
            ),
            67 => 
            array (
                'role_id' => 1,
                'user_id' => 1599,
            ),
            68 => 
            array (
                'role_id' => 10,
                'user_id' => 1599,
            ),
            69 => 
            array (
                'role_id' => 1,
                'user_id' => 1600,
            ),
            70 => 
            array (
                'role_id' => 1,
                'user_id' => 1616,
            ),
            71 => 
            array (
                'role_id' => 10,
                'user_id' => 1616,
            ),
            72 => 
            array (
                'role_id' => 1,
                'user_id' => 1617,
            ),
            73 => 
            array (
                'role_id' => 10,
                'user_id' => 1617,
            ),
            74 => 
            array (
                'role_id' => 2,
                'user_id' => 1621,
            ),
            75 => 
            array (
                'role_id' => 10,
                'user_id' => 1621,
            ),
            76 => 
            array (
                'role_id' => 2,
                'user_id' => 1623,
            ),
            77 => 
            array (
                'role_id' => 10,
                'user_id' => 1623,
            ),
            78 => 
            array (
                'role_id' => 1,
                'user_id' => 1624,
            ),
            79 => 
            array (
                'role_id' => 10,
                'user_id' => 1624,
            ),
            80 => 
            array (
                'role_id' => 1,
                'user_id' => 1625,
            ),
            81 => 
            array (
                'role_id' => 10,
                'user_id' => 1625,
            ),
            82 => 
            array (
                'role_id' => 1,
                'user_id' => 1627,
            ),
            83 => 
            array (
                'role_id' => 10,
                'user_id' => 1627,
            ),
            84 => 
            array (
                'role_id' => 1,
                'user_id' => 1628,
            ),
            85 => 
            array (
                'role_id' => 10,
                'user_id' => 1628,
            ),
            86 => 
            array (
                'role_id' => 1,
                'user_id' => 1636,
            ),
            87 => 
            array (
                'role_id' => 10,
                'user_id' => 1636,
            ),
            88 => 
            array (
                'role_id' => 1,
                'user_id' => 1637,
            ),
            89 => 
            array (
                'role_id' => 10,
                'user_id' => 1637,
            ),
            90 => 
            array (
                'role_id' => 1,
                'user_id' => 1638,
            ),
            91 => 
            array (
                'role_id' => 10,
                'user_id' => 1638,
            ),
            92 => 
            array (
                'role_id' => 1,
                'user_id' => 1639,
            ),
            93 => 
            array (
                'role_id' => 10,
                'user_id' => 1639,
            ),
            94 => 
            array (
                'role_id' => 1,
                'user_id' => 1642,
            ),
            95 => 
            array (
                'role_id' => 10,
                'user_id' => 1642,
            ),
            96 => 
            array (
                'role_id' => 1,
                'user_id' => 1643,
            ),
            97 => 
            array (
                'role_id' => 10,
                'user_id' => 1643,
            ),
            98 => 
            array (
                'role_id' => 1,
                'user_id' => 1646,
            ),
            99 => 
            array (
                'role_id' => 10,
                'user_id' => 1646,
            ),
            100 => 
            array (
                'role_id' => 1,
                'user_id' => 1649,
            ),
            101 => 
            array (
                'role_id' => 1,
                'user_id' => 1651,
            ),
            102 => 
            array (
                'role_id' => 10,
                'user_id' => 1651,
            ),
            103 => 
            array (
                'role_id' => 1,
                'user_id' => 1654,
            ),
            104 => 
            array (
                'role_id' => 10,
                'user_id' => 1654,
            ),
            105 => 
            array (
                'role_id' => 1,
                'user_id' => 1655,
            ),
            106 => 
            array (
                'role_id' => 10,
                'user_id' => 1655,
            ),
            107 => 
            array (
                'role_id' => 1,
                'user_id' => 1656,
            ),
            108 => 
            array (
                'role_id' => 1,
                'user_id' => 1657,
            ),
            109 => 
            array (
                'role_id' => 10,
                'user_id' => 1657,
            ),
            110 => 
            array (
                'role_id' => 2,
                'user_id' => 1659,
            ),
            111 => 
            array (
                'role_id' => 10,
                'user_id' => 1659,
            ),
            112 => 
            array (
                'role_id' => 2,
                'user_id' => 1660,
            ),
            113 => 
            array (
                'role_id' => 10,
                'user_id' => 1660,
            ),
            114 => 
            array (
                'role_id' => 1,
                'user_id' => 1661,
            ),
            115 => 
            array (
                'role_id' => 1,
                'user_id' => 1664,
            ),
            116 => 
            array (
                'role_id' => 10,
                'user_id' => 1664,
            ),
            117 => 
            array (
                'role_id' => 1,
                'user_id' => 1665,
            ),
            118 => 
            array (
                'role_id' => 10,
                'user_id' => 1665,
            ),
            119 => 
            array (
                'role_id' => 1,
                'user_id' => 1670,
            ),
            120 => 
            array (
                'role_id' => 10,
                'user_id' => 1670,
            ),
            121 => 
            array (
                'role_id' => 1,
                'user_id' => 1671,
            ),
            122 => 
            array (
                'role_id' => 10,
                'user_id' => 1671,
            ),
            123 => 
            array (
                'role_id' => 1,
                'user_id' => 1672,
            ),
            124 => 
            array (
                'role_id' => 1,
                'user_id' => 1673,
            ),
            125 => 
            array (
                'role_id' => 10,
                'user_id' => 1673,
            ),
            126 => 
            array (
                'role_id' => 1,
                'user_id' => 1675,
            ),
            127 => 
            array (
                'role_id' => 10,
                'user_id' => 1675,
            ),
            128 => 
            array (
                'role_id' => 1,
                'user_id' => 1676,
            ),
            129 => 
            array (
                'role_id' => 10,
                'user_id' => 1676,
            ),
            130 => 
            array (
                'role_id' => 1,
                'user_id' => 1677,
            ),
            131 => 
            array (
                'role_id' => 10,
                'user_id' => 1677,
            ),
            132 => 
            array (
                'role_id' => 2,
                'user_id' => 1679,
            ),
            133 => 
            array (
                'role_id' => 10,
                'user_id' => 1679,
            ),
            134 => 
            array (
                'role_id' => 1,
                'user_id' => 1680,
            ),
            135 => 
            array (
                'role_id' => 10,
                'user_id' => 1680,
            ),
            136 => 
            array (
                'role_id' => 1,
                'user_id' => 1681,
            ),
            137 => 
            array (
                'role_id' => 10,
                'user_id' => 1681,
            ),
            138 => 
            array (
                'role_id' => 1,
                'user_id' => 1682,
            ),
            139 => 
            array (
                'role_id' => 10,
                'user_id' => 1682,
            ),
            140 => 
            array (
                'role_id' => 4,
                'user_id' => 1683,
            ),
            141 => 
            array (
                'role_id' => 1,
                'user_id' => 1684,
            ),
            142 => 
            array (
                'role_id' => 10,
                'user_id' => 1684,
            ),
            143 => 
            array (
                'role_id' => 1,
                'user_id' => 1685,
            ),
            144 => 
            array (
                'role_id' => 10,
                'user_id' => 1685,
            ),
            145 => 
            array (
                'role_id' => 1,
                'user_id' => 1688,
            ),
            146 => 
            array (
                'role_id' => 10,
                'user_id' => 1688,
            ),
            147 => 
            array (
                'role_id' => 1,
                'user_id' => 1693,
            ),
            148 => 
            array (
                'role_id' => 10,
                'user_id' => 1693,
            ),
            149 => 
            array (
                'role_id' => 1,
                'user_id' => 1694,
            ),
            150 => 
            array (
                'role_id' => 10,
                'user_id' => 1694,
            ),
            151 => 
            array (
                'role_id' => 1,
                'user_id' => 1696,
            ),
            152 => 
            array (
                'role_id' => 10,
                'user_id' => 1696,
            ),
            153 => 
            array (
                'role_id' => 1,
                'user_id' => 1698,
            ),
            154 => 
            array (
                'role_id' => 10,
                'user_id' => 1698,
            ),
            155 => 
            array (
                'role_id' => 1,
                'user_id' => 1699,
            ),
            156 => 
            array (
                'role_id' => 10,
                'user_id' => 1699,
            ),
            157 => 
            array (
                'role_id' => 1,
                'user_id' => 1701,
            ),
            158 => 
            array (
                'role_id' => 10,
                'user_id' => 1701,
            ),
            159 => 
            array (
                'role_id' => 1,
                'user_id' => 1704,
            ),
            160 => 
            array (
                'role_id' => 10,
                'user_id' => 1704,
            ),
            161 => 
            array (
                'role_id' => 1,
                'user_id' => 1707,
            ),
            162 => 
            array (
                'role_id' => 5,
                'user_id' => 1710,
            ),
            163 => 
            array (
                'role_id' => 10,
                'user_id' => 1710,
            ),
            164 => 
            array (
                'role_id' => 1,
                'user_id' => 1712,
            ),
            165 => 
            array (
                'role_id' => 1,
                'user_id' => 1715,
            ),
            166 => 
            array (
                'role_id' => 10,
                'user_id' => 1715,
            ),
            167 => 
            array (
                'role_id' => 1,
                'user_id' => 1719,
            ),
            168 => 
            array (
                'role_id' => 10,
                'user_id' => 1719,
            ),
            169 => 
            array (
                'role_id' => 1,
                'user_id' => 1720,
            ),
            170 => 
            array (
                'role_id' => 10,
                'user_id' => 1720,
            ),
            171 => 
            array (
                'role_id' => 1,
                'user_id' => 1722,
            ),
            172 => 
            array (
                'role_id' => 1,
                'user_id' => 1723,
            ),
            173 => 
            array (
                'role_id' => 1,
                'user_id' => 1725,
            ),
            174 => 
            array (
                'role_id' => 10,
                'user_id' => 1725,
            ),
            175 => 
            array (
                'role_id' => 1,
                'user_id' => 1728,
            ),
            176 => 
            array (
                'role_id' => 1,
                'user_id' => 1731,
            ),
            177 => 
            array (
                'role_id' => 10,
                'user_id' => 1731,
            ),
            178 => 
            array (
                'role_id' => 1,
                'user_id' => 1738,
            ),
            179 => 
            array (
                'role_id' => 10,
                'user_id' => 1738,
            ),
            180 => 
            array (
                'role_id' => 1,
                'user_id' => 1740,
            ),
            181 => 
            array (
                'role_id' => 10,
                'user_id' => 1740,
            ),
            182 => 
            array (
                'role_id' => 1,
                'user_id' => 1741,
            ),
            183 => 
            array (
                'role_id' => 10,
                'user_id' => 1741,
            ),
            184 => 
            array (
                'role_id' => 1,
                'user_id' => 1744,
            ),
            185 => 
            array (
                'role_id' => 10,
                'user_id' => 1744,
            ),
            186 => 
            array (
                'role_id' => 1,
                'user_id' => 1749,
            ),
            187 => 
            array (
                'role_id' => 2,
                'user_id' => 1756,
            ),
            188 => 
            array (
                'role_id' => 10,
                'user_id' => 1756,
            ),
            189 => 
            array (
                'role_id' => 1,
                'user_id' => 1758,
            ),
            190 => 
            array (
                'role_id' => 10,
                'user_id' => 1758,
            ),
            191 => 
            array (
                'role_id' => 1,
                'user_id' => 1760,
            ),
            192 => 
            array (
                'role_id' => 10,
                'user_id' => 1760,
            ),
            193 => 
            array (
                'role_id' => 1,
                'user_id' => 1761,
            ),
            194 => 
            array (
                'role_id' => 10,
                'user_id' => 1761,
            ),
            195 => 
            array (
                'role_id' => 1,
                'user_id' => 1763,
            ),
            196 => 
            array (
                'role_id' => 10,
                'user_id' => 1763,
            ),
            197 => 
            array (
                'role_id' => 2,
                'user_id' => 1764,
            ),
            198 => 
            array (
                'role_id' => 10,
                'user_id' => 1764,
            ),
            199 => 
            array (
                'role_id' => 1,
                'user_id' => 1768,
            ),
            200 => 
            array (
                'role_id' => 10,
                'user_id' => 1768,
            ),
            201 => 
            array (
                'role_id' => 1,
                'user_id' => 1770,
            ),
            202 => 
            array (
                'role_id' => 10,
                'user_id' => 1770,
            ),
            203 => 
            array (
                'role_id' => 1,
                'user_id' => 1771,
            ),
            204 => 
            array (
                'role_id' => 10,
                'user_id' => 1771,
            ),
            205 => 
            array (
                'role_id' => 1,
                'user_id' => 1772,
            ),
            206 => 
            array (
                'role_id' => 10,
                'user_id' => 1772,
            ),
            207 => 
            array (
                'role_id' => 1,
                'user_id' => 1774,
            ),
            208 => 
            array (
                'role_id' => 10,
                'user_id' => 1774,
            ),
            209 => 
            array (
                'role_id' => 1,
                'user_id' => 1775,
            ),
            210 => 
            array (
                'role_id' => 10,
                'user_id' => 1775,
            ),
            211 => 
            array (
                'role_id' => 1,
                'user_id' => 1778,
            ),
            212 => 
            array (
                'role_id' => 1,
                'user_id' => 1780,
            ),
            213 => 
            array (
                'role_id' => 10,
                'user_id' => 1780,
            ),
            214 => 
            array (
                'role_id' => 1,
                'user_id' => 1781,
            ),
            215 => 
            array (
                'role_id' => 10,
                'user_id' => 1781,
            ),
            216 => 
            array (
                'role_id' => 1,
                'user_id' => 1782,
            ),
            217 => 
            array (
                'role_id' => 10,
                'user_id' => 1782,
            ),
            218 => 
            array (
                'role_id' => 1,
                'user_id' => 1783,
            ),
            219 => 
            array (
                'role_id' => 10,
                'user_id' => 1783,
            ),
            220 => 
            array (
                'role_id' => 1,
                'user_id' => 1786,
            ),
            221 => 
            array (
                'role_id' => 10,
                'user_id' => 1786,
            ),
            222 => 
            array (
                'role_id' => 1,
                'user_id' => 1788,
            ),
            223 => 
            array (
                'role_id' => 10,
                'user_id' => 1788,
            ),
            224 => 
            array (
                'role_id' => 1,
                'user_id' => 1797,
            ),
            225 => 
            array (
                'role_id' => 10,
                'user_id' => 1797,
            ),
            226 => 
            array (
                'role_id' => 1,
                'user_id' => 1798,
            ),
            227 => 
            array (
                'role_id' => 1,
                'user_id' => 1802,
            ),
            228 => 
            array (
                'role_id' => 10,
                'user_id' => 1802,
            ),
            229 => 
            array (
                'role_id' => 1,
                'user_id' => 1803,
            ),
            230 => 
            array (
                'role_id' => 1,
                'user_id' => 1804,
            ),
            231 => 
            array (
                'role_id' => 10,
                'user_id' => 1804,
            ),
            232 => 
            array (
                'role_id' => 2,
                'user_id' => 1806,
            ),
            233 => 
            array (
                'role_id' => 10,
                'user_id' => 1806,
            ),
            234 => 
            array (
                'role_id' => 1,
                'user_id' => 1807,
            ),
            235 => 
            array (
                'role_id' => 10,
                'user_id' => 1807,
            ),
            236 => 
            array (
                'role_id' => 1,
                'user_id' => 1808,
            ),
            237 => 
            array (
                'role_id' => 10,
                'user_id' => 1808,
            ),
            238 => 
            array (
                'role_id' => 1,
                'user_id' => 1809,
            ),
            239 => 
            array (
                'role_id' => 10,
                'user_id' => 1809,
            ),
            240 => 
            array (
                'role_id' => 1,
                'user_id' => 1813,
            ),
            241 => 
            array (
                'role_id' => 4,
                'user_id' => 1814,
            ),
            242 => 
            array (
                'role_id' => 1,
                'user_id' => 1815,
            ),
            243 => 
            array (
                'role_id' => 10,
                'user_id' => 1815,
            ),
            244 => 
            array (
                'role_id' => 1,
                'user_id' => 1816,
            ),
            245 => 
            array (
                'role_id' => 1,
                'user_id' => 1818,
            ),
            246 => 
            array (
                'role_id' => 10,
                'user_id' => 1818,
            ),
            247 => 
            array (
                'role_id' => 2,
                'user_id' => 1823,
            ),
            248 => 
            array (
                'role_id' => 10,
                'user_id' => 1823,
            ),
            249 => 
            array (
                'role_id' => 1,
                'user_id' => 1824,
            ),
            250 => 
            array (
                'role_id' => 10,
                'user_id' => 1824,
            ),
            251 => 
            array (
                'role_id' => 1,
                'user_id' => 1828,
            ),
            252 => 
            array (
                'role_id' => 10,
                'user_id' => 1828,
            ),
            253 => 
            array (
                'role_id' => 1,
                'user_id' => 1831,
            ),
            254 => 
            array (
                'role_id' => 10,
                'user_id' => 1831,
            ),
            255 => 
            array (
                'role_id' => 13,
                'user_id' => 1831,
            ),
            256 => 
            array (
                'role_id' => 1,
                'user_id' => 1835,
            ),
            257 => 
            array (
                'role_id' => 10,
                'user_id' => 1835,
            ),
            258 => 
            array (
                'role_id' => 1,
                'user_id' => 1836,
            ),
            259 => 
            array (
                'role_id' => 1,
                'user_id' => 1837,
            ),
            260 => 
            array (
                'role_id' => 10,
                'user_id' => 1837,
            ),
            261 => 
            array (
                'role_id' => 1,
                'user_id' => 1838,
            ),
            262 => 
            array (
                'role_id' => 1,
                'user_id' => 1839,
            ),
            263 => 
            array (
                'role_id' => 10,
                'user_id' => 1839,
            ),
            264 => 
            array (
                'role_id' => 1,
                'user_id' => 1840,
            ),
            265 => 
            array (
                'role_id' => 1,
                'user_id' => 1842,
            ),
            266 => 
            array (
                'role_id' => 1,
                'user_id' => 1843,
            ),
            267 => 
            array (
                'role_id' => 10,
                'user_id' => 1843,
            ),
            268 => 
            array (
                'role_id' => 1,
                'user_id' => 1844,
            ),
            269 => 
            array (
                'role_id' => 1,
                'user_id' => 1848,
            ),
            270 => 
            array (
                'role_id' => 10,
                'user_id' => 1848,
            ),
            271 => 
            array (
                'role_id' => 1,
                'user_id' => 1849,
            ),
            272 => 
            array (
                'role_id' => 1,
                'user_id' => 1850,
            ),
            273 => 
            array (
                'role_id' => 10,
                'user_id' => 1850,
            ),
            274 => 
            array (
                'role_id' => 1,
                'user_id' => 1853,
            ),
            275 => 
            array (
                'role_id' => 1,
                'user_id' => 1854,
            ),
            276 => 
            array (
                'role_id' => 10,
                'user_id' => 1854,
            ),
            277 => 
            array (
                'role_id' => 1,
                'user_id' => 1859,
            ),
            278 => 
            array (
                'role_id' => 10,
                'user_id' => 1859,
            ),
            279 => 
            array (
                'role_id' => 1,
                'user_id' => 1860,
            ),
            280 => 
            array (
                'role_id' => 10,
                'user_id' => 1860,
            ),
            281 => 
            array (
                'role_id' => 1,
                'user_id' => 1862,
            ),
            282 => 
            array (
                'role_id' => 1,
                'user_id' => 1863,
            ),
            283 => 
            array (
                'role_id' => 10,
                'user_id' => 1863,
            ),
            284 => 
            array (
                'role_id' => 1,
                'user_id' => 1864,
            ),
            285 => 
            array (
                'role_id' => 10,
                'user_id' => 1864,
            ),
            286 => 
            array (
                'role_id' => 1,
                'user_id' => 1865,
            ),
            287 => 
            array (
                'role_id' => 10,
                'user_id' => 1865,
            ),
            288 => 
            array (
                'role_id' => 1,
                'user_id' => 1867,
            ),
            289 => 
            array (
                'role_id' => 10,
                'user_id' => 1867,
            ),
            290 => 
            array (
                'role_id' => 1,
                'user_id' => 1868,
            ),
            291 => 
            array (
                'role_id' => 10,
                'user_id' => 1868,
            ),
            292 => 
            array (
                'role_id' => 1,
                'user_id' => 1869,
            ),
            293 => 
            array (
                'role_id' => 10,
                'user_id' => 1869,
            ),
            294 => 
            array (
                'role_id' => 1,
                'user_id' => 1871,
            ),
            295 => 
            array (
                'role_id' => 10,
                'user_id' => 1871,
            ),
            296 => 
            array (
                'role_id' => 2,
                'user_id' => 1873,
            ),
            297 => 
            array (
                'role_id' => 10,
                'user_id' => 1873,
            ),
            298 => 
            array (
                'role_id' => 1,
                'user_id' => 1874,
            ),
            299 => 
            array (
                'role_id' => 10,
                'user_id' => 1874,
            ),
            300 => 
            array (
                'role_id' => 1,
                'user_id' => 1875,
            ),
            301 => 
            array (
                'role_id' => 10,
                'user_id' => 1875,
            ),
            302 => 
            array (
                'role_id' => 1,
                'user_id' => 1877,
            ),
            303 => 
            array (
                'role_id' => 10,
                'user_id' => 1877,
            ),
            304 => 
            array (
                'role_id' => 1,
                'user_id' => 1878,
            ),
            305 => 
            array (
                'role_id' => 10,
                'user_id' => 1878,
            ),
            306 => 
            array (
                'role_id' => 1,
                'user_id' => 1879,
            ),
            307 => 
            array (
                'role_id' => 2,
                'user_id' => 1880,
            ),
            308 => 
            array (
                'role_id' => 10,
                'user_id' => 1880,
            ),
            309 => 
            array (
                'role_id' => 1,
                'user_id' => 1881,
            ),
            310 => 
            array (
                'role_id' => 10,
                'user_id' => 1881,
            ),
            311 => 
            array (
                'role_id' => 1,
                'user_id' => 1882,
            ),
            312 => 
            array (
                'role_id' => 10,
                'user_id' => 1882,
            ),
            313 => 
            array (
                'role_id' => 1,
                'user_id' => 1884,
            ),
            314 => 
            array (
                'role_id' => 10,
                'user_id' => 1884,
            ),
            315 => 
            array (
                'role_id' => 1,
                'user_id' => 1890,
            ),
            316 => 
            array (
                'role_id' => 10,
                'user_id' => 1890,
            ),
            317 => 
            array (
                'role_id' => 1,
                'user_id' => 1892,
            ),
            318 => 
            array (
                'role_id' => 10,
                'user_id' => 1892,
            ),
            319 => 
            array (
                'role_id' => 1,
                'user_id' => 1893,
            ),
            320 => 
            array (
                'role_id' => 10,
                'user_id' => 1893,
            ),
            321 => 
            array (
                'role_id' => 1,
                'user_id' => 1896,
            ),
            322 => 
            array (
                'role_id' => 10,
                'user_id' => 1896,
            ),
            323 => 
            array (
                'role_id' => 1,
                'user_id' => 1899,
            ),
            324 => 
            array (
                'role_id' => 10,
                'user_id' => 1899,
            ),
            325 => 
            array (
                'role_id' => 1,
                'user_id' => 1900,
            ),
            326 => 
            array (
                'role_id' => 10,
                'user_id' => 1900,
            ),
            327 => 
            array (
                'role_id' => 1,
                'user_id' => 1902,
            ),
            328 => 
            array (
                'role_id' => 10,
                'user_id' => 1902,
            ),
            329 => 
            array (
                'role_id' => 1,
                'user_id' => 1903,
            ),
            330 => 
            array (
                'role_id' => 10,
                'user_id' => 1903,
            ),
            331 => 
            array (
                'role_id' => 1,
                'user_id' => 1904,
            ),
            332 => 
            array (
                'role_id' => 10,
                'user_id' => 1904,
            ),
            333 => 
            array (
                'role_id' => 13,
                'user_id' => 1905,
            ),
            334 => 
            array (
                'role_id' => 14,
                'user_id' => 1905,
            ),
            335 => 
            array (
                'role_id' => 1,
                'user_id' => 1906,
            ),
            336 => 
            array (
                'role_id' => 10,
                'user_id' => 1906,
            ),
            337 => 
            array (
                'role_id' => 1,
                'user_id' => 1909,
            ),
            338 => 
            array (
                'role_id' => 10,
                'user_id' => 1909,
            ),
            339 => 
            array (
                'role_id' => 1,
                'user_id' => 1910,
            ),
            340 => 
            array (
                'role_id' => 10,
                'user_id' => 1910,
            ),
            341 => 
            array (
                'role_id' => 1,
                'user_id' => 1915,
            ),
            342 => 
            array (
                'role_id' => 10,
                'user_id' => 1915,
            ),
            343 => 
            array (
                'role_id' => 1,
                'user_id' => 1916,
            ),
            344 => 
            array (
                'role_id' => 10,
                'user_id' => 1916,
            ),
            345 => 
            array (
                'role_id' => 1,
                'user_id' => 1917,
            ),
            346 => 
            array (
                'role_id' => 10,
                'user_id' => 1917,
            ),
            347 => 
            array (
                'role_id' => 1,
                'user_id' => 1918,
            ),
            348 => 
            array (
                'role_id' => 10,
                'user_id' => 1918,
            ),
            349 => 
            array (
                'role_id' => 1,
                'user_id' => 1919,
            ),
            350 => 
            array (
                'role_id' => 10,
                'user_id' => 1919,
            ),
            351 => 
            array (
                'role_id' => 1,
                'user_id' => 1923,
            ),
            352 => 
            array (
                'role_id' => 10,
                'user_id' => 1923,
            ),
            353 => 
            array (
                'role_id' => 1,
                'user_id' => 1925,
            ),
            354 => 
            array (
                'role_id' => 1,
                'user_id' => 1929,
            ),
            355 => 
            array (
                'role_id' => 10,
                'user_id' => 1929,
            ),
            356 => 
            array (
                'role_id' => 1,
                'user_id' => 1931,
            ),
            357 => 
            array (
                'role_id' => 10,
                'user_id' => 1931,
            ),
            358 => 
            array (
                'role_id' => 1,
                'user_id' => 1932,
            ),
            359 => 
            array (
                'role_id' => 10,
                'user_id' => 1932,
            ),
            360 => 
            array (
                'role_id' => 1,
                'user_id' => 1933,
            ),
            361 => 
            array (
                'role_id' => 10,
                'user_id' => 1933,
            ),
            362 => 
            array (
                'role_id' => 1,
                'user_id' => 1934,
            ),
            363 => 
            array (
                'role_id' => 10,
                'user_id' => 1934,
            ),
            364 => 
            array (
                'role_id' => 1,
                'user_id' => 1938,
            ),
            365 => 
            array (
                'role_id' => 10,
                'user_id' => 1938,
            ),
            366 => 
            array (
                'role_id' => 1,
                'user_id' => 1939,
            ),
            367 => 
            array (
                'role_id' => 10,
                'user_id' => 1939,
            ),
            368 => 
            array (
                'role_id' => 1,
                'user_id' => 1940,
            ),
            369 => 
            array (
                'role_id' => 10,
                'user_id' => 1940,
            ),
            370 => 
            array (
                'role_id' => 1,
                'user_id' => 1942,
            ),
            371 => 
            array (
                'role_id' => 10,
                'user_id' => 1942,
            ),
            372 => 
            array (
                'role_id' => 1,
                'user_id' => 1943,
            ),
            373 => 
            array (
                'role_id' => 10,
                'user_id' => 1943,
            ),
            374 => 
            array (
                'role_id' => 2,
                'user_id' => 1946,
            ),
            375 => 
            array (
                'role_id' => 10,
                'user_id' => 1946,
            ),
            376 => 
            array (
                'role_id' => 1,
                'user_id' => 1947,
            ),
            377 => 
            array (
                'role_id' => 10,
                'user_id' => 1947,
            ),
            378 => 
            array (
                'role_id' => 1,
                'user_id' => 1948,
            ),
            379 => 
            array (
                'role_id' => 10,
                'user_id' => 1948,
            ),
            380 => 
            array (
                'role_id' => 1,
                'user_id' => 1949,
            ),
            381 => 
            array (
                'role_id' => 10,
                'user_id' => 1949,
            ),
            382 => 
            array (
                'role_id' => 1,
                'user_id' => 1951,
            ),
            383 => 
            array (
                'role_id' => 10,
                'user_id' => 1951,
            ),
            384 => 
            array (
                'role_id' => 1,
                'user_id' => 1952,
            ),
            385 => 
            array (
                'role_id' => 10,
                'user_id' => 1952,
            ),
            386 => 
            array (
                'role_id' => 1,
                'user_id' => 1953,
            ),
            387 => 
            array (
                'role_id' => 10,
                'user_id' => 1953,
            ),
            388 => 
            array (
                'role_id' => 1,
                'user_id' => 1957,
            ),
            389 => 
            array (
                'role_id' => 1,
                'user_id' => 1958,
            ),
            390 => 
            array (
                'role_id' => 10,
                'user_id' => 1958,
            ),
            391 => 
            array (
                'role_id' => 5,
                'user_id' => 1959,
            ),
            392 => 
            array (
                'role_id' => 10,
                'user_id' => 1959,
            ),
            393 => 
            array (
                'role_id' => 1,
                'user_id' => 1960,
            ),
            394 => 
            array (
                'role_id' => 10,
                'user_id' => 1960,
            ),
            395 => 
            array (
                'role_id' => 2,
                'user_id' => 1961,
            ),
            396 => 
            array (
                'role_id' => 10,
                'user_id' => 1961,
            ),
            397 => 
            array (
                'role_id' => 1,
                'user_id' => 1963,
            ),
            398 => 
            array (
                'role_id' => 10,
                'user_id' => 1963,
            ),
            399 => 
            array (
                'role_id' => 2,
                'user_id' => 1964,
            ),
            400 => 
            array (
                'role_id' => 10,
                'user_id' => 1964,
            ),
            401 => 
            array (
                'role_id' => 1,
                'user_id' => 1965,
            ),
            402 => 
            array (
                'role_id' => 10,
                'user_id' => 1965,
            ),
            403 => 
            array (
                'role_id' => 1,
                'user_id' => 1966,
            ),
            404 => 
            array (
                'role_id' => 10,
                'user_id' => 1966,
            ),
            405 => 
            array (
                'role_id' => 2,
                'user_id' => 1968,
            ),
            406 => 
            array (
                'role_id' => 10,
                'user_id' => 1968,
            ),
            407 => 
            array (
                'role_id' => 1,
                'user_id' => 1973,
            ),
            408 => 
            array (
                'role_id' => 2,
                'user_id' => 1974,
            ),
            409 => 
            array (
                'role_id' => 10,
                'user_id' => 1974,
            ),
            410 => 
            array (
                'role_id' => 1,
                'user_id' => 1975,
            ),
            411 => 
            array (
                'role_id' => 10,
                'user_id' => 1975,
            ),
            412 => 
            array (
                'role_id' => 1,
                'user_id' => 1976,
            ),
            413 => 
            array (
                'role_id' => 10,
                'user_id' => 1976,
            ),
            414 => 
            array (
                'role_id' => 1,
                'user_id' => 1977,
            ),
            415 => 
            array (
                'role_id' => 10,
                'user_id' => 1977,
            ),
            416 => 
            array (
                'role_id' => 1,
                'user_id' => 1979,
            ),
            417 => 
            array (
                'role_id' => 1,
                'user_id' => 1980,
            ),
            418 => 
            array (
                'role_id' => 10,
                'user_id' => 1980,
            ),
            419 => 
            array (
                'role_id' => 1,
                'user_id' => 1982,
            ),
            420 => 
            array (
                'role_id' => 10,
                'user_id' => 1982,
            ),
            421 => 
            array (
                'role_id' => 1,
                'user_id' => 1983,
            ),
            422 => 
            array (
                'role_id' => 1,
                'user_id' => 1986,
            ),
            423 => 
            array (
                'role_id' => 10,
                'user_id' => 1986,
            ),
            424 => 
            array (
                'role_id' => 1,
                'user_id' => 1990,
            ),
            425 => 
            array (
                'role_id' => 10,
                'user_id' => 1990,
            ),
            426 => 
            array (
                'role_id' => 1,
                'user_id' => 1991,
            ),
            427 => 
            array (
                'role_id' => 10,
                'user_id' => 1991,
            ),
            428 => 
            array (
                'role_id' => 1,
                'user_id' => 1992,
            ),
            429 => 
            array (
                'role_id' => 10,
                'user_id' => 1992,
            ),
            430 => 
            array (
                'role_id' => 1,
                'user_id' => 1993,
            ),
            431 => 
            array (
                'role_id' => 10,
                'user_id' => 1993,
            ),
            432 => 
            array (
                'role_id' => 1,
                'user_id' => 1994,
            ),
            433 => 
            array (
                'role_id' => 10,
                'user_id' => 1994,
            ),
            434 => 
            array (
                'role_id' => 1,
                'user_id' => 1997,
            ),
            435 => 
            array (
                'role_id' => 10,
                'user_id' => 1997,
            ),
            436 => 
            array (
                'role_id' => 1,
                'user_id' => 1998,
            ),
            437 => 
            array (
                'role_id' => 10,
                'user_id' => 1998,
            ),
            438 => 
            array (
                'role_id' => 1,
                'user_id' => 1999,
            ),
            439 => 
            array (
                'role_id' => 10,
                'user_id' => 1999,
            ),
            440 => 
            array (
                'role_id' => 1,
                'user_id' => 2000,
            ),
            441 => 
            array (
                'role_id' => 10,
                'user_id' => 2000,
            ),
            442 => 
            array (
                'role_id' => 1,
                'user_id' => 2001,
            ),
            443 => 
            array (
                'role_id' => 10,
                'user_id' => 2001,
            ),
            444 => 
            array (
                'role_id' => 1,
                'user_id' => 2002,
            ),
            445 => 
            array (
                'role_id' => 10,
                'user_id' => 2002,
            ),
            446 => 
            array (
                'role_id' => 1,
                'user_id' => 2004,
            ),
            447 => 
            array (
                'role_id' => 1,
                'user_id' => 2006,
            ),
            448 => 
            array (
                'role_id' => 10,
                'user_id' => 2006,
            ),
            449 => 
            array (
                'role_id' => 1,
                'user_id' => 2007,
            ),
            450 => 
            array (
                'role_id' => 2,
                'user_id' => 2008,
            ),
            451 => 
            array (
                'role_id' => 10,
                'user_id' => 2008,
            ),
            452 => 
            array (
                'role_id' => 1,
                'user_id' => 2009,
            ),
            453 => 
            array (
                'role_id' => 10,
                'user_id' => 2009,
            ),
            454 => 
            array (
                'role_id' => 2,
                'user_id' => 2012,
            ),
            455 => 
            array (
                'role_id' => 10,
                'user_id' => 2012,
            ),
            456 => 
            array (
                'role_id' => 1,
                'user_id' => 2013,
            ),
            457 => 
            array (
                'role_id' => 10,
                'user_id' => 2013,
            ),
            458 => 
            array (
                'role_id' => 2,
                'user_id' => 2016,
            ),
            459 => 
            array (
                'role_id' => 10,
                'user_id' => 2016,
            ),
            460 => 
            array (
                'role_id' => 1,
                'user_id' => 2020,
            ),
            461 => 
            array (
                'role_id' => 10,
                'user_id' => 2020,
            ),
            462 => 
            array (
                'role_id' => 1,
                'user_id' => 2021,
            ),
            463 => 
            array (
                'role_id' => 10,
                'user_id' => 2021,
            ),
            464 => 
            array (
                'role_id' => 1,
                'user_id' => 2023,
            ),
            465 => 
            array (
                'role_id' => 10,
                'user_id' => 2023,
            ),
            466 => 
            array (
                'role_id' => 1,
                'user_id' => 2025,
            ),
            467 => 
            array (
                'role_id' => 10,
                'user_id' => 2025,
            ),
            468 => 
            array (
                'role_id' => 1,
                'user_id' => 2026,
            ),
            469 => 
            array (
                'role_id' => 10,
                'user_id' => 2026,
            ),
            470 => 
            array (
                'role_id' => 1,
                'user_id' => 2027,
            ),
            471 => 
            array (
                'role_id' => 10,
                'user_id' => 2027,
            ),
            472 => 
            array (
                'role_id' => 1,
                'user_id' => 2028,
            ),
            473 => 
            array (
                'role_id' => 10,
                'user_id' => 2028,
            ),
            474 => 
            array (
                'role_id' => 2,
                'user_id' => 2029,
            ),
            475 => 
            array (
                'role_id' => 10,
                'user_id' => 2029,
            ),
            476 => 
            array (
                'role_id' => 1,
                'user_id' => 2031,
            ),
            477 => 
            array (
                'role_id' => 10,
                'user_id' => 2031,
            ),
            478 => 
            array (
                'role_id' => 1,
                'user_id' => 2032,
            ),
            479 => 
            array (
                'role_id' => 10,
                'user_id' => 2032,
            ),
            480 => 
            array (
                'role_id' => 10,
                'user_id' => 2033,
            ),
            481 => 
            array (
                'role_id' => 15,
                'user_id' => 2033,
            ),
            482 => 
            array (
                'role_id' => 1,
                'user_id' => 2034,
            ),
            483 => 
            array (
                'role_id' => 10,
                'user_id' => 2034,
            ),
            484 => 
            array (
                'role_id' => 1,
                'user_id' => 2035,
            ),
            485 => 
            array (
                'role_id' => 10,
                'user_id' => 2035,
            ),
            486 => 
            array (
                'role_id' => 1,
                'user_id' => 2036,
            ),
            487 => 
            array (
                'role_id' => 10,
                'user_id' => 2036,
            ),
            488 => 
            array (
                'role_id' => 1,
                'user_id' => 2037,
            ),
            489 => 
            array (
                'role_id' => 10,
                'user_id' => 2037,
            ),
            490 => 
            array (
                'role_id' => 1,
                'user_id' => 2039,
            ),
            491 => 
            array (
                'role_id' => 10,
                'user_id' => 2039,
            ),
            492 => 
            array (
                'role_id' => 1,
                'user_id' => 2041,
            ),
            493 => 
            array (
                'role_id' => 10,
                'user_id' => 2041,
            ),
            494 => 
            array (
                'role_id' => 1,
                'user_id' => 2043,
            ),
            495 => 
            array (
                'role_id' => 3,
                'user_id' => 2043,
            ),
            496 => 
            array (
                'role_id' => 1,
                'user_id' => 2045,
            ),
            497 => 
            array (
                'role_id' => 10,
                'user_id' => 2045,
            ),
            498 => 
            array (
                'role_id' => 1,
                'user_id' => 2050,
            ),
            499 => 
            array (
                'role_id' => 10,
                'user_id' => 2050,
            ),
        ));
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'user_id' => 2054,
            ),
            1 => 
            array (
                'role_id' => 1,
                'user_id' => 2055,
            ),
            2 => 
            array (
                'role_id' => 10,
                'user_id' => 2055,
            ),
            3 => 
            array (
                'role_id' => 1,
                'user_id' => 2058,
            ),
            4 => 
            array (
                'role_id' => 10,
                'user_id' => 2058,
            ),
            5 => 
            array (
                'role_id' => 1,
                'user_id' => 2059,
            ),
            6 => 
            array (
                'role_id' => 10,
                'user_id' => 2059,
            ),
            7 => 
            array (
                'role_id' => 1,
                'user_id' => 2060,
            ),
            8 => 
            array (
                'role_id' => 10,
                'user_id' => 2060,
            ),
            9 => 
            array (
                'role_id' => 1,
                'user_id' => 2063,
            ),
            10 => 
            array (
                'role_id' => 10,
                'user_id' => 2063,
            ),
            11 => 
            array (
                'role_id' => 1,
                'user_id' => 2064,
            ),
            12 => 
            array (
                'role_id' => 10,
                'user_id' => 2064,
            ),
            13 => 
            array (
                'role_id' => 2,
                'user_id' => 2067,
            ),
            14 => 
            array (
                'role_id' => 10,
                'user_id' => 2067,
            ),
            15 => 
            array (
                'role_id' => 1,
                'user_id' => 2068,
            ),
            16 => 
            array (
                'role_id' => 10,
                'user_id' => 2068,
            ),
            17 => 
            array (
                'role_id' => 1,
                'user_id' => 2070,
            ),
            18 => 
            array (
                'role_id' => 10,
                'user_id' => 2070,
            ),
            19 => 
            array (
                'role_id' => 1,
                'user_id' => 2071,
            ),
            20 => 
            array (
                'role_id' => 10,
                'user_id' => 2071,
            ),
            21 => 
            array (
                'role_id' => 1,
                'user_id' => 2073,
            ),
            22 => 
            array (
                'role_id' => 10,
                'user_id' => 2073,
            ),
            23 => 
            array (
                'role_id' => 1,
                'user_id' => 2075,
            ),
            24 => 
            array (
                'role_id' => 10,
                'user_id' => 2075,
            ),
            25 => 
            array (
                'role_id' => 1,
                'user_id' => 2076,
            ),
            26 => 
            array (
                'role_id' => 10,
                'user_id' => 2076,
            ),
            27 => 
            array (
                'role_id' => 1,
                'user_id' => 2077,
            ),
            28 => 
            array (
                'role_id' => 10,
                'user_id' => 2077,
            ),
            29 => 
            array (
                'role_id' => 1,
                'user_id' => 2078,
            ),
            30 => 
            array (
                'role_id' => 10,
                'user_id' => 2078,
            ),
            31 => 
            array (
                'role_id' => 1,
                'user_id' => 2079,
            ),
            32 => 
            array (
                'role_id' => 10,
                'user_id' => 2079,
            ),
            33 => 
            array (
                'role_id' => 1,
                'user_id' => 2080,
            ),
            34 => 
            array (
                'role_id' => 10,
                'user_id' => 2080,
            ),
            35 => 
            array (
                'role_id' => 1,
                'user_id' => 2082,
            ),
            36 => 
            array (
                'role_id' => 10,
                'user_id' => 2082,
            ),
            37 => 
            array (
                'role_id' => 1,
                'user_id' => 2084,
            ),
            38 => 
            array (
                'role_id' => 10,
                'user_id' => 2084,
            ),
            39 => 
            array (
                'role_id' => 1,
                'user_id' => 2085,
            ),
            40 => 
            array (
                'role_id' => 10,
                'user_id' => 2085,
            ),
            41 => 
            array (
                'role_id' => 13,
                'user_id' => 2086,
            ),
            42 => 
            array (
                'role_id' => 14,
                'user_id' => 2086,
            ),
            43 => 
            array (
                'role_id' => 1,
                'user_id' => 2088,
            ),
            44 => 
            array (
                'role_id' => 10,
                'user_id' => 2088,
            ),
            45 => 
            array (
                'role_id' => 1,
                'user_id' => 2089,
            ),
            46 => 
            array (
                'role_id' => 10,
                'user_id' => 2089,
            ),
            47 => 
            array (
                'role_id' => 1,
                'user_id' => 2090,
            ),
            48 => 
            array (
                'role_id' => 10,
                'user_id' => 2090,
            ),
            49 => 
            array (
                'role_id' => 1,
                'user_id' => 2092,
            ),
            50 => 
            array (
                'role_id' => 1,
                'user_id' => 2094,
            ),
            51 => 
            array (
                'role_id' => 10,
                'user_id' => 2094,
            ),
            52 => 
            array (
                'role_id' => 1,
                'user_id' => 2095,
            ),
            53 => 
            array (
                'role_id' => 10,
                'user_id' => 2095,
            ),
            54 => 
            array (
                'role_id' => 1,
                'user_id' => 2096,
            ),
            55 => 
            array (
                'role_id' => 10,
                'user_id' => 2096,
            ),
            56 => 
            array (
                'role_id' => 1,
                'user_id' => 2097,
            ),
            57 => 
            array (
                'role_id' => 10,
                'user_id' => 2097,
            ),
            58 => 
            array (
                'role_id' => 1,
                'user_id' => 2098,
            ),
            59 => 
            array (
                'role_id' => 10,
                'user_id' => 2098,
            ),
            60 => 
            array (
                'role_id' => 1,
                'user_id' => 2100,
            ),
            61 => 
            array (
                'role_id' => 10,
                'user_id' => 2100,
            ),
            62 => 
            array (
                'role_id' => 1,
                'user_id' => 2102,
            ),
            63 => 
            array (
                'role_id' => 10,
                'user_id' => 2102,
            ),
            64 => 
            array (
                'role_id' => 2,
                'user_id' => 2103,
            ),
            65 => 
            array (
                'role_id' => 10,
                'user_id' => 2103,
            ),
            66 => 
            array (
                'role_id' => 1,
                'user_id' => 2105,
            ),
            67 => 
            array (
                'role_id' => 10,
                'user_id' => 2105,
            ),
            68 => 
            array (
                'role_id' => 1,
                'user_id' => 2107,
            ),
            69 => 
            array (
                'role_id' => 10,
                'user_id' => 2107,
            ),
            70 => 
            array (
                'role_id' => 1,
                'user_id' => 2109,
            ),
            71 => 
            array (
                'role_id' => 10,
                'user_id' => 2109,
            ),
            72 => 
            array (
                'role_id' => 1,
                'user_id' => 2110,
            ),
            73 => 
            array (
                'role_id' => 10,
                'user_id' => 2110,
            ),
            74 => 
            array (
                'role_id' => 1,
                'user_id' => 2114,
            ),
            75 => 
            array (
                'role_id' => 1,
                'user_id' => 2115,
            ),
            76 => 
            array (
                'role_id' => 10,
                'user_id' => 2115,
            ),
            77 => 
            array (
                'role_id' => 1,
                'user_id' => 2116,
            ),
            78 => 
            array (
                'role_id' => 10,
                'user_id' => 2116,
            ),
            79 => 
            array (
                'role_id' => 1,
                'user_id' => 2117,
            ),
            80 => 
            array (
                'role_id' => 10,
                'user_id' => 2117,
            ),
            81 => 
            array (
                'role_id' => 1,
                'user_id' => 2121,
            ),
            82 => 
            array (
                'role_id' => 10,
                'user_id' => 2121,
            ),
            83 => 
            array (
                'role_id' => 1,
                'user_id' => 2122,
            ),
            84 => 
            array (
                'role_id' => 10,
                'user_id' => 2122,
            ),
            85 => 
            array (
                'role_id' => 1,
                'user_id' => 2123,
            ),
            86 => 
            array (
                'role_id' => 10,
                'user_id' => 2123,
            ),
            87 => 
            array (
                'role_id' => 1,
                'user_id' => 2124,
            ),
            88 => 
            array (
                'role_id' => 10,
                'user_id' => 2124,
            ),
            89 => 
            array (
                'role_id' => 1,
                'user_id' => 2125,
            ),
            90 => 
            array (
                'role_id' => 10,
                'user_id' => 2125,
            ),
            91 => 
            array (
                'role_id' => 1,
                'user_id' => 2126,
            ),
            92 => 
            array (
                'role_id' => 10,
                'user_id' => 2126,
            ),
            93 => 
            array (
                'role_id' => 1,
                'user_id' => 2128,
            ),
            94 => 
            array (
                'role_id' => 10,
                'user_id' => 2128,
            ),
            95 => 
            array (
                'role_id' => 1,
                'user_id' => 2130,
            ),
            96 => 
            array (
                'role_id' => 13,
                'user_id' => 2130,
            ),
            97 => 
            array (
                'role_id' => 1,
                'user_id' => 2131,
            ),
            98 => 
            array (
                'role_id' => 1,
                'user_id' => 2138,
            ),
            99 => 
            array (
                'role_id' => 1,
                'user_id' => 2139,
            ),
            100 => 
            array (
                'role_id' => 1,
                'user_id' => 2141,
            ),
            101 => 
            array (
                'role_id' => 10,
                'user_id' => 2141,
            ),
            102 => 
            array (
                'role_id' => 1,
                'user_id' => 2142,
            ),
            103 => 
            array (
                'role_id' => 10,
                'user_id' => 2142,
            ),
            104 => 
            array (
                'role_id' => 1,
                'user_id' => 2144,
            ),
            105 => 
            array (
                'role_id' => 10,
                'user_id' => 2144,
            ),
            106 => 
            array (
                'role_id' => 5,
                'user_id' => 2145,
            ),
            107 => 
            array (
                'role_id' => 10,
                'user_id' => 2145,
            ),
            108 => 
            array (
                'role_id' => 5,
                'user_id' => 2146,
            ),
            109 => 
            array (
                'role_id' => 10,
                'user_id' => 2146,
            ),
            110 => 
            array (
                'role_id' => 1,
                'user_id' => 2147,
            ),
            111 => 
            array (
                'role_id' => 1,
                'user_id' => 2148,
            ),
            112 => 
            array (
                'role_id' => 1,
                'user_id' => 2149,
            ),
            113 => 
            array (
                'role_id' => 10,
                'user_id' => 2149,
            ),
            114 => 
            array (
                'role_id' => 5,
                'user_id' => 2150,
            ),
            115 => 
            array (
                'role_id' => 10,
                'user_id' => 2150,
            ),
            116 => 
            array (
                'role_id' => 2,
                'user_id' => 2151,
            ),
            117 => 
            array (
                'role_id' => 10,
                'user_id' => 2151,
            ),
            118 => 
            array (
                'role_id' => 1,
                'user_id' => 2152,
            ),
            119 => 
            array (
                'role_id' => 10,
                'user_id' => 2152,
            ),
            120 => 
            array (
                'role_id' => 1,
                'user_id' => 2153,
            ),
            121 => 
            array (
                'role_id' => 10,
                'user_id' => 2153,
            ),
            122 => 
            array (
                'role_id' => 1,
                'user_id' => 2154,
            ),
            123 => 
            array (
                'role_id' => 10,
                'user_id' => 2154,
            ),
            124 => 
            array (
                'role_id' => 1,
                'user_id' => 2155,
            ),
            125 => 
            array (
                'role_id' => 10,
                'user_id' => 2155,
            ),
            126 => 
            array (
                'role_id' => 1,
                'user_id' => 2503,
            ),
            127 => 
            array (
                'role_id' => 10,
                'user_id' => 2503,
            ),
            128 => 
            array (
                'role_id' => 1,
                'user_id' => 2504,
            ),
            129 => 
            array (
                'role_id' => 3,
                'user_id' => 2504,
            ),
            130 => 
            array (
                'role_id' => 2,
                'user_id' => 2506,
            ),
            131 => 
            array (
                'role_id' => 10,
                'user_id' => 2506,
            ),
            132 => 
            array (
                'role_id' => 1,
                'user_id' => 2508,
            ),
            133 => 
            array (
                'role_id' => 10,
                'user_id' => 2508,
            ),
            134 => 
            array (
                'role_id' => 13,
                'user_id' => 2510,
            ),
            135 => 
            array (
                'role_id' => 14,
                'user_id' => 2510,
            ),
            136 => 
            array (
                'role_id' => 1,
                'user_id' => 2511,
            ),
            137 => 
            array (
                'role_id' => 1,
                'user_id' => 2513,
            ),
            138 => 
            array (
                'role_id' => 10,
                'user_id' => 2513,
            ),
            139 => 
            array (
                'role_id' => 1,
                'user_id' => 2514,
            ),
            140 => 
            array (
                'role_id' => 10,
                'user_id' => 2514,
            ),
            141 => 
            array (
                'role_id' => 1,
                'user_id' => 2518,
            ),
            142 => 
            array (
                'role_id' => 10,
                'user_id' => 2518,
            ),
            143 => 
            array (
                'role_id' => 2,
                'user_id' => 2519,
            ),
            144 => 
            array (
                'role_id' => 10,
                'user_id' => 2519,
            ),
            145 => 
            array (
                'role_id' => 1,
                'user_id' => 2520,
            ),
            146 => 
            array (
                'role_id' => 10,
                'user_id' => 2520,
            ),
            147 => 
            array (
                'role_id' => 1,
                'user_id' => 2521,
            ),
            148 => 
            array (
                'role_id' => 10,
                'user_id' => 2521,
            ),
            149 => 
            array (
                'role_id' => 1,
                'user_id' => 2523,
            ),
            150 => 
            array (
                'role_id' => 3,
                'user_id' => 2523,
            ),
            151 => 
            array (
                'role_id' => 10,
                'user_id' => 2527,
            ),
            152 => 
            array (
                'role_id' => 15,
                'user_id' => 2527,
            ),
            153 => 
            array (
                'role_id' => 1,
                'user_id' => 2528,
            ),
            154 => 
            array (
                'role_id' => 1,
                'user_id' => 2529,
            ),
            155 => 
            array (
                'role_id' => 10,
                'user_id' => 2529,
            ),
            156 => 
            array (
                'role_id' => 1,
                'user_id' => 2530,
            ),
            157 => 
            array (
                'role_id' => 1,
                'user_id' => 2532,
            ),
            158 => 
            array (
                'role_id' => 10,
                'user_id' => 2532,
            ),
            159 => 
            array (
                'role_id' => 10,
                'user_id' => 2534,
            ),
            160 => 
            array (
                'role_id' => 15,
                'user_id' => 2534,
            ),
            161 => 
            array (
                'role_id' => 1,
                'user_id' => 2535,
            ),
            162 => 
            array (
                'role_id' => 10,
                'user_id' => 2535,
            ),
            163 => 
            array (
                'role_id' => 1,
                'user_id' => 2536,
            ),
            164 => 
            array (
                'role_id' => 10,
                'user_id' => 2536,
            ),
            165 => 
            array (
                'role_id' => 1,
                'user_id' => 2538,
            ),
            166 => 
            array (
                'role_id' => 10,
                'user_id' => 2538,
            ),
            167 => 
            array (
                'role_id' => 1,
                'user_id' => 2539,
            ),
            168 => 
            array (
                'role_id' => 10,
                'user_id' => 2539,
            ),
            169 => 
            array (
                'role_id' => 1,
                'user_id' => 2540,
            ),
            170 => 
            array (
                'role_id' => 10,
                'user_id' => 2540,
            ),
            171 => 
            array (
                'role_id' => 5,
                'user_id' => 2541,
            ),
            172 => 
            array (
                'role_id' => 10,
                'user_id' => 2541,
            ),
            173 => 
            array (
                'role_id' => 4,
                'user_id' => 2542,
            ),
            174 => 
            array (
                'role_id' => 2,
                'user_id' => 2543,
            ),
            175 => 
            array (
                'role_id' => 10,
                'user_id' => 2543,
            ),
            176 => 
            array (
                'role_id' => 1,
                'user_id' => 2544,
            ),
            177 => 
            array (
                'role_id' => 2,
                'user_id' => 2545,
            ),
            178 => 
            array (
                'role_id' => 10,
                'user_id' => 2545,
            ),
            179 => 
            array (
                'role_id' => 2,
                'user_id' => 2546,
            ),
            180 => 
            array (
                'role_id' => 10,
                'user_id' => 2546,
            ),
            181 => 
            array (
                'role_id' => 2,
                'user_id' => 2548,
            ),
            182 => 
            array (
                'role_id' => 10,
                'user_id' => 2548,
            ),
            183 => 
            array (
                'role_id' => 2,
                'user_id' => 2549,
            ),
            184 => 
            array (
                'role_id' => 10,
                'user_id' => 2549,
            ),
            185 => 
            array (
                'role_id' => 6,
                'user_id' => 2550,
            ),
            186 => 
            array (
                'role_id' => 10,
                'user_id' => 2550,
            ),
            187 => 
            array (
                'role_id' => 2,
                'user_id' => 2551,
            ),
            188 => 
            array (
                'role_id' => 10,
                'user_id' => 2551,
            ),
            189 => 
            array (
                'role_id' => 2,
                'user_id' => 2552,
            ),
            190 => 
            array (
                'role_id' => 10,
                'user_id' => 2552,
            ),
            191 => 
            array (
                'role_id' => 2,
                'user_id' => 2553,
            ),
            192 => 
            array (
                'role_id' => 10,
                'user_id' => 2553,
            ),
            193 => 
            array (
                'role_id' => 1,
                'user_id' => 2554,
            ),
            194 => 
            array (
                'role_id' => 10,
                'user_id' => 2554,
            ),
            195 => 
            array (
                'role_id' => 4,
                'user_id' => 2558,
            ),
            196 => 
            array (
                'role_id' => 10,
                'user_id' => 2558,
            ),
            197 => 
            array (
                'role_id' => 1,
                'user_id' => 2559,
            ),
            198 => 
            array (
                'role_id' => 10,
                'user_id' => 2559,
            ),
            199 => 
            array (
                'role_id' => 1,
                'user_id' => 2560,
            ),
            200 => 
            array (
                'role_id' => 10,
                'user_id' => 2560,
            ),
            201 => 
            array (
                'role_id' => 1,
                'user_id' => 2561,
            ),
            202 => 
            array (
                'role_id' => 1,
                'user_id' => 2562,
            ),
            203 => 
            array (
                'role_id' => 1,
                'user_id' => 2563,
            ),
            204 => 
            array (
                'role_id' => 10,
                'user_id' => 2563,
            ),
            205 => 
            array (
                'role_id' => 10,
                'user_id' => 2564,
            ),
            206 => 
            array (
                'role_id' => 15,
                'user_id' => 2564,
            ),
            207 => 
            array (
                'role_id' => 10,
                'user_id' => 2565,
            ),
            208 => 
            array (
                'role_id' => 15,
                'user_id' => 2565,
            ),
            209 => 
            array (
                'role_id' => 4,
                'user_id' => 2567,
            ),
            210 => 
            array (
                'role_id' => 10,
                'user_id' => 2567,
            ),
            211 => 
            array (
                'role_id' => 1,
                'user_id' => 2568,
            ),
            212 => 
            array (
                'role_id' => 10,
                'user_id' => 2568,
            ),
            213 => 
            array (
                'role_id' => 1,
                'user_id' => 2569,
            ),
            214 => 
            array (
                'role_id' => 13,
                'user_id' => 2569,
            ),
            215 => 
            array (
                'role_id' => 1,
                'user_id' => 2571,
            ),
            216 => 
            array (
                'role_id' => 10,
                'user_id' => 2571,
            ),
            217 => 
            array (
                'role_id' => 1,
                'user_id' => 2572,
            ),
            218 => 
            array (
                'role_id' => 10,
                'user_id' => 2572,
            ),
            219 => 
            array (
                'role_id' => 1,
                'user_id' => 2574,
            ),
            220 => 
            array (
                'role_id' => 10,
                'user_id' => 2574,
            ),
            221 => 
            array (
                'role_id' => 1,
                'user_id' => 2575,
            ),
            222 => 
            array (
                'role_id' => 2,
                'user_id' => 2577,
            ),
            223 => 
            array (
                'role_id' => 10,
                'user_id' => 2577,
            ),
            224 => 
            array (
                'role_id' => 2,
                'user_id' => 2578,
            ),
            225 => 
            array (
                'role_id' => 10,
                'user_id' => 2578,
            ),
            226 => 
            array (
                'role_id' => 4,
                'user_id' => 2579,
            ),
            227 => 
            array (
                'role_id' => 1,
                'user_id' => 2580,
            ),
            228 => 
            array (
                'role_id' => 1,
                'user_id' => 2584,
            ),
            229 => 
            array (
                'role_id' => 10,
                'user_id' => 2584,
            ),
            230 => 
            array (
                'role_id' => 1,
                'user_id' => 2589,
            ),
            231 => 
            array (
                'role_id' => 10,
                'user_id' => 2589,
            ),
            232 => 
            array (
                'role_id' => 1,
                'user_id' => 2590,
            ),
            233 => 
            array (
                'role_id' => 10,
                'user_id' => 2590,
            ),
            234 => 
            array (
                'role_id' => 2,
                'user_id' => 2591,
            ),
            235 => 
            array (
                'role_id' => 10,
                'user_id' => 2591,
            ),
            236 => 
            array (
                'role_id' => 10,
                'user_id' => 2592,
            ),
            237 => 
            array (
                'role_id' => 15,
                'user_id' => 2592,
            ),
            238 => 
            array (
                'role_id' => 18,
                'user_id' => 2592,
            ),
            239 => 
            array (
                'role_id' => 1,
                'user_id' => 2593,
            ),
            240 => 
            array (
                'role_id' => 1,
                'user_id' => 2595,
            ),
            241 => 
            array (
                'role_id' => 10,
                'user_id' => 2595,
            ),
            242 => 
            array (
                'role_id' => 1,
                'user_id' => 2596,
            ),
            243 => 
            array (
                'role_id' => 10,
                'user_id' => 2596,
            ),
            244 => 
            array (
                'role_id' => 1,
                'user_id' => 2597,
            ),
            245 => 
            array (
                'role_id' => 10,
                'user_id' => 2597,
            ),
            246 => 
            array (
                'role_id' => 1,
                'user_id' => 2598,
            ),
            247 => 
            array (
                'role_id' => 10,
                'user_id' => 2598,
            ),
            248 => 
            array (
                'role_id' => 1,
                'user_id' => 2602,
            ),
            249 => 
            array (
                'role_id' => 10,
                'user_id' => 2602,
            ),
            250 => 
            array (
                'role_id' => 4,
                'user_id' => 2603,
            ),
            251 => 
            array (
                'role_id' => 1,
                'user_id' => 2604,
            ),
            252 => 
            array (
                'role_id' => 10,
                'user_id' => 2604,
            ),
            253 => 
            array (
                'role_id' => 1,
                'user_id' => 2606,
            ),
            254 => 
            array (
                'role_id' => 10,
                'user_id' => 2606,
            ),
            255 => 
            array (
                'role_id' => 1,
                'user_id' => 2607,
            ),
            256 => 
            array (
                'role_id' => 10,
                'user_id' => 2607,
            ),
            257 => 
            array (
                'role_id' => 2,
                'user_id' => 2608,
            ),
            258 => 
            array (
                'role_id' => 10,
                'user_id' => 2608,
            ),
            259 => 
            array (
                'role_id' => 2,
                'user_id' => 2609,
            ),
            260 => 
            array (
                'role_id' => 10,
                'user_id' => 2609,
            ),
            261 => 
            array (
                'role_id' => 2,
                'user_id' => 2613,
            ),
            262 => 
            array (
                'role_id' => 10,
                'user_id' => 2613,
            ),
            263 => 
            array (
                'role_id' => 2,
                'user_id' => 2614,
            ),
            264 => 
            array (
                'role_id' => 10,
                'user_id' => 2614,
            ),
            265 => 
            array (
                'role_id' => 2,
                'user_id' => 2615,
            ),
            266 => 
            array (
                'role_id' => 10,
                'user_id' => 2615,
            ),
            267 => 
            array (
                'role_id' => 2,
                'user_id' => 2616,
            ),
            268 => 
            array (
                'role_id' => 10,
                'user_id' => 2616,
            ),
            269 => 
            array (
                'role_id' => 1,
                'user_id' => 2618,
            ),
            270 => 
            array (
                'role_id' => 10,
                'user_id' => 2618,
            ),
            271 => 
            array (
                'role_id' => 1,
                'user_id' => 2620,
            ),
            272 => 
            array (
                'role_id' => 10,
                'user_id' => 2620,
            ),
            273 => 
            array (
                'role_id' => 1,
                'user_id' => 2621,
            ),
            274 => 
            array (
                'role_id' => 10,
                'user_id' => 2621,
            ),
            275 => 
            array (
                'role_id' => 1,
                'user_id' => 2622,
            ),
            276 => 
            array (
                'role_id' => 10,
                'user_id' => 2622,
            ),
            277 => 
            array (
                'role_id' => 1,
                'user_id' => 2623,
            ),
            278 => 
            array (
                'role_id' => 1,
                'user_id' => 2624,
            ),
            279 => 
            array (
                'role_id' => 10,
                'user_id' => 2624,
            ),
            280 => 
            array (
                'role_id' => 1,
                'user_id' => 2625,
            ),
            281 => 
            array (
                'role_id' => 10,
                'user_id' => 2625,
            ),
            282 => 
            array (
                'role_id' => 1,
                'user_id' => 2626,
            ),
            283 => 
            array (
                'role_id' => 1,
                'user_id' => 2628,
            ),
            284 => 
            array (
                'role_id' => 10,
                'user_id' => 2628,
            ),
            285 => 
            array (
                'role_id' => 1,
                'user_id' => 2631,
            ),
            286 => 
            array (
                'role_id' => 10,
                'user_id' => 2631,
            ),
            287 => 
            array (
                'role_id' => 1,
                'user_id' => 2632,
            ),
            288 => 
            array (
                'role_id' => 1,
                'user_id' => 2633,
            ),
            289 => 
            array (
                'role_id' => 10,
                'user_id' => 2633,
            ),
            290 => 
            array (
                'role_id' => 1,
                'user_id' => 2634,
            ),
            291 => 
            array (
                'role_id' => 10,
                'user_id' => 2634,
            ),
            292 => 
            array (
                'role_id' => 1,
                'user_id' => 2636,
            ),
            293 => 
            array (
                'role_id' => 1,
                'user_id' => 2637,
            ),
            294 => 
            array (
                'role_id' => 10,
                'user_id' => 2637,
            ),
            295 => 
            array (
                'role_id' => 1,
                'user_id' => 2638,
            ),
            296 => 
            array (
                'role_id' => 10,
                'user_id' => 2638,
            ),
            297 => 
            array (
                'role_id' => 1,
                'user_id' => 2639,
            ),
            298 => 
            array (
                'role_id' => 10,
                'user_id' => 2639,
            ),
            299 => 
            array (
                'role_id' => 1,
                'user_id' => 2640,
            ),
            300 => 
            array (
                'role_id' => 10,
                'user_id' => 2640,
            ),
            301 => 
            array (
                'role_id' => 2,
                'user_id' => 2642,
            ),
            302 => 
            array (
                'role_id' => 10,
                'user_id' => 2642,
            ),
            303 => 
            array (
                'role_id' => 1,
                'user_id' => 2644,
            ),
            304 => 
            array (
                'role_id' => 10,
                'user_id' => 2644,
            ),
            305 => 
            array (
                'role_id' => 1,
                'user_id' => 2645,
            ),
            306 => 
            array (
                'role_id' => 10,
                'user_id' => 2645,
            ),
            307 => 
            array (
                'role_id' => 1,
                'user_id' => 2649,
            ),
            308 => 
            array (
                'role_id' => 10,
                'user_id' => 2649,
            ),
            309 => 
            array (
                'role_id' => 1,
                'user_id' => 2650,
            ),
            310 => 
            array (
                'role_id' => 1,
                'user_id' => 2651,
            ),
            311 => 
            array (
                'role_id' => 10,
                'user_id' => 2651,
            ),
            312 => 
            array (
                'role_id' => 1,
                'user_id' => 2652,
            ),
            313 => 
            array (
                'role_id' => 10,
                'user_id' => 2652,
            ),
            314 => 
            array (
                'role_id' => 2,
                'user_id' => 2653,
            ),
            315 => 
            array (
                'role_id' => 10,
                'user_id' => 2653,
            ),
            316 => 
            array (
                'role_id' => 2,
                'user_id' => 2654,
            ),
            317 => 
            array (
                'role_id' => 10,
                'user_id' => 2654,
            ),
            318 => 
            array (
                'role_id' => 1,
                'user_id' => 2655,
            ),
            319 => 
            array (
                'role_id' => 1,
                'user_id' => 2656,
            ),
            320 => 
            array (
                'role_id' => 10,
                'user_id' => 2656,
            ),
            321 => 
            array (
                'role_id' => 2,
                'user_id' => 2658,
            ),
            322 => 
            array (
                'role_id' => 10,
                'user_id' => 2658,
            ),
            323 => 
            array (
                'role_id' => 1,
                'user_id' => 2659,
            ),
            324 => 
            array (
                'role_id' => 1,
                'user_id' => 2662,
            ),
            325 => 
            array (
                'role_id' => 2,
                'user_id' => 2673,
            ),
            326 => 
            array (
                'role_id' => 10,
                'user_id' => 2673,
            ),
            327 => 
            array (
                'role_id' => 1,
                'user_id' => 2674,
            ),
            328 => 
            array (
                'role_id' => 10,
                'user_id' => 2674,
            ),
            329 => 
            array (
                'role_id' => 1,
                'user_id' => 2675,
            ),
            330 => 
            array (
                'role_id' => 10,
                'user_id' => 2675,
            ),
            331 => 
            array (
                'role_id' => 1,
                'user_id' => 2676,
            ),
            332 => 
            array (
                'role_id' => 2,
                'user_id' => 2677,
            ),
            333 => 
            array (
                'role_id' => 10,
                'user_id' => 2677,
            ),
            334 => 
            array (
                'role_id' => 1,
                'user_id' => 2678,
            ),
            335 => 
            array (
                'role_id' => 10,
                'user_id' => 2678,
            ),
            336 => 
            array (
                'role_id' => 1,
                'user_id' => 2679,
            ),
            337 => 
            array (
                'role_id' => 10,
                'user_id' => 2679,
            ),
            338 => 
            array (
                'role_id' => 2,
                'user_id' => 2680,
            ),
            339 => 
            array (
                'role_id' => 10,
                'user_id' => 2680,
            ),
            340 => 
            array (
                'role_id' => 2,
                'user_id' => 2681,
            ),
            341 => 
            array (
                'role_id' => 10,
                'user_id' => 2681,
            ),
            342 => 
            array (
                'role_id' => 2,
                'user_id' => 2682,
            ),
            343 => 
            array (
                'role_id' => 10,
                'user_id' => 2682,
            ),
            344 => 
            array (
                'role_id' => 1,
                'user_id' => 2683,
            ),
            345 => 
            array (
                'role_id' => 10,
                'user_id' => 2683,
            ),
            346 => 
            array (
                'role_id' => 1,
                'user_id' => 2684,
            ),
            347 => 
            array (
                'role_id' => 1,
                'user_id' => 2685,
            ),
            348 => 
            array (
                'role_id' => 10,
                'user_id' => 2685,
            ),
            349 => 
            array (
                'role_id' => 2,
                'user_id' => 2686,
            ),
            350 => 
            array (
                'role_id' => 10,
                'user_id' => 2686,
            ),
            351 => 
            array (
                'role_id' => 1,
                'user_id' => 2688,
            ),
            352 => 
            array (
                'role_id' => 10,
                'user_id' => 2688,
            ),
            353 => 
            array (
                'role_id' => 1,
                'user_id' => 2690,
            ),
            354 => 
            array (
                'role_id' => 1,
                'user_id' => 2694,
            ),
            355 => 
            array (
                'role_id' => 10,
                'user_id' => 2694,
            ),
            356 => 
            array (
                'role_id' => 1,
                'user_id' => 2695,
            ),
            357 => 
            array (
                'role_id' => 10,
                'user_id' => 2695,
            ),
            358 => 
            array (
                'role_id' => 2,
                'user_id' => 2697,
            ),
            359 => 
            array (
                'role_id' => 10,
                'user_id' => 2697,
            ),
            360 => 
            array (
                'role_id' => 1,
                'user_id' => 2698,
            ),
            361 => 
            array (
                'role_id' => 10,
                'user_id' => 2698,
            ),
            362 => 
            array (
                'role_id' => 1,
                'user_id' => 2699,
            ),
            363 => 
            array (
                'role_id' => 10,
                'user_id' => 2699,
            ),
            364 => 
            array (
                'role_id' => 1,
                'user_id' => 2701,
            ),
            365 => 
            array (
                'role_id' => 10,
                'user_id' => 2701,
            ),
            366 => 
            array (
                'role_id' => 1,
                'user_id' => 2702,
            ),
            367 => 
            array (
                'role_id' => 10,
                'user_id' => 2702,
            ),
            368 => 
            array (
                'role_id' => 1,
                'user_id' => 2704,
            ),
            369 => 
            array (
                'role_id' => 10,
                'user_id' => 2704,
            ),
            370 => 
            array (
                'role_id' => 1,
                'user_id' => 2705,
            ),
            371 => 
            array (
                'role_id' => 10,
                'user_id' => 2705,
            ),
            372 => 
            array (
                'role_id' => 1,
                'user_id' => 2707,
            ),
            373 => 
            array (
                'role_id' => 10,
                'user_id' => 2707,
            ),
            374 => 
            array (
                'role_id' => 1,
                'user_id' => 2709,
            ),
            375 => 
            array (
                'role_id' => 10,
                'user_id' => 2709,
            ),
            376 => 
            array (
                'role_id' => 1,
                'user_id' => 2711,
            ),
            377 => 
            array (
                'role_id' => 10,
                'user_id' => 2711,
            ),
            378 => 
            array (
                'role_id' => 1,
                'user_id' => 2712,
            ),
            379 => 
            array (
                'role_id' => 10,
                'user_id' => 2712,
            ),
            380 => 
            array (
                'role_id' => 1,
                'user_id' => 2713,
            ),
            381 => 
            array (
                'role_id' => 10,
                'user_id' => 2713,
            ),
            382 => 
            array (
                'role_id' => 1,
                'user_id' => 2714,
            ),
            383 => 
            array (
                'role_id' => 10,
                'user_id' => 2714,
            ),
            384 => 
            array (
                'role_id' => 2,
                'user_id' => 2715,
            ),
            385 => 
            array (
                'role_id' => 10,
                'user_id' => 2715,
            ),
            386 => 
            array (
                'role_id' => 1,
                'user_id' => 2716,
            ),
            387 => 
            array (
                'role_id' => 10,
                'user_id' => 2716,
            ),
            388 => 
            array (
                'role_id' => 2,
                'user_id' => 2717,
            ),
            389 => 
            array (
                'role_id' => 10,
                'user_id' => 2717,
            ),
            390 => 
            array (
                'role_id' => 1,
                'user_id' => 2718,
            ),
            391 => 
            array (
                'role_id' => 10,
                'user_id' => 2718,
            ),
            392 => 
            array (
                'role_id' => 1,
                'user_id' => 2719,
            ),
            393 => 
            array (
                'role_id' => 10,
                'user_id' => 2719,
            ),
            394 => 
            array (
                'role_id' => 2,
                'user_id' => 2721,
            ),
            395 => 
            array (
                'role_id' => 10,
                'user_id' => 2721,
            ),
            396 => 
            array (
                'role_id' => 1,
                'user_id' => 2722,
            ),
            397 => 
            array (
                'role_id' => 10,
                'user_id' => 2722,
            ),
            398 => 
            array (
                'role_id' => 2,
                'user_id' => 2723,
            ),
            399 => 
            array (
                'role_id' => 10,
                'user_id' => 2723,
            ),
            400 => 
            array (
                'role_id' => 1,
                'user_id' => 2724,
            ),
            401 => 
            array (
                'role_id' => 10,
                'user_id' => 2724,
            ),
            402 => 
            array (
                'role_id' => 2,
                'user_id' => 2725,
            ),
            403 => 
            array (
                'role_id' => 10,
                'user_id' => 2725,
            ),
            404 => 
            array (
                'role_id' => 1,
                'user_id' => 2727,
            ),
            405 => 
            array (
                'role_id' => 3,
                'user_id' => 2727,
            ),
            406 => 
            array (
                'role_id' => 1,
                'user_id' => 2728,
            ),
            407 => 
            array (
                'role_id' => 1,
                'user_id' => 2729,
            ),
            408 => 
            array (
                'role_id' => 10,
                'user_id' => 2729,
            ),
            409 => 
            array (
                'role_id' => 2,
                'user_id' => 2730,
            ),
            410 => 
            array (
                'role_id' => 10,
                'user_id' => 2730,
            ),
            411 => 
            array (
                'role_id' => 1,
                'user_id' => 2731,
            ),
            412 => 
            array (
                'role_id' => 10,
                'user_id' => 2731,
            ),
            413 => 
            array (
                'role_id' => 1,
                'user_id' => 2732,
            ),
            414 => 
            array (
                'role_id' => 10,
                'user_id' => 2732,
            ),
            415 => 
            array (
                'role_id' => 1,
                'user_id' => 2733,
            ),
            416 => 
            array (
                'role_id' => 3,
                'user_id' => 2733,
            ),
            417 => 
            array (
                'role_id' => 2,
                'user_id' => 2734,
            ),
            418 => 
            array (
                'role_id' => 10,
                'user_id' => 2734,
            ),
            419 => 
            array (
                'role_id' => 2,
                'user_id' => 2735,
            ),
            420 => 
            array (
                'role_id' => 10,
                'user_id' => 2735,
            ),
            421 => 
            array (
                'role_id' => 2,
                'user_id' => 2737,
            ),
            422 => 
            array (
                'role_id' => 10,
                'user_id' => 2737,
            ),
            423 => 
            array (
                'role_id' => 1,
                'user_id' => 2738,
            ),
            424 => 
            array (
                'role_id' => 10,
                'user_id' => 2738,
            ),
            425 => 
            array (
                'role_id' => 2,
                'user_id' => 2739,
            ),
            426 => 
            array (
                'role_id' => 10,
                'user_id' => 2739,
            ),
            427 => 
            array (
                'role_id' => 1,
                'user_id' => 2740,
            ),
            428 => 
            array (
                'role_id' => 10,
                'user_id' => 2740,
            ),
            429 => 
            array (
                'role_id' => 1,
                'user_id' => 2741,
            ),
            430 => 
            array (
                'role_id' => 10,
                'user_id' => 2741,
            ),
            431 => 
            array (
                'role_id' => 1,
                'user_id' => 2744,
            ),
            432 => 
            array (
                'role_id' => 10,
                'user_id' => 2744,
            ),
            433 => 
            array (
                'role_id' => 2,
                'user_id' => 2745,
            ),
            434 => 
            array (
                'role_id' => 10,
                'user_id' => 2745,
            ),
            435 => 
            array (
                'role_id' => 1,
                'user_id' => 2746,
            ),
            436 => 
            array (
                'role_id' => 10,
                'user_id' => 2746,
            ),
            437 => 
            array (
                'role_id' => 1,
                'user_id' => 2747,
            ),
            438 => 
            array (
                'role_id' => 10,
                'user_id' => 2747,
            ),
            439 => 
            array (
                'role_id' => 1,
                'user_id' => 2748,
            ),
            440 => 
            array (
                'role_id' => 1,
                'user_id' => 2749,
            ),
            441 => 
            array (
                'role_id' => 10,
                'user_id' => 2749,
            ),
            442 => 
            array (
                'role_id' => 1,
                'user_id' => 2750,
            ),
            443 => 
            array (
                'role_id' => 10,
                'user_id' => 2750,
            ),
            444 => 
            array (
                'role_id' => 2,
                'user_id' => 2751,
            ),
            445 => 
            array (
                'role_id' => 10,
                'user_id' => 2751,
            ),
            446 => 
            array (
                'role_id' => 2,
                'user_id' => 2752,
            ),
            447 => 
            array (
                'role_id' => 10,
                'user_id' => 2752,
            ),
            448 => 
            array (
                'role_id' => 2,
                'user_id' => 2753,
            ),
            449 => 
            array (
                'role_id' => 10,
                'user_id' => 2753,
            ),
            450 => 
            array (
                'role_id' => 1,
                'user_id' => 2754,
            ),
            451 => 
            array (
                'role_id' => 1,
                'user_id' => 2755,
            ),
            452 => 
            array (
                'role_id' => 1,
                'user_id' => 2756,
            ),
            453 => 
            array (
                'role_id' => 1,
                'user_id' => 2757,
            ),
            454 => 
            array (
                'role_id' => 10,
                'user_id' => 2757,
            ),
            455 => 
            array (
                'role_id' => 1,
                'user_id' => 2758,
            ),
            456 => 
            array (
                'role_id' => 10,
                'user_id' => 2758,
            ),
            457 => 
            array (
                'role_id' => 1,
                'user_id' => 2759,
            ),
            458 => 
            array (
                'role_id' => 1,
                'user_id' => 2760,
            ),
            459 => 
            array (
                'role_id' => 10,
                'user_id' => 2760,
            ),
            460 => 
            array (
                'role_id' => 1,
                'user_id' => 2761,
            ),
            461 => 
            array (
                'role_id' => 10,
                'user_id' => 2761,
            ),
            462 => 
            array (
                'role_id' => 1,
                'user_id' => 2762,
            ),
            463 => 
            array (
                'role_id' => 10,
                'user_id' => 2762,
            ),
            464 => 
            array (
                'role_id' => 1,
                'user_id' => 2763,
            ),
            465 => 
            array (
                'role_id' => 10,
                'user_id' => 2763,
            ),
            466 => 
            array (
                'role_id' => 1,
                'user_id' => 2764,
            ),
            467 => 
            array (
                'role_id' => 3,
                'user_id' => 2764,
            ),
            468 => 
            array (
                'role_id' => 1,
                'user_id' => 2765,
            ),
            469 => 
            array (
                'role_id' => 3,
                'user_id' => 2765,
            ),
            470 => 
            array (
                'role_id' => 1,
                'user_id' => 2766,
            ),
            471 => 
            array (
                'role_id' => 10,
                'user_id' => 2766,
            ),
            472 => 
            array (
                'role_id' => 2,
                'user_id' => 2767,
            ),
            473 => 
            array (
                'role_id' => 10,
                'user_id' => 2767,
            ),
            474 => 
            array (
                'role_id' => 1,
                'user_id' => 2768,
            ),
            475 => 
            array (
                'role_id' => 10,
                'user_id' => 2768,
            ),
            476 => 
            array (
                'role_id' => 1,
                'user_id' => 2769,
            ),
            477 => 
            array (
                'role_id' => 2,
                'user_id' => 2770,
            ),
            478 => 
            array (
                'role_id' => 10,
                'user_id' => 2770,
            ),
            479 => 
            array (
                'role_id' => 1,
                'user_id' => 2771,
            ),
            480 => 
            array (
                'role_id' => 10,
                'user_id' => 2771,
            ),
            481 => 
            array (
                'role_id' => 1,
                'user_id' => 2772,
            ),
            482 => 
            array (
                'role_id' => 2,
                'user_id' => 2773,
            ),
            483 => 
            array (
                'role_id' => 10,
                'user_id' => 2773,
            ),
            484 => 
            array (
                'role_id' => 2,
                'user_id' => 2774,
            ),
            485 => 
            array (
                'role_id' => 10,
                'user_id' => 2774,
            ),
            486 => 
            array (
                'role_id' => 1,
                'user_id' => 2775,
            ),
            487 => 
            array (
                'role_id' => 10,
                'user_id' => 2775,
            ),
            488 => 
            array (
                'role_id' => 2,
                'user_id' => 2776,
            ),
            489 => 
            array (
                'role_id' => 10,
                'user_id' => 2776,
            ),
            490 => 
            array (
                'role_id' => 2,
                'user_id' => 2777,
            ),
            491 => 
            array (
                'role_id' => 10,
                'user_id' => 2777,
            ),
            492 => 
            array (
                'role_id' => 1,
                'user_id' => 2778,
            ),
            493 => 
            array (
                'role_id' => 1,
                'user_id' => 2779,
            ),
            494 => 
            array (
                'role_id' => 4,
                'user_id' => 2780,
            ),
            495 => 
            array (
                'role_id' => 10,
                'user_id' => 2780,
            ),
            496 => 
            array (
                'role_id' => 2,
                'user_id' => 2781,
            ),
            497 => 
            array (
                'role_id' => 10,
                'user_id' => 2781,
            ),
            498 => 
            array (
                'role_id' => 1,
                'user_id' => 2782,
            ),
            499 => 
            array (
                'role_id' => 10,
                'user_id' => 2782,
            ),
        ));
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'user_id' => 2783,
            ),
            1 => 
            array (
                'role_id' => 10,
                'user_id' => 2783,
            ),
            2 => 
            array (
                'role_id' => 1,
                'user_id' => 2784,
            ),
            3 => 
            array (
                'role_id' => 10,
                'user_id' => 2784,
            ),
            4 => 
            array (
                'role_id' => 1,
                'user_id' => 2785,
            ),
            5 => 
            array (
                'role_id' => 10,
                'user_id' => 2785,
            ),
            6 => 
            array (
                'role_id' => 1,
                'user_id' => 2786,
            ),
            7 => 
            array (
                'role_id' => 10,
                'user_id' => 2786,
            ),
            8 => 
            array (
                'role_id' => 2,
                'user_id' => 2787,
            ),
            9 => 
            array (
                'role_id' => 10,
                'user_id' => 2787,
            ),
            10 => 
            array (
                'role_id' => 2,
                'user_id' => 2788,
            ),
            11 => 
            array (
                'role_id' => 10,
                'user_id' => 2788,
            ),
            12 => 
            array (
                'role_id' => 1,
                'user_id' => 2789,
            ),
            13 => 
            array (
                'role_id' => 10,
                'user_id' => 2789,
            ),
            14 => 
            array (
                'role_id' => 1,
                'user_id' => 2790,
            ),
            15 => 
            array (
                'role_id' => 10,
                'user_id' => 2790,
            ),
            16 => 
            array (
                'role_id' => 2,
                'user_id' => 2791,
            ),
            17 => 
            array (
                'role_id' => 10,
                'user_id' => 2791,
            ),
            18 => 
            array (
                'role_id' => 2,
                'user_id' => 2792,
            ),
            19 => 
            array (
                'role_id' => 10,
                'user_id' => 2792,
            ),
            20 => 
            array (
                'role_id' => 1,
                'user_id' => 2793,
            ),
            21 => 
            array (
                'role_id' => 10,
                'user_id' => 2793,
            ),
            22 => 
            array (
                'role_id' => 1,
                'user_id' => 2794,
            ),
            23 => 
            array (
                'role_id' => 10,
                'user_id' => 2794,
            ),
            24 => 
            array (
                'role_id' => 2,
                'user_id' => 2795,
            ),
            25 => 
            array (
                'role_id' => 10,
                'user_id' => 2795,
            ),
            26 => 
            array (
                'role_id' => 1,
                'user_id' => 2796,
            ),
            27 => 
            array (
                'role_id' => 10,
                'user_id' => 2796,
            ),
            28 => 
            array (
                'role_id' => 1,
                'user_id' => 2797,
            ),
            29 => 
            array (
                'role_id' => 10,
                'user_id' => 2797,
            ),
            30 => 
            array (
                'role_id' => 2,
                'user_id' => 2798,
            ),
            31 => 
            array (
                'role_id' => 10,
                'user_id' => 2798,
            ),
            32 => 
            array (
                'role_id' => 1,
                'user_id' => 2799,
            ),
            33 => 
            array (
                'role_id' => 10,
                'user_id' => 2799,
            ),
            34 => 
            array (
                'role_id' => 2,
                'user_id' => 2800,
            ),
            35 => 
            array (
                'role_id' => 10,
                'user_id' => 2800,
            ),
            36 => 
            array (
                'role_id' => 1,
                'user_id' => 2801,
            ),
            37 => 
            array (
                'role_id' => 10,
                'user_id' => 2801,
            ),
            38 => 
            array (
                'role_id' => 1,
                'user_id' => 2802,
            ),
            39 => 
            array (
                'role_id' => 10,
                'user_id' => 2802,
            ),
            40 => 
            array (
                'role_id' => 1,
                'user_id' => 2803,
            ),
            41 => 
            array (
                'role_id' => 10,
                'user_id' => 2803,
            ),
            42 => 
            array (
                'role_id' => 1,
                'user_id' => 2804,
            ),
            43 => 
            array (
                'role_id' => 10,
                'user_id' => 2804,
            ),
            44 => 
            array (
                'role_id' => 1,
                'user_id' => 2805,
            ),
            45 => 
            array (
                'role_id' => 10,
                'user_id' => 2805,
            ),
            46 => 
            array (
                'role_id' => 1,
                'user_id' => 2806,
            ),
            47 => 
            array (
                'role_id' => 10,
                'user_id' => 2806,
            ),
            48 => 
            array (
                'role_id' => 2,
                'user_id' => 2807,
            ),
            49 => 
            array (
                'role_id' => 10,
                'user_id' => 2807,
            ),
            50 => 
            array (
                'role_id' => 1,
                'user_id' => 2808,
            ),
            51 => 
            array (
                'role_id' => 3,
                'user_id' => 2808,
            ),
            52 => 
            array (
                'role_id' => 1,
                'user_id' => 2809,
            ),
            53 => 
            array (
                'role_id' => 10,
                'user_id' => 2809,
            ),
            54 => 
            array (
                'role_id' => 2,
                'user_id' => 2810,
            ),
            55 => 
            array (
                'role_id' => 10,
                'user_id' => 2810,
            ),
            56 => 
            array (
                'role_id' => 2,
                'user_id' => 2811,
            ),
            57 => 
            array (
                'role_id' => 10,
                'user_id' => 2811,
            ),
            58 => 
            array (
                'role_id' => 1,
                'user_id' => 2812,
            ),
            59 => 
            array (
                'role_id' => 10,
                'user_id' => 2812,
            ),
            60 => 
            array (
                'role_id' => 1,
                'user_id' => 2813,
            ),
            61 => 
            array (
                'role_id' => 10,
                'user_id' => 2813,
            ),
            62 => 
            array (
                'role_id' => 1,
                'user_id' => 2814,
            ),
            63 => 
            array (
                'role_id' => 10,
                'user_id' => 2814,
            ),
            64 => 
            array (
                'role_id' => 1,
                'user_id' => 2815,
            ),
            65 => 
            array (
                'role_id' => 10,
                'user_id' => 2815,
            ),
            66 => 
            array (
                'role_id' => 1,
                'user_id' => 2816,
            ),
            67 => 
            array (
                'role_id' => 10,
                'user_id' => 2816,
            ),
            68 => 
            array (
                'role_id' => 1,
                'user_id' => 2817,
            ),
            69 => 
            array (
                'role_id' => 1,
                'user_id' => 2818,
            ),
            70 => 
            array (
                'role_id' => 10,
                'user_id' => 2818,
            ),
            71 => 
            array (
                'role_id' => 1,
                'user_id' => 2819,
            ),
            72 => 
            array (
                'role_id' => 10,
                'user_id' => 2819,
            ),
            73 => 
            array (
                'role_id' => 1,
                'user_id' => 2820,
            ),
            74 => 
            array (
                'role_id' => 10,
                'user_id' => 2820,
            ),
            75 => 
            array (
                'role_id' => 2,
                'user_id' => 2821,
            ),
            76 => 
            array (
                'role_id' => 10,
                'user_id' => 2821,
            ),
            77 => 
            array (
                'role_id' => 1,
                'user_id' => 2822,
            ),
            78 => 
            array (
                'role_id' => 3,
                'user_id' => 2822,
            ),
            79 => 
            array (
                'role_id' => 10,
                'user_id' => 2822,
            ),
            80 => 
            array (
                'role_id' => 1,
                'user_id' => 2823,
            ),
            81 => 
            array (
                'role_id' => 10,
                'user_id' => 2823,
            ),
            82 => 
            array (
                'role_id' => 1,
                'user_id' => 2824,
            ),
            83 => 
            array (
                'role_id' => 10,
                'user_id' => 2824,
            ),
            84 => 
            array (
                'role_id' => 1,
                'user_id' => 2825,
            ),
            85 => 
            array (
                'role_id' => 10,
                'user_id' => 2825,
            ),
            86 => 
            array (
                'role_id' => 2,
                'user_id' => 2826,
            ),
            87 => 
            array (
                'role_id' => 10,
                'user_id' => 2826,
            ),
            88 => 
            array (
                'role_id' => 1,
                'user_id' => 2827,
            ),
            89 => 
            array (
                'role_id' => 10,
                'user_id' => 2827,
            ),
            90 => 
            array (
                'role_id' => 1,
                'user_id' => 2828,
            ),
            91 => 
            array (
                'role_id' => 10,
                'user_id' => 2828,
            ),
            92 => 
            array (
                'role_id' => 1,
                'user_id' => 2829,
            ),
            93 => 
            array (
                'role_id' => 10,
                'user_id' => 2829,
            ),
            94 => 
            array (
                'role_id' => 1,
                'user_id' => 2830,
            ),
            95 => 
            array (
                'role_id' => 10,
                'user_id' => 2830,
            ),
            96 => 
            array (
                'role_id' => 1,
                'user_id' => 2831,
            ),
            97 => 
            array (
                'role_id' => 10,
                'user_id' => 2831,
            ),
            98 => 
            array (
                'role_id' => 1,
                'user_id' => 2832,
            ),
            99 => 
            array (
                'role_id' => 10,
                'user_id' => 2832,
            ),
            100 => 
            array (
                'role_id' => 5,
                'user_id' => 2833,
            ),
            101 => 
            array (
                'role_id' => 10,
                'user_id' => 2833,
            ),
            102 => 
            array (
                'role_id' => 5,
                'user_id' => 2834,
            ),
            103 => 
            array (
                'role_id' => 10,
                'user_id' => 2834,
            ),
            104 => 
            array (
                'role_id' => 1,
                'user_id' => 2835,
            ),
            105 => 
            array (
                'role_id' => 10,
                'user_id' => 2835,
            ),
            106 => 
            array (
                'role_id' => 2,
                'user_id' => 2836,
            ),
            107 => 
            array (
                'role_id' => 10,
                'user_id' => 2836,
            ),
            108 => 
            array (
                'role_id' => 1,
                'user_id' => 2837,
            ),
            109 => 
            array (
                'role_id' => 10,
                'user_id' => 2837,
            ),
            110 => 
            array (
                'role_id' => 1,
                'user_id' => 2838,
            ),
            111 => 
            array (
                'role_id' => 10,
                'user_id' => 2838,
            ),
            112 => 
            array (
                'role_id' => 1,
                'user_id' => 2839,
            ),
            113 => 
            array (
                'role_id' => 10,
                'user_id' => 2839,
            ),
            114 => 
            array (
                'role_id' => 1,
                'user_id' => 2840,
            ),
            115 => 
            array (
                'role_id' => 10,
                'user_id' => 2840,
            ),
            116 => 
            array (
                'role_id' => 4,
                'user_id' => 2841,
            ),
            117 => 
            array (
                'role_id' => 10,
                'user_id' => 2841,
            ),
            118 => 
            array (
                'role_id' => 1,
                'user_id' => 2842,
            ),
            119 => 
            array (
                'role_id' => 10,
                'user_id' => 2842,
            ),
            120 => 
            array (
                'role_id' => 1,
                'user_id' => 2843,
            ),
            121 => 
            array (
                'role_id' => 10,
                'user_id' => 2843,
            ),
            122 => 
            array (
                'role_id' => 1,
                'user_id' => 2844,
            ),
            123 => 
            array (
                'role_id' => 10,
                'user_id' => 2844,
            ),
            124 => 
            array (
                'role_id' => 1,
                'user_id' => 2845,
            ),
            125 => 
            array (
                'role_id' => 10,
                'user_id' => 2845,
            ),
            126 => 
            array (
                'role_id' => 1,
                'user_id' => 2846,
            ),
            127 => 
            array (
                'role_id' => 10,
                'user_id' => 2846,
            ),
            128 => 
            array (
                'role_id' => 2,
                'user_id' => 2847,
            ),
            129 => 
            array (
                'role_id' => 10,
                'user_id' => 2847,
            ),
            130 => 
            array (
                'role_id' => 1,
                'user_id' => 2848,
            ),
            131 => 
            array (
                'role_id' => 10,
                'user_id' => 2848,
            ),
            132 => 
            array (
                'role_id' => 1,
                'user_id' => 2849,
            ),
            133 => 
            array (
                'role_id' => 10,
                'user_id' => 2849,
            ),
            134 => 
            array (
                'role_id' => 1,
                'user_id' => 2850,
            ),
            135 => 
            array (
                'role_id' => 10,
                'user_id' => 2850,
            ),
            136 => 
            array (
                'role_id' => 1,
                'user_id' => 2851,
            ),
            137 => 
            array (
                'role_id' => 10,
                'user_id' => 2851,
            ),
            138 => 
            array (
                'role_id' => 1,
                'user_id' => 2852,
            ),
            139 => 
            array (
                'role_id' => 10,
                'user_id' => 2852,
            ),
            140 => 
            array (
                'role_id' => 1,
                'user_id' => 2853,
            ),
            141 => 
            array (
                'role_id' => 10,
                'user_id' => 2853,
            ),
            142 => 
            array (
                'role_id' => 4,
                'user_id' => 2854,
            ),
            143 => 
            array (
                'role_id' => 1,
                'user_id' => 2855,
            ),
            144 => 
            array (
                'role_id' => 10,
                'user_id' => 2855,
            ),
            145 => 
            array (
                'role_id' => 1,
                'user_id' => 2856,
            ),
            146 => 
            array (
                'role_id' => 10,
                'user_id' => 2856,
            ),
            147 => 
            array (
                'role_id' => 1,
                'user_id' => 2857,
            ),
            148 => 
            array (
                'role_id' => 10,
                'user_id' => 2857,
            ),
            149 => 
            array (
                'role_id' => 1,
                'user_id' => 2858,
            ),
            150 => 
            array (
                'role_id' => 10,
                'user_id' => 2858,
            ),
            151 => 
            array (
                'role_id' => 1,
                'user_id' => 2859,
            ),
            152 => 
            array (
                'role_id' => 10,
                'user_id' => 2859,
            ),
            153 => 
            array (
                'role_id' => 1,
                'user_id' => 2860,
            ),
            154 => 
            array (
                'role_id' => 10,
                'user_id' => 2860,
            ),
            155 => 
            array (
                'role_id' => 2,
                'user_id' => 2861,
            ),
            156 => 
            array (
                'role_id' => 10,
                'user_id' => 2861,
            ),
            157 => 
            array (
                'role_id' => 1,
                'user_id' => 2862,
            ),
            158 => 
            array (
                'role_id' => 10,
                'user_id' => 2862,
            ),
            159 => 
            array (
                'role_id' => 2,
                'user_id' => 2863,
            ),
            160 => 
            array (
                'role_id' => 10,
                'user_id' => 2863,
            ),
            161 => 
            array (
                'role_id' => 2,
                'user_id' => 2864,
            ),
            162 => 
            array (
                'role_id' => 10,
                'user_id' => 2864,
            ),
            163 => 
            array (
                'role_id' => 2,
                'user_id' => 2865,
            ),
            164 => 
            array (
                'role_id' => 10,
                'user_id' => 2865,
            ),
            165 => 
            array (
                'role_id' => 1,
                'user_id' => 2866,
            ),
            166 => 
            array (
                'role_id' => 10,
                'user_id' => 2866,
            ),
            167 => 
            array (
                'role_id' => 1,
                'user_id' => 2867,
            ),
            168 => 
            array (
                'role_id' => 10,
                'user_id' => 2867,
            ),
            169 => 
            array (
                'role_id' => 1,
                'user_id' => 2868,
            ),
            170 => 
            array (
                'role_id' => 10,
                'user_id' => 2868,
            ),
            171 => 
            array (
                'role_id' => 2,
                'user_id' => 2869,
            ),
            172 => 
            array (
                'role_id' => 10,
                'user_id' => 2869,
            ),
            173 => 
            array (
                'role_id' => 2,
                'user_id' => 2870,
            ),
            174 => 
            array (
                'role_id' => 10,
                'user_id' => 2870,
            ),
            175 => 
            array (
                'role_id' => 1,
                'user_id' => 2871,
            ),
            176 => 
            array (
                'role_id' => 10,
                'user_id' => 2871,
            ),
            177 => 
            array (
                'role_id' => 1,
                'user_id' => 2872,
            ),
            178 => 
            array (
                'role_id' => 10,
                'user_id' => 2872,
            ),
            179 => 
            array (
                'role_id' => 1,
                'user_id' => 2873,
            ),
            180 => 
            array (
                'role_id' => 10,
                'user_id' => 2873,
            ),
            181 => 
            array (
                'role_id' => 2,
                'user_id' => 2874,
            ),
            182 => 
            array (
                'role_id' => 10,
                'user_id' => 2874,
            ),
            183 => 
            array (
                'role_id' => 1,
                'user_id' => 2875,
            ),
            184 => 
            array (
                'role_id' => 10,
                'user_id' => 2875,
            ),
            185 => 
            array (
                'role_id' => 1,
                'user_id' => 2876,
            ),
            186 => 
            array (
                'role_id' => 10,
                'user_id' => 2876,
            ),
            187 => 
            array (
                'role_id' => 1,
                'user_id' => 2877,
            ),
            188 => 
            array (
                'role_id' => 10,
                'user_id' => 2877,
            ),
            189 => 
            array (
                'role_id' => 1,
                'user_id' => 2878,
            ),
            190 => 
            array (
                'role_id' => 10,
                'user_id' => 2878,
            ),
            191 => 
            array (
                'role_id' => 1,
                'user_id' => 2879,
            ),
            192 => 
            array (
                'role_id' => 10,
                'user_id' => 2879,
            ),
            193 => 
            array (
                'role_id' => 6,
                'user_id' => 2880,
            ),
            194 => 
            array (
                'role_id' => 10,
                'user_id' => 2880,
            ),
            195 => 
            array (
                'role_id' => 1,
                'user_id' => 2881,
            ),
            196 => 
            array (
                'role_id' => 10,
                'user_id' => 2881,
            ),
            197 => 
            array (
                'role_id' => 4,
                'user_id' => 2882,
            ),
            198 => 
            array (
                'role_id' => 2,
                'user_id' => 2883,
            ),
            199 => 
            array (
                'role_id' => 10,
                'user_id' => 2883,
            ),
            200 => 
            array (
                'role_id' => 1,
                'user_id' => 2884,
            ),
            201 => 
            array (
                'role_id' => 10,
                'user_id' => 2884,
            ),
            202 => 
            array (
                'role_id' => 2,
                'user_id' => 2885,
            ),
            203 => 
            array (
                'role_id' => 10,
                'user_id' => 2885,
            ),
            204 => 
            array (
                'role_id' => 1,
                'user_id' => 2886,
            ),
            205 => 
            array (
                'role_id' => 10,
                'user_id' => 2886,
            ),
            206 => 
            array (
                'role_id' => 2,
                'user_id' => 2887,
            ),
            207 => 
            array (
                'role_id' => 10,
                'user_id' => 2887,
            ),
            208 => 
            array (
                'role_id' => 1,
                'user_id' => 2888,
            ),
            209 => 
            array (
                'role_id' => 10,
                'user_id' => 2888,
            ),
            210 => 
            array (
                'role_id' => 1,
                'user_id' => 2889,
            ),
            211 => 
            array (
                'role_id' => 10,
                'user_id' => 2889,
            ),
            212 => 
            array (
                'role_id' => 2,
                'user_id' => 2890,
            ),
            213 => 
            array (
                'role_id' => 10,
                'user_id' => 2890,
            ),
            214 => 
            array (
                'role_id' => 13,
                'user_id' => 2890,
            ),
            215 => 
            array (
                'role_id' => 1,
                'user_id' => 2891,
            ),
            216 => 
            array (
                'role_id' => 10,
                'user_id' => 2891,
            ),
            217 => 
            array (
                'role_id' => 1,
                'user_id' => 2892,
            ),
            218 => 
            array (
                'role_id' => 10,
                'user_id' => 2892,
            ),
            219 => 
            array (
                'role_id' => 1,
                'user_id' => 2893,
            ),
            220 => 
            array (
                'role_id' => 10,
                'user_id' => 2893,
            ),
            221 => 
            array (
                'role_id' => 1,
                'user_id' => 2894,
            ),
            222 => 
            array (
                'role_id' => 10,
                'user_id' => 2894,
            ),
            223 => 
            array (
                'role_id' => 2,
                'user_id' => 2895,
            ),
            224 => 
            array (
                'role_id' => 10,
                'user_id' => 2895,
            ),
            225 => 
            array (
                'role_id' => 1,
                'user_id' => 2896,
            ),
            226 => 
            array (
                'role_id' => 10,
                'user_id' => 2896,
            ),
            227 => 
            array (
                'role_id' => 1,
                'user_id' => 2897,
            ),
            228 => 
            array (
                'role_id' => 10,
                'user_id' => 2897,
            ),
            229 => 
            array (
                'role_id' => 1,
                'user_id' => 2898,
            ),
            230 => 
            array (
                'role_id' => 10,
                'user_id' => 2898,
            ),
            231 => 
            array (
                'role_id' => 2,
                'user_id' => 2899,
            ),
            232 => 
            array (
                'role_id' => 10,
                'user_id' => 2899,
            ),
            233 => 
            array (
                'role_id' => 1,
                'user_id' => 2900,
            ),
            234 => 
            array (
                'role_id' => 10,
                'user_id' => 2900,
            ),
            235 => 
            array (
                'role_id' => 1,
                'user_id' => 2901,
            ),
            236 => 
            array (
                'role_id' => 10,
                'user_id' => 2901,
            ),
            237 => 
            array (
                'role_id' => 1,
                'user_id' => 2902,
            ),
            238 => 
            array (
                'role_id' => 10,
                'user_id' => 2902,
            ),
            239 => 
            array (
                'role_id' => 2,
                'user_id' => 2903,
            ),
            240 => 
            array (
                'role_id' => 10,
                'user_id' => 2903,
            ),
            241 => 
            array (
                'role_id' => 2,
                'user_id' => 2904,
            ),
            242 => 
            array (
                'role_id' => 10,
                'user_id' => 2904,
            ),
            243 => 
            array (
                'role_id' => 13,
                'user_id' => 2904,
            ),
            244 => 
            array (
                'role_id' => 1,
                'user_id' => 2905,
            ),
            245 => 
            array (
                'role_id' => 10,
                'user_id' => 2905,
            ),
            246 => 
            array (
                'role_id' => 2,
                'user_id' => 2906,
            ),
            247 => 
            array (
                'role_id' => 10,
                'user_id' => 2906,
            ),
            248 => 
            array (
                'role_id' => 1,
                'user_id' => 2907,
            ),
            249 => 
            array (
                'role_id' => 10,
                'user_id' => 2907,
            ),
            250 => 
            array (
                'role_id' => 2,
                'user_id' => 2908,
            ),
            251 => 
            array (
                'role_id' => 10,
                'user_id' => 2908,
            ),
            252 => 
            array (
                'role_id' => 1,
                'user_id' => 2909,
            ),
            253 => 
            array (
                'role_id' => 10,
                'user_id' => 2909,
            ),
            254 => 
            array (
                'role_id' => 1,
                'user_id' => 2910,
            ),
            255 => 
            array (
                'role_id' => 13,
                'user_id' => 2910,
            ),
            256 => 
            array (
                'role_id' => 1,
                'user_id' => 2911,
            ),
            257 => 
            array (
                'role_id' => 13,
                'user_id' => 2911,
            ),
            258 => 
            array (
                'role_id' => 1,
                'user_id' => 2912,
            ),
            259 => 
            array (
                'role_id' => 13,
                'user_id' => 2912,
            ),
            260 => 
            array (
                'role_id' => 2,
                'user_id' => 2913,
            ),
            261 => 
            array (
                'role_id' => 10,
                'user_id' => 2913,
            ),
            262 => 
            array (
                'role_id' => 1,
                'user_id' => 2914,
            ),
            263 => 
            array (
                'role_id' => 13,
                'user_id' => 2914,
            ),
            264 => 
            array (
                'role_id' => 1,
                'user_id' => 2915,
            ),
            265 => 
            array (
                'role_id' => 10,
                'user_id' => 2915,
            ),
            266 => 
            array (
                'role_id' => 1,
                'user_id' => 2916,
            ),
            267 => 
            array (
                'role_id' => 10,
                'user_id' => 2916,
            ),
            268 => 
            array (
                'role_id' => 1,
                'user_id' => 2917,
            ),
            269 => 
            array (
                'role_id' => 10,
                'user_id' => 2917,
            ),
            270 => 
            array (
                'role_id' => 1,
                'user_id' => 2918,
            ),
            271 => 
            array (
                'role_id' => 10,
                'user_id' => 2918,
            ),
            272 => 
            array (
                'role_id' => 6,
                'user_id' => 2919,
            ),
            273 => 
            array (
                'role_id' => 10,
                'user_id' => 2919,
            ),
            274 => 
            array (
                'role_id' => 1,
                'user_id' => 2920,
            ),
            275 => 
            array (
                'role_id' => 10,
                'user_id' => 2920,
            ),
            276 => 
            array (
                'role_id' => 2,
                'user_id' => 2921,
            ),
            277 => 
            array (
                'role_id' => 10,
                'user_id' => 2921,
            ),
            278 => 
            array (
                'role_id' => 2,
                'user_id' => 2922,
            ),
            279 => 
            array (
                'role_id' => 10,
                'user_id' => 2922,
            ),
            280 => 
            array (
                'role_id' => 1,
                'user_id' => 2923,
            ),
            281 => 
            array (
                'role_id' => 10,
                'user_id' => 2923,
            ),
            282 => 
            array (
                'role_id' => 2,
                'user_id' => 2924,
            ),
            283 => 
            array (
                'role_id' => 10,
                'user_id' => 2924,
            ),
            284 => 
            array (
                'role_id' => 1,
                'user_id' => 2925,
            ),
            285 => 
            array (
                'role_id' => 13,
                'user_id' => 2925,
            ),
            286 => 
            array (
                'role_id' => 1,
                'user_id' => 2926,
            ),
            287 => 
            array (
                'role_id' => 10,
                'user_id' => 2926,
            ),
            288 => 
            array (
                'role_id' => 1,
                'user_id' => 2927,
            ),
            289 => 
            array (
                'role_id' => 10,
                'user_id' => 2927,
            ),
            290 => 
            array (
                'role_id' => 1,
                'user_id' => 2928,
            ),
            291 => 
            array (
                'role_id' => 10,
                'user_id' => 2928,
            ),
            292 => 
            array (
                'role_id' => 1,
                'user_id' => 2929,
            ),
            293 => 
            array (
                'role_id' => 10,
                'user_id' => 2929,
            ),
            294 => 
            array (
                'role_id' => 1,
                'user_id' => 2930,
            ),
            295 => 
            array (
                'role_id' => 10,
                'user_id' => 2930,
            ),
            296 => 
            array (
                'role_id' => 1,
                'user_id' => 2931,
            ),
            297 => 
            array (
                'role_id' => 13,
                'user_id' => 2931,
            ),
            298 => 
            array (
                'role_id' => 1,
                'user_id' => 2932,
            ),
            299 => 
            array (
                'role_id' => 10,
                'user_id' => 2932,
            ),
            300 => 
            array (
                'role_id' => 2,
                'user_id' => 2933,
            ),
            301 => 
            array (
                'role_id' => 10,
                'user_id' => 2933,
            ),
            302 => 
            array (
                'role_id' => 2,
                'user_id' => 2934,
            ),
            303 => 
            array (
                'role_id' => 10,
                'user_id' => 2934,
            ),
            304 => 
            array (
                'role_id' => 1,
                'user_id' => 2935,
            ),
            305 => 
            array (
                'role_id' => 10,
                'user_id' => 2935,
            ),
            306 => 
            array (
                'role_id' => 1,
                'user_id' => 2936,
            ),
            307 => 
            array (
                'role_id' => 10,
                'user_id' => 2936,
            ),
            308 => 
            array (
                'role_id' => 2,
                'user_id' => 2937,
            ),
            309 => 
            array (
                'role_id' => 10,
                'user_id' => 2937,
            ),
            310 => 
            array (
                'role_id' => 2,
                'user_id' => 2938,
            ),
            311 => 
            array (
                'role_id' => 10,
                'user_id' => 2938,
            ),
            312 => 
            array (
                'role_id' => 10,
                'user_id' => 2939,
            ),
            313 => 
            array (
                'role_id' => 14,
                'user_id' => 2939,
            ),
            314 => 
            array (
                'role_id' => 1,
                'user_id' => 2940,
            ),
            315 => 
            array (
                'role_id' => 10,
                'user_id' => 2940,
            ),
            316 => 
            array (
                'role_id' => 1,
                'user_id' => 2941,
            ),
            317 => 
            array (
                'role_id' => 10,
                'user_id' => 2941,
            ),
            318 => 
            array (
                'role_id' => 1,
                'user_id' => 2942,
            ),
            319 => 
            array (
                'role_id' => 10,
                'user_id' => 2942,
            ),
            320 => 
            array (
                'role_id' => 1,
                'user_id' => 2943,
            ),
            321 => 
            array (
                'role_id' => 10,
                'user_id' => 2943,
            ),
            322 => 
            array (
                'role_id' => 1,
                'user_id' => 2944,
            ),
            323 => 
            array (
                'role_id' => 10,
                'user_id' => 2944,
            ),
            324 => 
            array (
                'role_id' => 2,
                'user_id' => 2945,
            ),
            325 => 
            array (
                'role_id' => 10,
                'user_id' => 2945,
            ),
            326 => 
            array (
                'role_id' => 2,
                'user_id' => 2946,
            ),
            327 => 
            array (
                'role_id' => 10,
                'user_id' => 2946,
            ),
            328 => 
            array (
                'role_id' => 1,
                'user_id' => 2947,
            ),
            329 => 
            array (
                'role_id' => 10,
                'user_id' => 2947,
            ),
            330 => 
            array (
                'role_id' => 1,
                'user_id' => 2948,
            ),
            331 => 
            array (
                'role_id' => 10,
                'user_id' => 2948,
            ),
            332 => 
            array (
                'role_id' => 1,
                'user_id' => 2949,
            ),
            333 => 
            array (
                'role_id' => 10,
                'user_id' => 2949,
            ),
            334 => 
            array (
                'role_id' => 1,
                'user_id' => 2950,
            ),
            335 => 
            array (
                'role_id' => 10,
                'user_id' => 2950,
            ),
            336 => 
            array (
                'role_id' => 1,
                'user_id' => 2951,
            ),
            337 => 
            array (
                'role_id' => 10,
                'user_id' => 2951,
            ),
            338 => 
            array (
                'role_id' => 1,
                'user_id' => 2952,
            ),
            339 => 
            array (
                'role_id' => 10,
                'user_id' => 2952,
            ),
            340 => 
            array (
                'role_id' => 1,
                'user_id' => 2953,
            ),
            341 => 
            array (
                'role_id' => 10,
                'user_id' => 2953,
            ),
            342 => 
            array (
                'role_id' => 1,
                'user_id' => 2954,
            ),
            343 => 
            array (
                'role_id' => 10,
                'user_id' => 2954,
            ),
            344 => 
            array (
                'role_id' => 10,
                'user_id' => 2955,
            ),
            345 => 
            array (
                'role_id' => 17,
                'user_id' => 2955,
            ),
            346 => 
            array (
                'role_id' => 2,
                'user_id' => 2956,
            ),
            347 => 
            array (
                'role_id' => 10,
                'user_id' => 2956,
            ),
            348 => 
            array (
                'role_id' => 5,
                'user_id' => 2957,
            ),
            349 => 
            array (
                'role_id' => 10,
                'user_id' => 2957,
            ),
            350 => 
            array (
                'role_id' => 1,
                'user_id' => 2958,
            ),
            351 => 
            array (
                'role_id' => 10,
                'user_id' => 2958,
            ),
            352 => 
            array (
                'role_id' => 2,
                'user_id' => 2959,
            ),
            353 => 
            array (
                'role_id' => 10,
                'user_id' => 2959,
            ),
            354 => 
            array (
                'role_id' => 1,
                'user_id' => 2960,
            ),
            355 => 
            array (
                'role_id' => 10,
                'user_id' => 2960,
            ),
            356 => 
            array (
                'role_id' => 1,
                'user_id' => 2961,
            ),
            357 => 
            array (
                'role_id' => 10,
                'user_id' => 2961,
            ),
            358 => 
            array (
                'role_id' => 1,
                'user_id' => 2962,
            ),
            359 => 
            array (
                'role_id' => 10,
                'user_id' => 2962,
            ),
            360 => 
            array (
                'role_id' => 1,
                'user_id' => 2963,
            ),
            361 => 
            array (
                'role_id' => 10,
                'user_id' => 2963,
            ),
            362 => 
            array (
                'role_id' => 2,
                'user_id' => 2964,
            ),
            363 => 
            array (
                'role_id' => 10,
                'user_id' => 2964,
            ),
            364 => 
            array (
                'role_id' => 1,
                'user_id' => 2965,
            ),
            365 => 
            array (
                'role_id' => 10,
                'user_id' => 2965,
            ),
            366 => 
            array (
                'role_id' => 1,
                'user_id' => 2966,
            ),
            367 => 
            array (
                'role_id' => 10,
                'user_id' => 2966,
            ),
            368 => 
            array (
                'role_id' => 2,
                'user_id' => 2967,
            ),
            369 => 
            array (
                'role_id' => 13,
                'user_id' => 2967,
            ),
            370 => 
            array (
                'role_id' => 1,
                'user_id' => 2968,
            ),
            371 => 
            array (
                'role_id' => 10,
                'user_id' => 2968,
            ),
            372 => 
            array (
                'role_id' => 1,
                'user_id' => 2969,
            ),
            373 => 
            array (
                'role_id' => 10,
                'user_id' => 2969,
            ),
            374 => 
            array (
                'role_id' => 1,
                'user_id' => 2970,
            ),
            375 => 
            array (
                'role_id' => 10,
                'user_id' => 2970,
            ),
            376 => 
            array (
                'role_id' => 1,
                'user_id' => 2971,
            ),
            377 => 
            array (
                'role_id' => 10,
                'user_id' => 2971,
            ),
            378 => 
            array (
                'role_id' => 2,
                'user_id' => 2972,
            ),
            379 => 
            array (
                'role_id' => 10,
                'user_id' => 2972,
            ),
            380 => 
            array (
                'role_id' => 2,
                'user_id' => 2973,
            ),
            381 => 
            array (
                'role_id' => 10,
                'user_id' => 2973,
            ),
            382 => 
            array (
                'role_id' => 2,
                'user_id' => 2974,
            ),
            383 => 
            array (
                'role_id' => 10,
                'user_id' => 2974,
            ),
            384 => 
            array (
                'role_id' => 1,
                'user_id' => 2975,
            ),
            385 => 
            array (
                'role_id' => 10,
                'user_id' => 2975,
            ),
            386 => 
            array (
                'role_id' => 1,
                'user_id' => 2976,
            ),
            387 => 
            array (
                'role_id' => 10,
                'user_id' => 2976,
            ),
            388 => 
            array (
                'role_id' => 1,
                'user_id' => 2977,
            ),
            389 => 
            array (
                'role_id' => 10,
                'user_id' => 2977,
            ),
            390 => 
            array (
                'role_id' => 5,
                'user_id' => 2978,
            ),
            391 => 
            array (
                'role_id' => 10,
                'user_id' => 2978,
            ),
            392 => 
            array (
                'role_id' => 1,
                'user_id' => 2979,
            ),
            393 => 
            array (
                'role_id' => 10,
                'user_id' => 2979,
            ),
            394 => 
            array (
                'role_id' => 1,
                'user_id' => 2980,
            ),
            395 => 
            array (
                'role_id' => 10,
                'user_id' => 2980,
            ),
            396 => 
            array (
                'role_id' => 2,
                'user_id' => 2981,
            ),
            397 => 
            array (
                'role_id' => 10,
                'user_id' => 2981,
            ),
            398 => 
            array (
                'role_id' => 1,
                'user_id' => 2982,
            ),
            399 => 
            array (
                'role_id' => 10,
                'user_id' => 2982,
            ),
            400 => 
            array (
                'role_id' => 1,
                'user_id' => 2983,
            ),
            401 => 
            array (
                'role_id' => 10,
                'user_id' => 2983,
            ),
            402 => 
            array (
                'role_id' => 1,
                'user_id' => 2984,
            ),
            403 => 
            array (
                'role_id' => 10,
                'user_id' => 2984,
            ),
            404 => 
            array (
                'role_id' => 1,
                'user_id' => 2985,
            ),
            405 => 
            array (
                'role_id' => 10,
                'user_id' => 2985,
            ),
            406 => 
            array (
                'role_id' => 1,
                'user_id' => 2986,
            ),
            407 => 
            array (
                'role_id' => 10,
                'user_id' => 2986,
            ),
            408 => 
            array (
                'role_id' => 1,
                'user_id' => 2987,
            ),
            409 => 
            array (
                'role_id' => 10,
                'user_id' => 2987,
            ),
            410 => 
            array (
                'role_id' => 1,
                'user_id' => 2988,
            ),
            411 => 
            array (
                'role_id' => 10,
                'user_id' => 2988,
            ),
            412 => 
            array (
                'role_id' => 1,
                'user_id' => 2989,
            ),
            413 => 
            array (
                'role_id' => 10,
                'user_id' => 2989,
            ),
            414 => 
            array (
                'role_id' => 4,
                'user_id' => 2990,
            ),
            415 => 
            array (
                'role_id' => 10,
                'user_id' => 2990,
            ),
            416 => 
            array (
                'role_id' => 4,
                'user_id' => 2991,
            ),
            417 => 
            array (
                'role_id' => 10,
                'user_id' => 2991,
            ),
            418 => 
            array (
                'role_id' => 1,
                'user_id' => 2992,
            ),
            419 => 
            array (
                'role_id' => 10,
                'user_id' => 2992,
            ),
            420 => 
            array (
                'role_id' => 10,
                'user_id' => 2993,
            ),
            421 => 
            array (
                'role_id' => 15,
                'user_id' => 2993,
            ),
            422 => 
            array (
                'role_id' => 10,
                'user_id' => 2994,
            ),
            423 => 
            array (
                'role_id' => 15,
                'user_id' => 2994,
            ),
            424 => 
            array (
                'role_id' => 2,
                'user_id' => 2995,
            ),
            425 => 
            array (
                'role_id' => 10,
                'user_id' => 2995,
            ),
            426 => 
            array (
                'role_id' => 1,
                'user_id' => 2996,
            ),
            427 => 
            array (
                'role_id' => 10,
                'user_id' => 2996,
            ),
            428 => 
            array (
                'role_id' => 1,
                'user_id' => 2997,
            ),
            429 => 
            array (
                'role_id' => 10,
                'user_id' => 2997,
            ),
            430 => 
            array (
                'role_id' => 5,
                'user_id' => 2998,
            ),
            431 => 
            array (
                'role_id' => 10,
                'user_id' => 2998,
            ),
            432 => 
            array (
                'role_id' => 1,
                'user_id' => 2999,
            ),
            433 => 
            array (
                'role_id' => 10,
                'user_id' => 2999,
            ),
            434 => 
            array (
                'role_id' => 1,
                'user_id' => 3000,
            ),
            435 => 
            array (
                'role_id' => 10,
                'user_id' => 3000,
            ),
            436 => 
            array (
                'role_id' => 2,
                'user_id' => 3001,
            ),
            437 => 
            array (
                'role_id' => 10,
                'user_id' => 3001,
            ),
            438 => 
            array (
                'role_id' => 1,
                'user_id' => 3002,
            ),
            439 => 
            array (
                'role_id' => 10,
                'user_id' => 3002,
            ),
            440 => 
            array (
                'role_id' => 1,
                'user_id' => 3003,
            ),
            441 => 
            array (
                'role_id' => 1,
                'user_id' => 3004,
            ),
            442 => 
            array (
                'role_id' => 10,
                'user_id' => 3004,
            ),
            443 => 
            array (
                'role_id' => 6,
                'user_id' => 3005,
            ),
            444 => 
            array (
                'role_id' => 10,
                'user_id' => 3005,
            ),
            445 => 
            array (
                'role_id' => 1,
                'user_id' => 3006,
            ),
            446 => 
            array (
                'role_id' => 10,
                'user_id' => 3006,
            ),
            447 => 
            array (
                'role_id' => 1,
                'user_id' => 3007,
            ),
            448 => 
            array (
                'role_id' => 10,
                'user_id' => 3007,
            ),
            449 => 
            array (
                'role_id' => 1,
                'user_id' => 3008,
            ),
            450 => 
            array (
                'role_id' => 10,
                'user_id' => 3008,
            ),
            451 => 
            array (
                'role_id' => 1,
                'user_id' => 3009,
            ),
            452 => 
            array (
                'role_id' => 10,
                'user_id' => 3009,
            ),
            453 => 
            array (
                'role_id' => 2,
                'user_id' => 3010,
            ),
            454 => 
            array (
                'role_id' => 10,
                'user_id' => 3010,
            ),
            455 => 
            array (
                'role_id' => 1,
                'user_id' => 3011,
            ),
            456 => 
            array (
                'role_id' => 10,
                'user_id' => 3011,
            ),
            457 => 
            array (
                'role_id' => 2,
                'user_id' => 3012,
            ),
            458 => 
            array (
                'role_id' => 10,
                'user_id' => 3012,
            ),
            459 => 
            array (
                'role_id' => 1,
                'user_id' => 3013,
            ),
            460 => 
            array (
                'role_id' => 10,
                'user_id' => 3013,
            ),
            461 => 
            array (
                'role_id' => 1,
                'user_id' => 3014,
            ),
            462 => 
            array (
                'role_id' => 10,
                'user_id' => 3014,
            ),
            463 => 
            array (
                'role_id' => 1,
                'user_id' => 3015,
            ),
            464 => 
            array (
                'role_id' => 10,
                'user_id' => 3015,
            ),
            465 => 
            array (
                'role_id' => 1,
                'user_id' => 3016,
            ),
            466 => 
            array (
                'role_id' => 10,
                'user_id' => 3016,
            ),
            467 => 
            array (
                'role_id' => 2,
                'user_id' => 3017,
            ),
            468 => 
            array (
                'role_id' => 10,
                'user_id' => 3017,
            ),
            469 => 
            array (
                'role_id' => 1,
                'user_id' => 3018,
            ),
            470 => 
            array (
                'role_id' => 10,
                'user_id' => 3018,
            ),
            471 => 
            array (
                'role_id' => 1,
                'user_id' => 3019,
            ),
            472 => 
            array (
                'role_id' => 10,
                'user_id' => 3019,
            ),
            473 => 
            array (
                'role_id' => 2,
                'user_id' => 3020,
            ),
            474 => 
            array (
                'role_id' => 10,
                'user_id' => 3020,
            ),
            475 => 
            array (
                'role_id' => 2,
                'user_id' => 3021,
            ),
            476 => 
            array (
                'role_id' => 10,
                'user_id' => 3021,
            ),
            477 => 
            array (
                'role_id' => 2,
                'user_id' => 3022,
            ),
            478 => 
            array (
                'role_id' => 10,
                'user_id' => 3022,
            ),
            479 => 
            array (
                'role_id' => 1,
                'user_id' => 3023,
            ),
            480 => 
            array (
                'role_id' => 10,
                'user_id' => 3023,
            ),
            481 => 
            array (
                'role_id' => 1,
                'user_id' => 3024,
            ),
            482 => 
            array (
                'role_id' => 10,
                'user_id' => 3024,
            ),
            483 => 
            array (
                'role_id' => 1,
                'user_id' => 3025,
            ),
            484 => 
            array (
                'role_id' => 10,
                'user_id' => 3025,
            ),
            485 => 
            array (
                'role_id' => 10,
                'user_id' => 3026,
            ),
            486 => 
            array (
                'role_id' => 15,
                'user_id' => 3026,
            ),
            487 => 
            array (
                'role_id' => 1,
                'user_id' => 3027,
            ),
            488 => 
            array (
                'role_id' => 10,
                'user_id' => 3027,
            ),
            489 => 
            array (
                'role_id' => 1,
                'user_id' => 3028,
            ),
            490 => 
            array (
                'role_id' => 10,
                'user_id' => 3028,
            ),
            491 => 
            array (
                'role_id' => 1,
                'user_id' => 3029,
            ),
            492 => 
            array (
                'role_id' => 10,
                'user_id' => 3029,
            ),
            493 => 
            array (
                'role_id' => 1,
                'user_id' => 3030,
            ),
            494 => 
            array (
                'role_id' => 10,
                'user_id' => 3030,
            ),
            495 => 
            array (
                'role_id' => 1,
                'user_id' => 3031,
            ),
            496 => 
            array (
                'role_id' => 10,
                'user_id' => 3031,
            ),
            497 => 
            array (
                'role_id' => 1,
                'user_id' => 3032,
            ),
            498 => 
            array (
                'role_id' => 10,
                'user_id' => 3032,
            ),
            499 => 
            array (
                'role_id' => 1,
                'user_id' => 3033,
            ),
        ));
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'role_id' => 10,
                'user_id' => 3033,
            ),
            1 => 
            array (
                'role_id' => 1,
                'user_id' => 3034,
            ),
            2 => 
            array (
                'role_id' => 10,
                'user_id' => 3034,
            ),
            3 => 
            array (
                'role_id' => 1,
                'user_id' => 3035,
            ),
            4 => 
            array (
                'role_id' => 10,
                'user_id' => 3035,
            ),
            5 => 
            array (
                'role_id' => 1,
                'user_id' => 3036,
            ),
            6 => 
            array (
                'role_id' => 10,
                'user_id' => 3036,
            ),
            7 => 
            array (
                'role_id' => 1,
                'user_id' => 3037,
            ),
            8 => 
            array (
                'role_id' => 10,
                'user_id' => 3037,
            ),
            9 => 
            array (
                'role_id' => 1,
                'user_id' => 3038,
            ),
            10 => 
            array (
                'role_id' => 10,
                'user_id' => 3038,
            ),
            11 => 
            array (
                'role_id' => 4,
                'user_id' => 3039,
            ),
            12 => 
            array (
                'role_id' => 10,
                'user_id' => 3039,
            ),
            13 => 
            array (
                'role_id' => 4,
                'user_id' => 3040,
            ),
            14 => 
            array (
                'role_id' => 10,
                'user_id' => 3040,
            ),
            15 => 
            array (
                'role_id' => 4,
                'user_id' => 3041,
            ),
            16 => 
            array (
                'role_id' => 10,
                'user_id' => 3041,
            ),
            17 => 
            array (
                'role_id' => 4,
                'user_id' => 3042,
            ),
            18 => 
            array (
                'role_id' => 10,
                'user_id' => 3042,
            ),
            19 => 
            array (
                'role_id' => 4,
                'user_id' => 3043,
            ),
            20 => 
            array (
                'role_id' => 10,
                'user_id' => 3043,
            ),
            21 => 
            array (
                'role_id' => 4,
                'user_id' => 3044,
            ),
            22 => 
            array (
                'role_id' => 10,
                'user_id' => 3044,
            ),
            23 => 
            array (
                'role_id' => 4,
                'user_id' => 3045,
            ),
            24 => 
            array (
                'role_id' => 10,
                'user_id' => 3045,
            ),
            25 => 
            array (
                'role_id' => 4,
                'user_id' => 3046,
            ),
            26 => 
            array (
                'role_id' => 10,
                'user_id' => 3046,
            ),
            27 => 
            array (
                'role_id' => 4,
                'user_id' => 3047,
            ),
            28 => 
            array (
                'role_id' => 10,
                'user_id' => 3047,
            ),
            29 => 
            array (
                'role_id' => 4,
                'user_id' => 3048,
            ),
            30 => 
            array (
                'role_id' => 10,
                'user_id' => 3048,
            ),
            31 => 
            array (
                'role_id' => 4,
                'user_id' => 3049,
            ),
            32 => 
            array (
                'role_id' => 10,
                'user_id' => 3049,
            ),
            33 => 
            array (
                'role_id' => 4,
                'user_id' => 3050,
            ),
            34 => 
            array (
                'role_id' => 10,
                'user_id' => 3050,
            ),
            35 => 
            array (
                'role_id' => 4,
                'user_id' => 3051,
            ),
            36 => 
            array (
                'role_id' => 10,
                'user_id' => 3051,
            ),
            37 => 
            array (
                'role_id' => 1,
                'user_id' => 3052,
            ),
            38 => 
            array (
                'role_id' => 10,
                'user_id' => 3052,
            ),
            39 => 
            array (
                'role_id' => 1,
                'user_id' => 3053,
            ),
            40 => 
            array (
                'role_id' => 10,
                'user_id' => 3053,
            ),
            41 => 
            array (
                'role_id' => 1,
                'user_id' => 3054,
            ),
            42 => 
            array (
                'role_id' => 10,
                'user_id' => 3054,
            ),
            43 => 
            array (
                'role_id' => 1,
                'user_id' => 3055,
            ),
            44 => 
            array (
                'role_id' => 1,
                'user_id' => 3056,
            ),
            45 => 
            array (
                'role_id' => 10,
                'user_id' => 3056,
            ),
            46 => 
            array (
                'role_id' => 1,
                'user_id' => 3057,
            ),
            47 => 
            array (
                'role_id' => 10,
                'user_id' => 3057,
            ),
            48 => 
            array (
                'role_id' => 1,
                'user_id' => 3058,
            ),
            49 => 
            array (
                'role_id' => 10,
                'user_id' => 3058,
            ),
            50 => 
            array (
                'role_id' => 1,
                'user_id' => 3059,
            ),
            51 => 
            array (
                'role_id' => 10,
                'user_id' => 3059,
            ),
            52 => 
            array (
                'role_id' => 1,
                'user_id' => 3060,
            ),
            53 => 
            array (
                'role_id' => 10,
                'user_id' => 3060,
            ),
            54 => 
            array (
                'role_id' => 1,
                'user_id' => 3061,
            ),
            55 => 
            array (
                'role_id' => 10,
                'user_id' => 3061,
            ),
            56 => 
            array (
                'role_id' => 1,
                'user_id' => 3062,
            ),
            57 => 
            array (
                'role_id' => 10,
                'user_id' => 3062,
            ),
            58 => 
            array (
                'role_id' => 1,
                'user_id' => 3063,
            ),
            59 => 
            array (
                'role_id' => 10,
                'user_id' => 3063,
            ),
            60 => 
            array (
                'role_id' => 1,
                'user_id' => 3064,
            ),
            61 => 
            array (
                'role_id' => 2,
                'user_id' => 3065,
            ),
            62 => 
            array (
                'role_id' => 10,
                'user_id' => 3065,
            ),
            63 => 
            array (
                'role_id' => 1,
                'user_id' => 3066,
            ),
            64 => 
            array (
                'role_id' => 10,
                'user_id' => 3066,
            ),
            65 => 
            array (
                'role_id' => 1,
                'user_id' => 3067,
            ),
            66 => 
            array (
                'role_id' => 10,
                'user_id' => 3067,
            ),
            67 => 
            array (
                'role_id' => 2,
                'user_id' => 3068,
            ),
            68 => 
            array (
                'role_id' => 10,
                'user_id' => 3068,
            ),
            69 => 
            array (
                'role_id' => 1,
                'user_id' => 3069,
            ),
            70 => 
            array (
                'role_id' => 10,
                'user_id' => 3069,
            ),
            71 => 
            array (
                'role_id' => 1,
                'user_id' => 3070,
            ),
            72 => 
            array (
                'role_id' => 10,
                'user_id' => 3070,
            ),
            73 => 
            array (
                'role_id' => 1,
                'user_id' => 3071,
            ),
            74 => 
            array (
                'role_id' => 10,
                'user_id' => 3071,
            ),
            75 => 
            array (
                'role_id' => 1,
                'user_id' => 3072,
            ),
            76 => 
            array (
                'role_id' => 10,
                'user_id' => 3072,
            ),
            77 => 
            array (
                'role_id' => 1,
                'user_id' => 3073,
            ),
            78 => 
            array (
                'role_id' => 10,
                'user_id' => 3073,
            ),
            79 => 
            array (
                'role_id' => 2,
                'user_id' => 3074,
            ),
            80 => 
            array (
                'role_id' => 10,
                'user_id' => 3074,
            ),
            81 => 
            array (
                'role_id' => 2,
                'user_id' => 3075,
            ),
            82 => 
            array (
                'role_id' => 10,
                'user_id' => 3075,
            ),
            83 => 
            array (
                'role_id' => 1,
                'user_id' => 3076,
            ),
            84 => 
            array (
                'role_id' => 10,
                'user_id' => 3076,
            ),
            85 => 
            array (
                'role_id' => 1,
                'user_id' => 3077,
            ),
            86 => 
            array (
                'role_id' => 10,
                'user_id' => 3077,
            ),
            87 => 
            array (
                'role_id' => 1,
                'user_id' => 3078,
            ),
            88 => 
            array (
                'role_id' => 10,
                'user_id' => 3078,
            ),
            89 => 
            array (
                'role_id' => 1,
                'user_id' => 3079,
            ),
            90 => 
            array (
                'role_id' => 10,
                'user_id' => 3079,
            ),
            91 => 
            array (
                'role_id' => 1,
                'user_id' => 3080,
            ),
            92 => 
            array (
                'role_id' => 10,
                'user_id' => 3080,
            ),
            93 => 
            array (
                'role_id' => 1,
                'user_id' => 3081,
            ),
            94 => 
            array (
                'role_id' => 10,
                'user_id' => 3081,
            ),
            95 => 
            array (
                'role_id' => 2,
                'user_id' => 3082,
            ),
            96 => 
            array (
                'role_id' => 10,
                'user_id' => 3082,
            ),
            97 => 
            array (
                'role_id' => 1,
                'user_id' => 3083,
            ),
            98 => 
            array (
                'role_id' => 10,
                'user_id' => 3083,
            ),
            99 => 
            array (
                'role_id' => 1,
                'user_id' => 3084,
            ),
            100 => 
            array (
                'role_id' => 10,
                'user_id' => 3084,
            ),
            101 => 
            array (
                'role_id' => 1,
                'user_id' => 3085,
            ),
            102 => 
            array (
                'role_id' => 10,
                'user_id' => 3085,
            ),
            103 => 
            array (
                'role_id' => 2,
                'user_id' => 3086,
            ),
            104 => 
            array (
                'role_id' => 10,
                'user_id' => 3086,
            ),
            105 => 
            array (
                'role_id' => 1,
                'user_id' => 3087,
            ),
            106 => 
            array (
                'role_id' => 10,
                'user_id' => 3087,
            ),
            107 => 
            array (
                'role_id' => 1,
                'user_id' => 3088,
            ),
            108 => 
            array (
                'role_id' => 10,
                'user_id' => 3088,
            ),
            109 => 
            array (
                'role_id' => 1,
                'user_id' => 3089,
            ),
            110 => 
            array (
                'role_id' => 10,
                'user_id' => 3089,
            ),
            111 => 
            array (
                'role_id' => 1,
                'user_id' => 3090,
            ),
            112 => 
            array (
                'role_id' => 10,
                'user_id' => 3090,
            ),
            113 => 
            array (
                'role_id' => 1,
                'user_id' => 3091,
            ),
            114 => 
            array (
                'role_id' => 10,
                'user_id' => 3091,
            ),
            115 => 
            array (
                'role_id' => 1,
                'user_id' => 3092,
            ),
            116 => 
            array (
                'role_id' => 10,
                'user_id' => 3092,
            ),
            117 => 
            array (
                'role_id' => 1,
                'user_id' => 3093,
            ),
            118 => 
            array (
                'role_id' => 10,
                'user_id' => 3093,
            ),
            119 => 
            array (
                'role_id' => 1,
                'user_id' => 3094,
            ),
            120 => 
            array (
                'role_id' => 10,
                'user_id' => 3094,
            ),
            121 => 
            array (
                'role_id' => 1,
                'user_id' => 3095,
            ),
            122 => 
            array (
                'role_id' => 10,
                'user_id' => 3095,
            ),
            123 => 
            array (
                'role_id' => 9,
                'user_id' => 3096,
            ),
            124 => 
            array (
                'role_id' => 10,
                'user_id' => 3096,
            ),
            125 => 
            array (
                'role_id' => 13,
                'user_id' => 3096,
            ),
            126 => 
            array (
                'role_id' => 1,
                'user_id' => 3097,
            ),
            127 => 
            array (
                'role_id' => 10,
                'user_id' => 3097,
            ),
            128 => 
            array (
                'role_id' => 1,
                'user_id' => 3098,
            ),
            129 => 
            array (
                'role_id' => 10,
                'user_id' => 3098,
            ),
            130 => 
            array (
                'role_id' => 1,
                'user_id' => 3099,
            ),
            131 => 
            array (
                'role_id' => 10,
                'user_id' => 3099,
            ),
            132 => 
            array (
                'role_id' => 1,
                'user_id' => 3100,
            ),
            133 => 
            array (
                'role_id' => 10,
                'user_id' => 3100,
            ),
            134 => 
            array (
                'role_id' => 1,
                'user_id' => 3101,
            ),
            135 => 
            array (
                'role_id' => 10,
                'user_id' => 3101,
            ),
            136 => 
            array (
                'role_id' => 1,
                'user_id' => 3102,
            ),
            137 => 
            array (
                'role_id' => 10,
                'user_id' => 3102,
            ),
            138 => 
            array (
                'role_id' => 1,
                'user_id' => 3103,
            ),
            139 => 
            array (
                'role_id' => 10,
                'user_id' => 3103,
            ),
            140 => 
            array (
                'role_id' => 1,
                'user_id' => 3104,
            ),
            141 => 
            array (
                'role_id' => 10,
                'user_id' => 3104,
            ),
            142 => 
            array (
                'role_id' => 1,
                'user_id' => 3105,
            ),
            143 => 
            array (
                'role_id' => 10,
                'user_id' => 3105,
            ),
            144 => 
            array (
                'role_id' => 1,
                'user_id' => 3106,
            ),
            145 => 
            array (
                'role_id' => 10,
                'user_id' => 3106,
            ),
            146 => 
            array (
                'role_id' => 1,
                'user_id' => 3107,
            ),
            147 => 
            array (
                'role_id' => 10,
                'user_id' => 3107,
            ),
            148 => 
            array (
                'role_id' => 1,
                'user_id' => 3108,
            ),
            149 => 
            array (
                'role_id' => 10,
                'user_id' => 3108,
            ),
            150 => 
            array (
                'role_id' => 2,
                'user_id' => 3109,
            ),
            151 => 
            array (
                'role_id' => 10,
                'user_id' => 3109,
            ),
            152 => 
            array (
                'role_id' => 1,
                'user_id' => 3110,
            ),
            153 => 
            array (
                'role_id' => 10,
                'user_id' => 3110,
            ),
            154 => 
            array (
                'role_id' => 1,
                'user_id' => 3111,
            ),
            155 => 
            array (
                'role_id' => 10,
                'user_id' => 3111,
            ),
            156 => 
            array (
                'role_id' => 2,
                'user_id' => 3112,
            ),
            157 => 
            array (
                'role_id' => 10,
                'user_id' => 3112,
            ),
            158 => 
            array (
                'role_id' => 1,
                'user_id' => 3113,
            ),
            159 => 
            array (
                'role_id' => 10,
                'user_id' => 3113,
            ),
            160 => 
            array (
                'role_id' => 1,
                'user_id' => 3114,
            ),
            161 => 
            array (
                'role_id' => 10,
                'user_id' => 3114,
            ),
            162 => 
            array (
                'role_id' => 1,
                'user_id' => 3115,
            ),
            163 => 
            array (
                'role_id' => 10,
                'user_id' => 3115,
            ),
            164 => 
            array (
                'role_id' => 2,
                'user_id' => 3116,
            ),
            165 => 
            array (
                'role_id' => 10,
                'user_id' => 3116,
            ),
            166 => 
            array (
                'role_id' => 1,
                'user_id' => 3117,
            ),
            167 => 
            array (
                'role_id' => 10,
                'user_id' => 3117,
            ),
            168 => 
            array (
                'role_id' => 1,
                'user_id' => 3118,
            ),
            169 => 
            array (
                'role_id' => 10,
                'user_id' => 3118,
            ),
            170 => 
            array (
                'role_id' => 1,
                'user_id' => 3119,
            ),
            171 => 
            array (
                'role_id' => 10,
                'user_id' => 3119,
            ),
            172 => 
            array (
                'role_id' => 1,
                'user_id' => 3120,
            ),
            173 => 
            array (
                'role_id' => 10,
                'user_id' => 3120,
            ),
            174 => 
            array (
                'role_id' => 1,
                'user_id' => 3121,
            ),
            175 => 
            array (
                'role_id' => 10,
                'user_id' => 3121,
            ),
            176 => 
            array (
                'role_id' => 1,
                'user_id' => 3122,
            ),
            177 => 
            array (
                'role_id' => 10,
                'user_id' => 3122,
            ),
            178 => 
            array (
                'role_id' => 2,
                'user_id' => 3123,
            ),
            179 => 
            array (
                'role_id' => 10,
                'user_id' => 3123,
            ),
            180 => 
            array (
                'role_id' => 1,
                'user_id' => 3124,
            ),
            181 => 
            array (
                'role_id' => 10,
                'user_id' => 3124,
            ),
            182 => 
            array (
                'role_id' => 1,
                'user_id' => 3125,
            ),
            183 => 
            array (
                'role_id' => 10,
                'user_id' => 3125,
            ),
            184 => 
            array (
                'role_id' => 1,
                'user_id' => 3126,
            ),
            185 => 
            array (
                'role_id' => 10,
                'user_id' => 3126,
            ),
            186 => 
            array (
                'role_id' => 1,
                'user_id' => 3127,
            ),
            187 => 
            array (
                'role_id' => 10,
                'user_id' => 3127,
            ),
            188 => 
            array (
                'role_id' => 1,
                'user_id' => 3128,
            ),
            189 => 
            array (
                'role_id' => 10,
                'user_id' => 3128,
            ),
            190 => 
            array (
                'role_id' => 1,
                'user_id' => 3129,
            ),
            191 => 
            array (
                'role_id' => 10,
                'user_id' => 3129,
            ),
            192 => 
            array (
                'role_id' => 1,
                'user_id' => 3130,
            ),
            193 => 
            array (
                'role_id' => 10,
                'user_id' => 3130,
            ),
            194 => 
            array (
                'role_id' => 1,
                'user_id' => 3131,
            ),
            195 => 
            array (
                'role_id' => 10,
                'user_id' => 3131,
            ),
            196 => 
            array (
                'role_id' => 1,
                'user_id' => 3132,
            ),
            197 => 
            array (
                'role_id' => 10,
                'user_id' => 3132,
            ),
            198 => 
            array (
                'role_id' => 1,
                'user_id' => 3133,
            ),
            199 => 
            array (
                'role_id' => 10,
                'user_id' => 3133,
            ),
            200 => 
            array (
                'role_id' => 1,
                'user_id' => 3134,
            ),
            201 => 
            array (
                'role_id' => 10,
                'user_id' => 3134,
            ),
            202 => 
            array (
                'role_id' => 1,
                'user_id' => 3135,
            ),
            203 => 
            array (
                'role_id' => 10,
                'user_id' => 3135,
            ),
            204 => 
            array (
                'role_id' => 1,
                'user_id' => 3136,
            ),
            205 => 
            array (
                'role_id' => 10,
                'user_id' => 3136,
            ),
            206 => 
            array (
                'role_id' => 1,
                'user_id' => 3137,
            ),
            207 => 
            array (
                'role_id' => 10,
                'user_id' => 3137,
            ),
            208 => 
            array (
                'role_id' => 1,
                'user_id' => 3138,
            ),
            209 => 
            array (
                'role_id' => 10,
                'user_id' => 3138,
            ),
            210 => 
            array (
                'role_id' => 1,
                'user_id' => 3139,
            ),
            211 => 
            array (
                'role_id' => 10,
                'user_id' => 3139,
            ),
            212 => 
            array (
                'role_id' => 1,
                'user_id' => 3140,
            ),
            213 => 
            array (
                'role_id' => 3,
                'user_id' => 3140,
            ),
            214 => 
            array (
                'role_id' => 1,
                'user_id' => 3141,
            ),
            215 => 
            array (
                'role_id' => 10,
                'user_id' => 3141,
            ),
            216 => 
            array (
                'role_id' => 2,
                'user_id' => 3142,
            ),
            217 => 
            array (
                'role_id' => 10,
                'user_id' => 3142,
            ),
            218 => 
            array (
                'role_id' => 1,
                'user_id' => 3143,
            ),
            219 => 
            array (
                'role_id' => 10,
                'user_id' => 3143,
            ),
            220 => 
            array (
                'role_id' => 1,
                'user_id' => 3144,
            ),
            221 => 
            array (
                'role_id' => 10,
                'user_id' => 3144,
            ),
            222 => 
            array (
                'role_id' => 1,
                'user_id' => 3145,
            ),
            223 => 
            array (
                'role_id' => 10,
                'user_id' => 3145,
            ),
            224 => 
            array (
                'role_id' => 1,
                'user_id' => 3146,
            ),
            225 => 
            array (
                'role_id' => 10,
                'user_id' => 3146,
            ),
            226 => 
            array (
                'role_id' => 1,
                'user_id' => 3147,
            ),
            227 => 
            array (
                'role_id' => 10,
                'user_id' => 3147,
            ),
            228 => 
            array (
                'role_id' => 1,
                'user_id' => 3148,
            ),
            229 => 
            array (
                'role_id' => 10,
                'user_id' => 3148,
            ),
            230 => 
            array (
                'role_id' => 1,
                'user_id' => 3149,
            ),
            231 => 
            array (
                'role_id' => 10,
                'user_id' => 3149,
            ),
            232 => 
            array (
                'role_id' => 1,
                'user_id' => 3150,
            ),
            233 => 
            array (
                'role_id' => 10,
                'user_id' => 3150,
            ),
            234 => 
            array (
                'role_id' => 1,
                'user_id' => 3151,
            ),
            235 => 
            array (
                'role_id' => 10,
                'user_id' => 3151,
            ),
            236 => 
            array (
                'role_id' => 1,
                'user_id' => 3152,
            ),
            237 => 
            array (
                'role_id' => 10,
                'user_id' => 3152,
            ),
            238 => 
            array (
                'role_id' => 1,
                'user_id' => 3153,
            ),
            239 => 
            array (
                'role_id' => 10,
                'user_id' => 3153,
            ),
            240 => 
            array (
                'role_id' => 1,
                'user_id' => 3154,
            ),
            241 => 
            array (
                'role_id' => 10,
                'user_id' => 3154,
            ),
            242 => 
            array (
                'role_id' => 1,
                'user_id' => 3155,
            ),
            243 => 
            array (
                'role_id' => 3,
                'user_id' => 3155,
            ),
            244 => 
            array (
                'role_id' => 1,
                'user_id' => 3156,
            ),
            245 => 
            array (
                'role_id' => 3,
                'user_id' => 3156,
            ),
            246 => 
            array (
                'role_id' => 1,
                'user_id' => 3157,
            ),
            247 => 
            array (
                'role_id' => 10,
                'user_id' => 3157,
            ),
            248 => 
            array (
                'role_id' => 1,
                'user_id' => 3158,
            ),
            249 => 
            array (
                'role_id' => 10,
                'user_id' => 3158,
            ),
            250 => 
            array (
                'role_id' => 1,
                'user_id' => 3159,
            ),
            251 => 
            array (
                'role_id' => 10,
                'user_id' => 3159,
            ),
            252 => 
            array (
                'role_id' => 1,
                'user_id' => 3160,
            ),
            253 => 
            array (
                'role_id' => 10,
                'user_id' => 3160,
            ),
            254 => 
            array (
                'role_id' => 2,
                'user_id' => 3161,
            ),
            255 => 
            array (
                'role_id' => 10,
                'user_id' => 3161,
            ),
            256 => 
            array (
                'role_id' => 2,
                'user_id' => 3162,
            ),
            257 => 
            array (
                'role_id' => 10,
                'user_id' => 3162,
            ),
            258 => 
            array (
                'role_id' => 1,
                'user_id' => 3163,
            ),
            259 => 
            array (
                'role_id' => 10,
                'user_id' => 3163,
            ),
            260 => 
            array (
                'role_id' => 1,
                'user_id' => 3164,
            ),
            261 => 
            array (
                'role_id' => 10,
                'user_id' => 3164,
            ),
            262 => 
            array (
                'role_id' => 1,
                'user_id' => 3165,
            ),
            263 => 
            array (
                'role_id' => 10,
                'user_id' => 3165,
            ),
            264 => 
            array (
                'role_id' => 1,
                'user_id' => 3166,
            ),
            265 => 
            array (
                'role_id' => 10,
                'user_id' => 3166,
            ),
            266 => 
            array (
                'role_id' => 1,
                'user_id' => 3167,
            ),
            267 => 
            array (
                'role_id' => 10,
                'user_id' => 3167,
            ),
            268 => 
            array (
                'role_id' => 1,
                'user_id' => 3168,
            ),
            269 => 
            array (
                'role_id' => 10,
                'user_id' => 3168,
            ),
            270 => 
            array (
                'role_id' => 1,
                'user_id' => 3169,
            ),
            271 => 
            array (
                'role_id' => 10,
                'user_id' => 3169,
            ),
            272 => 
            array (
                'role_id' => 1,
                'user_id' => 3170,
            ),
            273 => 
            array (
                'role_id' => 10,
                'user_id' => 3170,
            ),
            274 => 
            array (
                'role_id' => 1,
                'user_id' => 3171,
            ),
            275 => 
            array (
                'role_id' => 10,
                'user_id' => 3171,
            ),
            276 => 
            array (
                'role_id' => 2,
                'user_id' => 3172,
            ),
            277 => 
            array (
                'role_id' => 10,
                'user_id' => 3172,
            ),
            278 => 
            array (
                'role_id' => 1,
                'user_id' => 3173,
            ),
            279 => 
            array (
                'role_id' => 10,
                'user_id' => 3173,
            ),
            280 => 
            array (
                'role_id' => 1,
                'user_id' => 3174,
            ),
            281 => 
            array (
                'role_id' => 10,
                'user_id' => 3174,
            ),
            282 => 
            array (
                'role_id' => 1,
                'user_id' => 3175,
            ),
            283 => 
            array (
                'role_id' => 10,
                'user_id' => 3175,
            ),
            284 => 
            array (
                'role_id' => 1,
                'user_id' => 3176,
            ),
            285 => 
            array (
                'role_id' => 10,
                'user_id' => 3176,
            ),
            286 => 
            array (
                'role_id' => 1,
                'user_id' => 3177,
            ),
            287 => 
            array (
                'role_id' => 10,
                'user_id' => 3177,
            ),
            288 => 
            array (
                'role_id' => 1,
                'user_id' => 3178,
            ),
            289 => 
            array (
                'role_id' => 10,
                'user_id' => 3178,
            ),
            290 => 
            array (
                'role_id' => 1,
                'user_id' => 3179,
            ),
            291 => 
            array (
                'role_id' => 10,
                'user_id' => 3179,
            ),
            292 => 
            array (
                'role_id' => 1,
                'user_id' => 3180,
            ),
            293 => 
            array (
                'role_id' => 10,
                'user_id' => 3180,
            ),
            294 => 
            array (
                'role_id' => 1,
                'user_id' => 3181,
            ),
            295 => 
            array (
                'role_id' => 10,
                'user_id' => 3181,
            ),
            296 => 
            array (
                'role_id' => 1,
                'user_id' => 3182,
            ),
            297 => 
            array (
                'role_id' => 10,
                'user_id' => 3182,
            ),
            298 => 
            array (
                'role_id' => 1,
                'user_id' => 3183,
            ),
            299 => 
            array (
                'role_id' => 10,
                'user_id' => 3183,
            ),
            300 => 
            array (
                'role_id' => 1,
                'user_id' => 3184,
            ),
            301 => 
            array (
                'role_id' => 10,
                'user_id' => 3184,
            ),
            302 => 
            array (
                'role_id' => 1,
                'user_id' => 3185,
            ),
            303 => 
            array (
                'role_id' => 10,
                'user_id' => 3185,
            ),
            304 => 
            array (
                'role_id' => 1,
                'user_id' => 3186,
            ),
            305 => 
            array (
                'role_id' => 10,
                'user_id' => 3186,
            ),
            306 => 
            array (
                'role_id' => 1,
                'user_id' => 3187,
            ),
            307 => 
            array (
                'role_id' => 10,
                'user_id' => 3187,
            ),
        ));
        
        
    }
}