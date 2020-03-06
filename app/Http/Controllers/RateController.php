<?php

namespace App\Http\Controllers;

use App\Models\Models\Carrier;
use App\Models\Models\Company;
use App\Models\Models\CompanyRates;
use App\Models\Models\DomesticRate;
use App\Models\Models\DomesticRateDiscount;
use App\Legacy\Fuk_RateH;
use App\Legacy\FukCustService;
use App\Legacy\FukRate;
use App\Legacy\FxRateH;
use App\Legacy\OldCompany;
use App\Models\Rate;
use App\Models\RateChangeLogs;
use App\Models\RateDetail;
use App\Models\RateDiscount;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RateController extends Controller
{
    public function index()
    {
        $rates = Rate::orderBy('rate_type')->orderBy('model')->orderBy('description')->get();

        return view('rates.index', compact('rates'));
    }

    public function showRate(Rate $rate, $rateDate = '')
    {
        $charges = [];
        if ($rateDate == '') {
            $rateDate = date('Y-m-d');
        }
        if ($rate) {
            if (strtolower($rate->model) == 'domestic') {
                $rateTable = DomesticRate::where('rate_id', $rate->id)->where('from_date', '<=', $rateDate)->where('to_date', '>=', $rateDate)->get();
            } else {
                $rateTable = RateDetail::where('rate_id', $rate->id)->where('from_date', '<=', $rateDate)->where('to_date', '>=', $rateDate)->get();
            }

            $companyRates = new CompanyRates();
            $table = $companyRates->formatRateTable($rate, 0, $rateTable);
            $tableFormat = $rate->model;
            if ($tableFormat == 'domestic') {
                $domesticRate = new DomesticRate();
                $zones = $domesticRate->getZones($rate, $rateDate);

                return view('rates.show_'.$tableFormat, compact('tableFormat', 'rate', 'table', 'zones', 'charges'));
            } else {
                $intlRate = new RateDetail();
                $zones = $intlRate->getZones($rate, $rateDate);

                return view('rates.show_intl', compact('tableFormat', 'rate', 'table', 'zones', 'charges'));
            }
        } else {
            return view('errors.404');
        }
    }

    public function showCompanyRate(Company $company, Service $service, $discount = 0, $shipDate = null)
    {
        if ($service) {
            if (is_null($shipDate)) {
                $shipDate = Carbon::today()->toDateString('Y-m-d');
            } else {
                $shipDate = Carbon::parse($shipDate)->format('Y-m-d');
            }

            $rateInfo = $company->salesRateForService($service->id);
            $rate = Rate::find($rateInfo['id']);
            if ($rate) {
                if ($rate->model == 'domestic') {
                    return $rate->getRateView($company, '', $discount, $shipDate);
                } else {
                    return $rate->getRateView($company, $service, $discount, $shipDate);
                }
            }
        }
    }

    /**
     * Get a list of all Live companies and
     * Migrate all legacy rates to the new
     * system.
     */
    public function migrateAllCompanyRates()
    {

        // $requiredCompanies = ["55","59","65","78","84","87","90","92","94","99","109","110","130","133","145","150","158","160","162","187","190","231","243","259","272","277","304","313","314","324","326","358","371","396","399","416","424","425","429","436","453","470","472","474","482","484","492","498","503","512","518","529","538","550","554","558","580","582","585","591","595","610","620","668","675","685","689","703","706","707","721","740","741","744","750","758","760","761","768","775","777","779","784","785","789","794","801","806","810","815","818","819","820","832","836","837","838","850","854"];
        $requiredCompanies = ['296', '302', '305', '308', '309', '312', '318', '320', '327', '332', '333', '354', '357', '364', '366', '382', '383', '391', '395', '397', '405', '408', '410', '411', '413', '417', '418', '422', '430', '434', '435', '443', '446', '451', '463', '467', '475', '493', '497', '501', '509', '510', '522', '525', '526', '556', '564', '569', '570', '578', '589', '598', '600', '603', '604', '623', '671', '683', '687', '690', '696', '697', '698', '700', '715', '723', '724', '730', '733', '735', '736', '742', '746', '748', '754', '755', '756', '757', '763', '764', '772', '778', '782', '790', '791', '792', '795', '812', '814', '816', '817', '822', '826', '829', '833', '834', '839', '840', '844', '846', '848', '851', '853', '856', '857'];

        $companies = new Company();
        $companyList = $companies::whereIn('id', $requiredCompanies)->where('legacy', '1')->get();

        //$companyList = $companies::where('legacy', '1')->orderBy('id')->get();
        foreach ($companyList as $company) {
            echo '********************************<br>';
            echo '* Migrating Company : '.$company->id.'<br>';
            echo '********************************<br>';
            $this->migrateAllRates($company);
        }
    }

    /**
     * **********************************************
     * Gets all rates defined on the old system for
     * a company and Migrates them to the new system
     * as a table of discounts on a standard rate.
     * **********************************************.
     *
     * @param type $Company
     */
    public function migrateAllRates(Company $company)
    {
        ini_set('memory_limit', '384M');
        set_time_limit(300);
        if ($company) {

            // Read Old Company Details
            $oldCompany = OldCompany::find($company->id);

            if ($oldCompany) {
                // Get Services defined for the Company
                $services = new FukCustService();
                $customerServices = $services->getCompanyServices($oldCompany);
                if (! $customerServices->isEmpty()) {

                    // Delete any existing Company Services
                    // $company->syncServices([], true);
                    // Delete any existing Rates or Rate Discounts
                    // $rate = CompanyRate::where('company_id', $company->id)->delete();
                    // $rate = DomesticRateDiscount::where('company_id', $company->id)->delete();
                    // $rate = RateDiscount::where('company_id', $company->id)->delete();
                    // Cycle through each service and migrate it.
                    foreach ($customerServices as $service) {
                        $this->doMigration($oldCompany, $service);
                    }
                }
            }
        }
    }

    /**
     * Given the service, create the appropriate objects
     * to allow migration of the legacy rate to the
     * new system and then initiate the migration.
     *
     * @param type $company
     * @param type $service
     */
    public function doMigration($company, $service)
    {
        if ($service->app == 'courierUK') {

            // Domestic Services
            switch (strtolower($service->service)) {

                case 'uk24':
                case 'uk48r':

                    // UPS Domestic Services
                    $this->migrateRate($company, $service, new Fuk_RateH());
                    break;

                default:

                    // Standard Domestic Services - Fedex/ IFS etc.
                    $this->migrateRate($company, $service, new FukRate());
                    break;
            }
        } else {

            // International Services
            $this->migrateRate($company, $service, new FxRateH());
        }
    }

    /**
     * Get all information necessary to start the
     * migration. Migrate the rate, and enable the
     * service for the Company.
     *
     * @param type $company
     * @param type $legacyService
     * @param type $rate
     * @return bool
     */
    public function migrateRate($company, $legacyService, $rate)
    {
        $carrierId = $legacyService->getCarrierId();
        $currentRate = $rate->readLegacyRate($company, $legacyService);

        if (! $currentRate->isEmpty()) {

            // Get Carrier and Service objects for this service code
            $carrier = Carrier::find($carrierId);                                           // Get Carrier
            $newService = Service::where('code', strtolower($legacyService->service))       // Get Service
                    ->where('carrier_id', $carrierId)
                    ->first();

            if ($newService) {

                // Get rateId of service on new system
                $newRateId = $this->getRateId($company, $legacyService, $newService);

                // Migrate the rate and enable the service for the company
                if ($rate->migrate($company, $currentRate, $newRateId, $newService)) {
                    $this->displayStatus('Migrated', $company, $legacyService);

                    if (strtoupper($legacyService->service) == 'UK48') {

                        // Enable all standard Domestic services
                        $service = new Service();
                        $service->find(2)->enableForCompany($company->company, $newRateId);
                        $service->find(3)->enableForCompany($company->company, $newRateId);
                        $service->find(19)->enableForCompany($company->company, $newRateId);
                    } else {
                        return $newService->enableForCompany($company->company, $newRateId);
                    }
                } else {
                    $this->displayStatus('Failed', $company, $legacyService);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Display Status, Carrier & Service for the given company.
     *
     * @param type $status
     * @param type $company
     * @param type $legacyService
     */
    public function displayStatus($status, $company, $legacyService)
    {
        echo 'Company : '.$company->company
        .' Service : '.$legacyService->service
        .' Carrier : '.$legacyService->gateway
        .' - '.$status
        .'<br>';
    }

    /**
     * Given Legacy tableName and Service identify
     * the correct base table to use on the new system.
     *
     * @param type $tableName
     * @param type $rateType
     * @return int
     */
    public function getRateId($company, $legacyService, $newService)
    {
        $stdRates = [
            'CUKA' => 500, 'CUKB' => 501, 'CUKC' => 502, 'CUKD' => 503,
            'CUK24_1' => 710, 'CUK24_2' => 711, 'CUK24_3' => 712, 'CUK24_4' => 713, 'CUK24_5' => 714,
            '3_1' => 600, '3_2' => 601, '3_3' => 602, '3_4' => 603, '3_5' => 604, '3_6' => 605,
            'EU_ECON' => 700,
        ];

        $tableName = $this->getRateTableName($company, $legacyService);

        if (isset($stdRates[$tableName])) {

            // Use Standard Rate
            return $stdRates[$tableName];
        } else {

            // Use Service default sales rate
            return $newService->sales_rate_id;
        }
    }

    public function getRateTableName($company, $legacyService)
    {
        if ($legacyService->app == 'courierUK') {

            // Domestic Service
            switch (strtolower($legacyService->service)) {

                case 'uk24':
                case 'uk48r':
                    $rate = new Fuk_RateH();
                    $rateId = $rate->getRateTable($company->company, 'courierUK', $legacyService->service, $company->UPSTable, date('Y-m-d'));

                    return strtoupper($rateId);
                    break;

                default:

                    return strtoupper($company->UKRate);
                    break;
            }
        } else {

            // International Service
            if (strtoupper($legacyService->gateway) == 'UPS') {

                // UPS
                return strtoupper($company->UPSTable);
            } else {

                // Fedex
                return strtoupper($company->FXTable);
            }
        }
    }

    /**
     * Display upload shipments form.
     *
     * @param Request $request
     * @return type
     */
    public function uploadCompanyRate($company_id, $service_id)
    {
        // $this->authorize(new Rate);
        $from_date = date('d-m-Y');
        $to_date = date('d-m-Y', strtotime('Dec 31'));

        return view('rates.upload', compact('company_id', 'service_id', 'from_date', 'to_date'));
    }

    /**
     * Process uploaded shipment file.
     *
     * @param Request $request
     * @return type
     */
    public function storeUpload($companyId, $serviceId, Request $request)
    {
        // $this->authorize('upload', new Rate);
        // Validate the request
        $this->validate($request, ['rate_id' => 'required|numeric', 'file.*' => 'required|mimes:xls,xlsx'], ['rate_id.required' => 'Please select base rate.', 'file.required' => 'Please select a file to upload.']);

        // Upload the file to the rate_uploads directory
        $uploadDirectory = 'rate_uploads';

        $fileName = $companyId.'_'.time().'.'.pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
        $path = $request->file('file')->storeAs($uploadDirectory, $fileName);

        // Check that the file was uploaded successfully
        if (! Storage::disk('local')->exists($path)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

            return back();
        }

        // Import csv file
        if ($request->hasFile('file')) {
            $array = array_map('str_getcsv', file(storage_path('app/'.$path)));
            $keys = $array[0];
            unset($array[0]);
            $uploadedRate = [];

            foreach ($array as $r => $row) {
                $i = 0;
                foreach ($keys as $key) {
                    $uploadedRate[$r][$key] = $row[$i];
                    $i++;
                }
            }
        }

        // Get base Rate
        $rate = Rate::find($request->rate_id);

        // Process uploaded file
        $errors = $rate->processRateUpload($companyId, $serviceId, $request->rate_id, $uploadedRate);
        if ($errors) {

            // Notify user and redirect
            flash()->error('File Failed!', $errors);

            return back();
        } else {
            if ($rate->model == 'domestic') {

                // Identify all domestic rates
                $domesticRates = Rate::where('model', 'domestic')->pluck('id')->toArray();

                // Identify which of these are being used by this company
                $companyRates = CompanyRates::where('company_id', $companyId)->whereIn('rate_id', $domesticRates)->get();

                // Set them all to the new base rate
                foreach ($companyRates as $companyRate) {
                    $companyRate->rate_id = $request->rate_id;
                    $companyRate->discount = 0;
                    $companyRate->special_discount = 1;
                    $companyRate->save();
                }
            } else {

                // Set base rate for International service
                CompanyRates::updateOrCreate(
                    [
                    'company_id' => $companyId,
                    'service_id' => $serviceId,
                        ],
                    [
                    'rate_id' => $request->rate_id,
                    'discount' => 0,
                    'special_discount' => 1,
                ]
                );
            }

            // Update log
            $changeLog = logRateChange(Auth::user()->id, $companyId, $serviceId, $request->rate_id, $uploadDirectory, $fileName, 'New rate Uploaded. Discount set to 0');

            // Notify user and redirect
            flash()->info('File Uploaded!', 'Rate Successfully loaded', true);

            return redirect('companies/'.$companyId);
        }
    }

    /**
     * Download rate - excel.
     *
     * @param Company $company
     * @param Service $service
     * @param type $effectiveDate
     *
     * @return Excel document
     */
    public function downloadCompanyRate(Company $company, Service $service, $effectiveDate = '', $download = true)
    {
        if ($service) {
            $effectiveDate = ($effectiveDate) ? $effectiveDate : Carbon::today()->toDateString();
            $rateInfo = $company->salesRateForService($service->id);
            if (isset($rateInfo['id']) && isset($rateInfo['discount'])) {
                $rate = Rate::find($rateInfo['id']);
                if ($rate) {
                    return $rate->downloadCompanyRate($company, $service, $rateInfo['discount'], $effectiveDate, $download);
                }
            }
        }

        if ($download) {
            return view('errors.404');
        } else {
            return [];
        }
    }

    public function revertCompanyRatesView()
    {
        return view('rates.revert');
    }

    public function revertCompanyRates(Request $request)
    {
        $errors = [];
        if (Carbon::parse($request->effectiveDate)->gt(Carbon::parse($request->revertToDate))) {
            $company = Company::find($request->company_id);
            if ($company) {
                $effectiveDate = Carbon::parse($request->effectiveDate)->format('Y-m-d');
                $revertToDate = Carbon::parse($request->revertToDate)->format('Y-m-d');
                $discount = $request->discount;

                // Cycle through each of the companies services reverting rates
                foreach ($company->services as $service) {
                    $errors = $this->revertRate($company, $service, $effectiveDate, $revertToDate, $discount);
                    if ($errors != []) {
                        echo 'Errors in processing upload<br>';
                        echo "Company : $company->id Service : $service->code Description : $service->carrier_name<br>";
                        echo 'Upload incomplete<br>';
                        dd($errors);
                    }
                }
            }
        }
    }

    /*
     * ***************************************
     * Revert rate to a previous date
     * ***************************************
     *
     */

    public function revertRate(Company $company, Service $service, $effectiveDate = '', $revertToDate = '', $discount = 0)
    {

        // Identify the rate we are using
        $rateArray = $company->salesRateForService($service->id);
        $rate = Rate::find($rateArray['id']);
        if ($rate) {

            // Download rate we wish to revert to
            $requiredRate = $rate->downloadCompanyRate($company, $service, $revertToDate, false);

            // Process uploaded file
            return $rate->processRateUpload($company->id, $service->id, $rate->id, $requiredRate, $effectiveDate);
        }
    }

    public function getDefaultDate($dateType, $myDate)
    {
        // If rateDate suppplied then use it
        if ($myDate != '') {
            return $myDate;
        }

        switch ($dateType) {
            case 'revert_to_date':
                // Return one month previous
                return date('Y-m-d H:i:s', strtotime('-1 month'));
                break;

            default:
                // Return todays date
                return date('Y-m-d');
                break;
        }
    }
}
