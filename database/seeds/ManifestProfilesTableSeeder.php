<?php

use Illuminate\Database\Seeder;

class ManifestProfilesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manifest_profiles')->insert([
            ['name' => 'FedEx International', 'prefix' => 'FDXI', 'mode_id' => 1, 'carrier_id' => 2, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'FedEx International (BFS)', 'prefix' => 'FDXI', 'mode_id' => 1, 'carrier_id' => 2, 'route_id' => '2', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'FedEx Domestic', 'prefix' => 'FDXD', 'mode_id' => 1, 'carrier_id' => 2, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'UPS International', 'prefix' => 'UPSI', 'mode_id' => 1, 'carrier_id' => 3, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'UPS 24HR Domestic', 'prefix' => 'UPSD', 'mode_id' => 1, 'carrier_id' => 3, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'UPS 24HR Domestic (BFS)', 'prefix' => 'UPSD', 'mode_id' => 1, 'carrier_id' => 3, 'route_id' => '2', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'IFS Local', 'prefix' => 'IFSL', 'mode_id' => 1, 'carrier_id' => 1, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'IFS ROI', 'prefix' => 'IFSR', 'mode_id' => 1, 'carrier_id' => 1, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'IFS Pallet', 'prefix' => 'IFSP', 'mode_id' => 1, 'carrier_id' => 1, 'route_id' => '1', 'depot_id' => 1, 'auto' => 0, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'Royal Mail', 'prefix' => 'RM', 'mode_id' => 1, 'carrier_id' => 6, 'route_id' => '1', 'depot_id' => 1, 'auto' => 1, 'time' => '17:30', 'upload_required' => 0, 'last_run' => null],
            ['name' => 'TNT Domestic', 'prefix' => 'TNTD', 'mode_id' => 1, 'carrier_id' => 4, 'route_id' => null, 'depot_id' => 1, 'auto' => 1, 'time' => '18:30', 'upload_required' => 1, 'last_run' => null],
            ['name' => 'TNT International', 'prefix' => 'TNTI', 'mode_id' => 1, 'carrier_id' => 4, 'route_id' => null, 'depot_id' => 1, 'auto' => 1, 'time' => '18:30', 'upload_required' => 1, 'last_run' => null],
            ['name' => 'Air Freight', 'prefix' => 'AF', 'mode_id' => 2, 'carrier_id' => 1, 'route_id' => null, 'depot_id' => 1, 'auto' => 1, 'time' => '18:30', 'upload_required' => 0, 'last_run' => null],
        ]);
    }

}
