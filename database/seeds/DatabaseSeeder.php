<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            AddChargesTableSeeder::class,
            CarriersTableSeeder::class,
            ChargeCodesTableSeeder::class,
            CommoditiesTableSeeder::class,
            CompaniesTableSeeder::class,
            CountriesTableSeeder::class,
            CPCTableSeeder::class,
            CurrenciesTableSeeder::class,
            DepartmentsTableSeeder::class,
            DepotsTableSeeder::class,
            // DomesticPurchaseInvoicesTableSeeder::class,
            DomesticZonesTableSeeder::class,
            DriversTableSeeder::class,
            FuelSurchargesTableSeeder::class,
            HazardsTableSeeder::class,
            IfsNdPostcodesTableSeeder::class,
            ImportConfigFieldsTableSeeder::class,
            ImportConfigsTableSeeder::class,
            LocalisationsTableSeeder::class,
            MailReportsTableSeeder::class,
            ManifestProfilesTableSeeder::class,
            ModesTableSeeder::class,
            PackagingTableSeeder::class,
            PermissionsTableSeeder::class,
            PrintFormatsTableSeeder::class,
            ProblemEventsTableSeeder::class,
            ReportsTableSeeder::class,
            RolesTableSeeder::class,
            RoutesTableSeeder::class,
            SalesTableSeeder::class,
            SequencesTableSeeder::class,
            ShipReasonsTableSeeder::class,
            ShipmentsTableSeeder::class,
            StatesTableSeeder::class,
            StatusesTableSeeder::class,
            SurchargesTableSeeder::class,
            TermsTableSeeder::class,
            TrackingTableSeeder::class,
            TransendCodesTableSeeder::class,
            UomsTableSeeder::class,
            UsersTableSeeder::class,
            VatCodesTableSeeder::class,
            VehiclesTableSeeder::class
        ]);

        // Unnecessary but explicitly reset Foreign Key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Eloquent::reguard();

        $this->call([
            AddChargeDetailsTableSeeder::class,
            AddressesTableSeeder::class,
            CarrierAccountsTableSeeder::class,
            CarrierChargeCodesTableSeeder::class,
            CarrierPackagingTypesTableSeeder::class,
            DomesticRatesTableSeeder::class,
            ServicesTableSeeder::class,
            // PermissionsRolesSeeder::class,
            // RoleUserTableSeeder::class,
            PermissionRoleTableSeeder::class,
            // SpecialServicesTableSeeder::class,
        ]);
    }
}
