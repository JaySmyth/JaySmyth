<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            ['code' => 'AL', 'alpha_code' => 'ALABAMA', 'name' => 'Alabama', 'country_code' => 'US'],
            ['code' => 'AR', 'alpha_code' => 'ARKANSAS', 'name' => 'Arkansas', 'country_code' => 'US'],
            ['code' => 'CT', 'alpha_code' => 'CONNECTICUT', 'name' => 'Connecticut', 'country_code' => 'US'],
            ['code' => 'FL', 'alpha_code' => 'FLORIDA', 'name' => 'Florida', 'country_code' => 'US'],
            ['code' => 'ID', 'alpha_code' => 'IDAHO', 'name' => 'Idaho', 'country_code' => 'US'],
            ['code' => 'IA', 'alpha_code' => 'IOWA', 'name' => 'Iowa', 'country_code' => 'US'],
            ['code' => 'LA', 'alpha_code' => 'LOUISIANA', 'name' => 'Louisiana', 'country_code' => 'US'],
            ['code' => 'MA', 'alpha_code' => 'MASSACHUSETTS', 'name' => 'Massachusetts', 'country_code' => 'US'],
            ['code' => 'MS', 'alpha_code' => 'MISSISSIPPI', 'name' => 'Mississippi', 'country_code' => 'US'],
            ['code' => 'NE', 'alpha_code' => 'NEBRASKA', 'name' => 'Nebraska', 'country_code' => 'US'],
            ['code' => 'NJ', 'alpha_code' => 'NEWJERSEY', 'name' => 'New Jersey', 'country_code' => 'US'],
            ['code' => 'NC', 'alpha_code' => 'NORTHCAROLINA', 'name' => 'North Carolina', 'country_code' => 'US'],
            ['code' => 'OK', 'alpha_code' => 'OKLAHOMA', 'name' => 'Oklahoma', 'country_code' => 'US'],
            ['code' => 'RI', 'alpha_code' => 'RHODEISLAND', 'name' => 'Rhode Island', 'country_code' => 'US'],
            ['code' => 'TN', 'alpha_code' => 'TENNESSEE', 'name' => 'Tennessee', 'country_code' => 'US'],
            ['code' => 'VT', 'alpha_code' => 'VERMONT', 'name' => 'Vermont', 'country_code' => 'US'],
            ['code' => 'WV', 'alpha_code' => 'WESTVIRGINIA', 'name' => 'West Virginia', 'country_code' => 'US'],
            ['code' => 'AK', 'alpha_code' => 'ALASKA', 'name' => 'Alaska', 'country_code' => 'US'],
            ['code' => 'CA', 'alpha_code' => 'CALIFORNIA', 'name' => 'California', 'country_code' => 'US'],
            ['code' => 'DC', 'alpha_code' => 'DISTCOLUMBIA', 'name' => 'Dist. Columbia', 'country_code' => 'US'],
            ['code' => 'GA', 'alpha_code' => 'GEORGIA', 'name' => 'Georgia', 'country_code' => 'US'],
            ['code' => 'IL', 'alpha_code' => 'ILLINOIS', 'name' => 'Illinois', 'country_code' => 'US'],
            ['code' => 'KS', 'alpha_code' => 'KANSAS', 'name' => 'Kansas', 'country_code' => 'US'],
            ['code' => 'ME', 'alpha_code' => 'MAINE', 'name' => 'Maine', 'country_code' => 'US'],
            ['code' => 'MI', 'alpha_code' => 'MICHIGAN', 'name' => 'Michigan', 'country_code' => 'US'],
            ['code' => 'MO', 'alpha_code' => 'MISSOURI', 'name' => 'Missouri', 'country_code' => 'US'],
            ['code' => 'NV', 'alpha_code' => 'NEVADA', 'name' => 'Nevada', 'country_code' => 'US'],
            ['code' => 'NM', 'alpha_code' => 'NEWMEXICO', 'name' => 'New Mexico', 'country_code' => 'US'],
            ['code' => 'ND', 'alpha_code' => 'NORTHDAKOTA', 'name' => 'North Dakota', 'country_code' => 'US'],
            ['code' => 'OR', 'alpha_code' => 'OREGON', 'name' => 'Oregon', 'country_code' => 'US'],
            ['code' => 'SC', 'alpha_code' => 'SOUTHCAROLINA', 'name' => 'South Carolina', 'country_code' => 'US'],
            ['code' => 'TX', 'alpha_code' => 'TEXAS', 'name' => 'Texas', 'country_code' => 'US'],
            ['code' => 'VA', 'alpha_code' => 'VIRGINIA', 'name' => 'Virginia', 'country_code' => 'US'],
            ['code' => 'WI', 'alpha_code' => 'WISCONSIN', 'name' => 'Wisconsin', 'country_code' => 'US'],
            ['code' => 'AZ', 'alpha_code' => 'ARIZONA', 'name' => 'Arizona', 'country_code' => 'US'],
            ['code' => 'CO', 'alpha_code' => 'COLORADO', 'name' => 'Colorado', 'country_code' => 'US'],
            ['code' => 'DE', 'alpha_code' => 'DELAWARE', 'name' => 'Delaware', 'country_code' => 'US'],
            ['code' => 'HI', 'alpha_code' => 'HAWAII', 'name' => 'Hawaii', 'country_code' => 'US'],
            ['code' => 'IN', 'alpha_code' => 'INDIANA', 'name' => 'Indiana', 'country_code' => 'US'],
            ['code' => 'KY', 'alpha_code' => 'KENTUCKY', 'name' => 'Kentucky', 'country_code' => 'US'],
            ['code' => 'MD', 'alpha_code' => 'MARYLAND', 'name' => 'Maryland', 'country_code' => 'US'],
            ['code' => 'MN', 'alpha_code' => 'MINNESOTA', 'name' => 'Minnesota', 'country_code' => 'US'],
            ['code' => 'MT', 'alpha_code' => 'MONTANA', 'name' => 'Montana', 'country_code' => 'US'],
            ['code' => 'NH', 'alpha_code' => 'NEWHAMPSHIRE', 'name' => 'New Hampshire', 'country_code' => 'US'],
            ['code' => 'NY', 'alpha_code' => 'NEWYORK', 'name' => 'New York', 'country_code' => 'US'],
            ['code' => 'OH', 'alpha_code' => 'OHIO', 'name' => 'Ohio', 'country_code' => 'US'],
            ['code' => 'PA', 'alpha_code' => 'PENNSYLVANIA', 'name' => 'Pennsylvania', 'country_code' => 'US'],
            ['code' => 'SD', 'alpha_code' => 'SOUTHDAKOTA', 'name' => 'South Dakota', 'country_code' => 'US'],
            ['code' => 'UT', 'alpha_code' => 'UTAH', 'name' => 'Utah', 'country_code' => 'US'],
            ['code' => 'WA', 'alpha_code' => 'WASHINGTON', 'name' => 'Washington', 'country_code' => 'US'],
            ['code' => 'WY', 'alpha_code' => 'WYOMING', 'name' => 'Wyoming', 'country_code' => 'US'],
            ['code' => 'AB', 'alpha_code' => 'ALBERTA', 'name' => 'Alberta', 'country_code' => 'CA'],
            ['code' => 'LB', 'alpha_code' => 'LABRADOR', 'name' => 'Labrador', 'country_code' => 'CA'],
            ['code' => 'NB', 'alpha_code' => 'NEWBRUNSWICK', 'name' => 'New Brunswick', 'country_code' => 'CA'],
            ['code' => 'NS', 'alpha_code' => 'NOVASCOTIA', 'name' => 'Nova Scotia', 'country_code' => 'CA'],
            ['code' => 'NW', 'alpha_code' => 'NORTHWESTTERR', 'name' => 'North West Terr.', 'country_code' => 'CA'],
            ['code' => 'PE', 'alpha_code' => 'PRINCEEDWARDIS', 'name' => 'Prince Edward Is.', 'country_code' => 'CA'],
            ['code' => 'SK', 'alpha_code' => 'SASKATCHEWEN', 'name' => 'Saskatchewen', 'country_code' => 'CA'],
            ['code' => 'BC', 'alpha_code' => 'BRITISHCOLUMBIA', 'name' => 'British Columbia', 'country_code' => 'CA'],
            ['code' => 'MB', 'alpha_code' => 'MANITOBA', 'name' => 'Manitoba', 'country_code' => 'CA'],
            ['code' => 'NF', 'alpha_code' => 'NEWFOUNDLAND', 'name' => 'Newfoundland', 'country_code' => 'CA'],
            ['code' => 'NU', 'alpha_code' => 'NUNAVUT', 'name' => 'Nunavut', 'country_code' => 'CA'],
            ['code' => 'ON', 'alpha_code' => 'ONTARIO', 'name' => 'Ontario', 'country_code' => 'CA'],
            ['code' => 'QC', 'alpha_code' => 'QUEBEC', 'name' => 'Quebec', 'country_code' => 'CA'],
            ['code' => 'YU', 'alpha_code' => 'YUKON', 'name' => 'Yukon', 'country_code' => 'CA'],
            ['code' => 'PR', 'alpha_code' => 'PUERTORICO', 'name' => 'Puerto Rico', 'country_code' => 'PR'],
        ]);
    }

}
