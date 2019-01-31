<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('en_NI');

        $companies = DB::connection('legacy')->select('SELECT * FROM company');

        // Loop through each of the results
        foreach ($companies as $company) {

            $create = true;

            // Only import redundant company if there are any shipment records
            if ($company->IFSDepot == 'REDUN') {

                // Count the number of shipments raised by this company
                $countUkShipments = DB::connection('legacy')->select('SELECT COUNT(*) AS cnt FROM FUKShipment WHERE compID = :id AND complete != "C" AND id > 357320', ['id' => $company->company]);
                $countIntlShipments = DB::connection('legacy')->select('SELECT COUNT(*) AS cnt FROM FX_Header WHERE compID = :id AND complete != "C" AND TxType != "LAR" AND id > 356715', ['id' => $company->company]);

                $totalShipments = $countUkShipments{0}->cnt + $countIntlShipments{0}->cnt;

                // Only create a record if the company has shipped before
                if ($totalShipments <= 0) {
                    $create = false;
                }
            }

            if ($create) {

                $enabled = 1;
                $testing = 0;

                if ($company->IFSDepot == 'REDUN') {
                    $enabled = 0;
                    $testing = 1;
                }

                $companyText = $str = preg_replace('/\\s+/', '', $company->Compname);
                $companyCode = str_random(6);

                $vatExempt = 0;

                if ($company->vat_exempt == 'y' || $company->vat_exempt == 'Y') {
                    $vatExempt = 1;
                }

                // Set the label format
                switch ($company->FXLabelFormat) {
                    case 'label6x4':
                        $printFormat = 2;
                        break;
                    case 'labelFDX':
                        $printFormat = 4;
                        break;
                    default:
                        $printFormat = 1;
                        break;
                }

                // Set the sales ID
                switch ($company->FXSales) {
                    case 'house':
                        $saleId = 1;
                        break;
                    case 'gheaney':
                        $saleId = 2;
                        break;
                    case 'mjohnston':
                        $saleId = 3;
                        break;
                    case 'ghanna':
                        $saleId = 4;
                        break;
                    default:
                        $saleId = 1;
                        break;
                }

                // Set the depot ID
                switch ($company->IFSDepot) {
                    case 'ANT':
                        $depotId = 1;
                        break;
                    case 'LON':
                        $depotId = 2;
                        break;
                    case 'NYC':
                        $depotId = 3;
                        break;
                    case 'REDUN':
                        $depotId = 4;
                        break;
                    default:
                        $depotId = 1;
                        break;
                }


                // Tidy up the county field
                switch ($company->county) {
                    case stristr($company->county, 'ant'):
                        $state = 'County Antrim';
                        break;
                    case stristr($company->county, 'armagh'):
                        $state = 'County Armagh';
                        break;
                    case stristr($company->county, 'down'):
                        $state = 'County Down';
                        break;
                    case stristr($company->county, 'ferman'):
                        $state = 'County Fermanagh';
                        break;
                    case stristr($company->county, 'derry'):
                        $state = 'County Londonderry';
                        break;
                    case stristr($company->county, 'tyrone'):
                        $state = 'County Tyrone';
                        break;
                    default:
                        $state = $company->county;
                        break;
                }

                // Split 2 line address into 4 lines
                $addressLines = [];
                $addressString = $company->building . ', ' . $company->street . ', ' . $company->town;
                $pieces = explode(', ', $addressString);

                $i = 0;
                foreach ($pieces as $value) {
                    if (strlen($value) > 0) {
                        $addressLines[$i] = $value;
                    }
                    $i++;
                }

                $city = last($addressLines);

                for ($i = 0; $i <= 3; $i++) {
                    if (!isset($addressLines[$i])) {
                        $addressLines[$i] = '';
                    }

                    if ($addressLines[$i] == $city) {
                        $addressLines[$i] = '';
                    }
                }

                // Create a site name
                //$arr = explode(' ',trim($company->Compname));
                //$siteName = ucwords(strtolower($arr[0] . ' ' . $city));
                $siteName = $company->Compname;

                // Build the array to create the company record
                $array = [
                    'id' => $company->company,
                    'company_code' => $companyCode,
                    'site_name' => $siteName,
                    'company_name' => $company->Compname,
                    'address1' => $addressLines[0],
                    'address2' => $addressLines[1],
                    'address3' => $addressLines[2],
                    'city' => ucwords($city),
                    'state' => $state,
                    'postcode' => strtoupper($company->postcode),
                    'country_code' => $company->country_code,
                    'telephone' => str_replace(' ', '', $company->phone),
                    'email' => $company->email,
                    'scs_code' => $company->SCSCode,
                    'carrier_choice' => 'user',
                    'vat_exempt' => $vatExempt,
                    'enabled' => $enabled,
                    'testing' => $testing,
                    'print_format_id' => $printFormat,
                    'sale_id' => $saleId,
                    'depot_id' => $depotId,
                    'localisation_id' => 1
                ];

                // Save the company record
                $company = App\Company::create($array);

                // Load all active users for the company from the legacy database
                $users = DB::connection('legacy')->select('select * from Users where Company = :id AND expiry_date != "0000-00-00" GROUP BY Email', ['id' => $company->id]);

                // Loop through each of the records
                foreach ($users as $user) :

                    if (isset($user->Email) && isset($user->Name) && filter_var($user->Email, FILTER_VALIDATE_EMAIL)) {

                        $cutOff = strtotime("-2 years", time());
                        $expiry = strtotime($user->expiry_date);

                        if ($expiry > $cutOff) {

                            if (strlen($user->Phone) > 5 && strlen($user->Phone) <= 13) {
                                $telephone = $user->Phone;
                            } else {
                                $telephone = $company->telephone;
                            }

                            $array = [
                                'id' => $user->AID,
                                'name' => $user->Name,
                                'email' => $user->Email,
                                'telephone' => str_replace(' ', '', $telephone),
                                'password' => bcrypt($user->Pword),
                                'remember_token' => '',
                                'api_token' => $faker->sha1,
                                'enabled' => 1,
                                'localisation_id' => 1,
                                'print_format_id' => $printFormat,
                            ];

                            $user = App\User::firstOrCreate($array);

                            $user->companies()->save($company);

                            if ($user->id == 195 || $user->id == 85) {
                                $user->assignRole('ifsa');
                            } elseif ($user->id == 176 || $user->id == 116) {
                                $user->assignRole('ifsc');
                            } else {
                                if ($company->id == 4) {
                                    $user->assignRole('ifsu');
                                    $user->assignRole('courier');
                                } else {
                                    $user->assignRole('cust');
                                    // Enable all users with courier mode
                                    $user->assignRole('courier');
                                }
                            }
                        }
                    }

                endforeach;
            }
        }

        $user = App\User::find(85);
        $user->api_token = '46f01fd0cb61d6e072983683bdef4c710b8b9b41';
        $user->save();

        // ***************************************************************************************************************//
        // ************************************************ Douglas & Grahame ********************************************//
        // ***************************************************************************************************************//

        $company = App\Company::find(153);
        $company->company_code = "DGDEV";
        $company->carrier_choice = "user";
        $company->print_format_id = 1;
        $company->depot_id = '1';
        $company->localisation_id = 1;
        $company->save();

        $user = App\User::find(395);
        $user->api_token = '46f03fd0cb61d7f072983683bdef4c630b8b9b41';
        $user->save();

        // ***************************************************************************************************************//
        // ************************************************ Glen Distribution ********************************************//
        // ***************************************************************************************************************//

        $company = App\Company::find(113);
        $company->company_code = "GDCDEV";
        $company->carrier_choice = "user";
        $company->print_format_id = 1;
        $company->depot_id = '1';
        $company->localisation_id = 1;
        $company->save();

        $user = App\User::find(1291);
        $user->api_token = '16f01ed0cb61d6e472983683bdcf4c71bb8b9d41';
        $user->save();

        // ***************************************************************************************************************//
        // ****************************************************** Terex **************************************************//
        // ***************************************************************************************************************//

        $company = App\Company::find(602);
        $company->company_code = "TEREXDEV";
        $company->carrier_choice = "user";
        $company->print_format_id = 1;
        $company->depot_id = '1';
        $company->localisation_id = 1;
        $company->save();

        $user = App\User::find(1663);
        $user->api_token = '26f01fd1fb61d6e074583683bdef4b210b8b9b35';
        $user->save();

        // ***************************************************************************************************************//
        // ****************************** COMPANY HAS NO ACTIVE USER ACCOUNTS - SET TO REDUNDANT *************************//
        // ***************************************************************************************************************//

        $companies = App\Company::all();

        foreach ($companies as $company) {
            if ($company->users()->count() <= 0) {
                $company->enabled = 0;
                $company->testing = 1;
                $company->depot_id = 4;
                $company->save();
            }
        }
    }

}
