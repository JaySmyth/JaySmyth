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

        $this->call(PermissionsRolesSeeder::class);
        $this->call(LocalisationsTableSeeder::class);
        $this->call(SalesTableSeeder::class);
        $this->call(SequencesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(PrintFormatsTableSeeder::class);
        $this->call(RoutesTableSeeder::class);
        $this->call(DepotsTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(ModesTableSeeder::class);
        $this->call(TermsTableSeeder::class);
        $this->call(VatCodesTableSeeder::class);
        $this->call(CarriersTableSeeder::class);
        $this->call(PackagingTableSeeder::class);
        $this->call(CarrierAccountsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(HazardsTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(UomsTableSeeder::class);
        $this->call(ManifestProfilesTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(ProblemEventsTableSeeder::class);
        $this->call(CPCTableSeeder::class);
        $this->call(CarrierChargeCodesTableSeeder::class);
        $this->call(MailReportsTableSeeder::class);

        // *** Seed from legacy data *** //
        $this->call(CompaniesTableSeeder::class);

        // Unnecessary but explicitly reset Foreign Key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Eloquent::reguard();
    }
}
