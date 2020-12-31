<?php

namespace App\Console\Commands\Brexit;

use App\Models\CompanyRates;
use App\Models\CompanyService;
use App\Models\Service;
use App\Models\RateDiscount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpgradeDhlEsxService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upgrade-dhl-esx-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade DHL ESX Service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Change ESU Services
        $services = Service::where('carrier_code', 'ESU')->get();
        foreach ($services as $service) {
            $service->carrier_name = 'DHL Economy Select (nondoc)';
            $service->carrier_code = 'ESI';
            $service->parameters = 'H,I';
            $service->save();
        }

        // Change ECX Services
        $services = Service::where('carrier_code', 'ECX')->get();
        foreach ($services as $service) {

            // Add the new service
            $newId = $this->addServices($service);

            // Make services available to all existing users of the ESX service
            $this->addServiceToCompany($service->id, $newId);

            // duplicate existing pricing for the new services.
            $this->duplicatePricing($service->id, $newId);
        }
    }

    protected function addServices($service)
    {
        $ids = [];
        $data = $service->toArray();

        // Modify existing service
        $service->carrier_name = 'DHL Express Worldwide (Docs)';
        $service->carrier_code = 'DOC';
        $service->parameters = 'D,D';
        $service->doc = 1;
        $service->nondoc = '0';
        $service->save();

        // Create new service
        $data['carrier_name'] = 'DHL Express Worldwide (NonDoc)';
        $data['carrier_code'] = 'WPX';
        $data['parameters'] = 'P,P';
        $data['doc'] = '0';
        $data['nondoc'] = '1';
        $newService = Service::create($data);

        return $newService->id;
    }

    // Loop through companies with old service and add the new one
    protected function addServiceToCompany($oldServiceId, $newServiceId)
    {
        $companyServices = CompanyService::where('service_id', $oldServiceId)->get();
        foreach ($companyServices as $companyService) {
            $data = $companyService->toArray();
            $data['service_id'] = $newServiceId;
            $newCompanyService = CompanyService::create($data);
        }
    }

    protected function duplicatePricing($existingServiceId, $newServiceId)
    {
        $companyRates = CompanyRates::where('service_id', $existingServiceId)->get();
        foreach ($companyRates as $companyRate) {
            $data = $companyRate->toArray();
            $data['service_id'] = $newServiceId;
            CompanyRates::create($data);
            $this->duplicateDiscounts($data['company_id'], $existingServiceId, $newServiceId);
        }
    }

    protected function duplicateDiscounts($companyId, $oldServiceId, $newServiceId)
    {
        $today = '2021-01-05';
        $discounts = RateDiscount::where('company_id', $companyId)->where('service_id', $oldServiceId)->where('to_date', '>', $today)->get();
        foreach ($discounts as $discount) {
            $data = $discount->toArray();
            $data['service_id'] =  $newServiceId;
            RateDiscount::create($data);
        }
    }
}
