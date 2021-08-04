<?php

namespace App\Http\Controllers;

use App\Legacy\Fuk_RateH;
use App\Legacy\FukCustService;
use App\Legacy\FukRate;
use App\Legacy\FxRateH;
use App\Legacy\OldCompany;
use App\Models\Carrier;
use App\Models\Company;
use App\Models\CompanyRates;
use App\Models\DomesticRate;
use App\Models\DomesticRateDiscount;
use App\Models\Rate;
use App\Models\RateChangeLogs;
use App\Models\RateDetail;
use App\Models\RateDiscount;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        if (empty($rateDate)) {
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
    public function downloadMasterRate(Rate $rate, $effectiveDate = '', $download = true)
    {
        $effectiveDate = ($effectiveDate) ? $effectiveDate : Carbon::today()->toDateString();
        if ($rate) {
            return $rate->downloadMasterRate($effectiveDate, $download);
        }

        if ($download) {
            return view('errors.404');
        } else {
            return [];
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

    public function rateIncrease(Request $request)
    {
        $user = Auth::User();
        if (! in_array($user->id, ['85','3378'])) {
            return view('errors.404');
        }

        return view('rates.increase');
    }

    public function storeRateIncrease(Request $request)
    {
        $this->validate($request, [
            'type_id' => 'required|in:all,domestic,intl', 'effective_from' => 'required|date', 'effective_to' => 'required|date|after:effective_from', 'increase' =>'required|numeric|min:0.5|max:10'
        ]);

        $logs = [];
        $mode = $request->type_id;
        $dateFrom = Carbon::createFromFormat('d-m-Y', $request->effective_from, 'Europe/London');
        $dateTo = Carbon::createFromFormat('d-m-Y', $request->effective_to, 'Europe/London');
        $increase = $request->increase;
        switch ($mode) {
            case 'all':
                $logs = $this->increaseDomestic($mode, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $increase, $logs);
                $logs = $this->increaseIntl($mode, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $increase, $logs);
                break;
            case 'domestic':
                $logs = $this->increaseDomestic($mode, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $increase, $logs);
                dd($logs);
                break;
            case 'intl':
                $logs = $this->increaseIntl($mode, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), $increase, $logs);
                break;
            default:
            break;
        }

        // Inform user that the import has been completed
        Mail::to(Auth::User()->email)->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\RateIncreaseResults($logs));


        return view('rates/increase');
    }

    private function increaseDomestic($mode, $fromDate, $toDate, $increase, $logs = [])
    {
        $errors = $this->checkAbleToIncrease($mode, $fromDate, $toDate);
        if ($errors == []) {
            $multiplier = 1 + $increase / 100;
            $rates = DomesticRate::where('rate_id', '>=', '500')
                    ->where('to_date', '>', date('Y-m-d'))
                    ->get();

            // Cycle through rates selected and increase values
            foreach ($rates as $rate) {
                DomesticRate::create([
                    'rate_id' => $rate->rate_id,
                    'service' => $rate->service,
                    'packaging_code' => $rate->packaging_code,
                    'first' => round($rate->first * $multiplier, 4),
                    'others' => round($rate->others * $multiplier, 4),
                    'notional_weight' => $rate->notional_weight,
                    'notional' => round($rate->notional * $multiplier, 4),
                    'area' => $rate->area,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ]);
            }

            $logs[] = 'Live Domestic Rates Updated '.date('Y-m-d H:i:s');
        } else {
            foreach ($errors as $error) {
                $logs[] = $error;
            }
        }

        return $logs;
    }

    private function increaseIntl($mode, $fromDate, $toDate, $increase, $logs = [])
    {
        $errors = $this->checkAbleToIncrease($mode, $fromDate, $toDate);
        if ($errors==[]) {
            $multiplier = 1 + $increase / 100;
            $rates = RateDetail::where('rate_id', '>=', '500')->where('to_date', '>', date('Y-m-d'))->get();

            // Cycle through rates selected and increase values
            foreach ($rates as $rate) {
                RateDetail::create([
                    'rate_id' => $rate->rate_id,
                    'residential' => $rate->residential,
                    'piece_limit' => $rate->piece_limit,
                    'package_type' => $rate->package_type,
                    'zone' => $rate->zone,
                    'break_point' => $rate->break_point,
                    'weight_rate' => round($rate->weight_rate * $multiplier, 4),
                    'weight_increment' => $rate->weight_increment,
                    'package_rate' => round($rate->package_rate * $multiplier, 4),
                    'consignment_rate' => round($rate->consignment_rate * $multiplier, 4),
                    'weight_units' => $rate->weight_units,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ]);

                $logs[] = 'Live Domestic Rates Updated '.date('Y-m-d H:i:s');
            }
        } else {
            foreach ($errors as $error) {
                $logs[] = $error;
            }
        }

        return $logs;
    }

    /**
     * Check there are no conflicting rates already in place.
     */
    protected function checkAbleToIncrease($mode, $fromDate, $toDate, $logs = [])
    {
        if (in_array($mode, ["all","domestic"])) {
            $rates = DomesticRate::where('rate_id', '>=', '500')->where('to_date', '>', $fromDate)->first();
            if ($rates) {
                $logs = $this->unableToApplyIncrease('domestic_rates', $fromDate, $toDate, $logs);
            }
        }

        if (in_array($mode, ["all","intl"])) {
            $rates = RateDetail::where('rate_id', '>=', '500')->where('to_date', '>', $fromDate)->first();
            if ($rates) {
                $logs = $this->unableToApplyIncrease('rate_details', $fromDate, $toDate, $logs);
            }
        }

        return $logs;
    }

    protected function unableToApplyIncrease($table, $fromDate, $toDate, $logs = [])
    {
        $logs[] = "Table : $table already contains rates for the period ".$fromDate.' to '.$toDate."\bPlease remove rate(s) and try again.";
        return $logs;
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

    /**
     * Master Rate Upload CSV screen.
     *
     * @return type
     */
    public function uploadMasterRate(Rate $rate)
    {
        // $this->authorize(new Rate);

        return view('rates.master_upload', compact('rate'));
    }

    /**
     * Process CSV upload.
     *
     * @param Request $request
     * @return type
     */
    public function storeMasterUpload(Rate $rate, Request $request)
    {
        // $this->authorize('upload', new Rate);

        $user = Auth::user();
        if ($user) {

            // Validate the request
            $this->validate($request, ['file' => 'required|mimes:csv,txt'], ['file.required' => 'Please select a file to upload.']);

            // Upload the file to the temp directory
            $path = $request->file('file')->storeAs('temp', 'original_'.Str::random(12).'.csv');

            // Check that the file was uploaded successfully
            if (! Storage::disk('local')->exists($path)) {
                flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

                return back();
            }

            dispatch(new \App\Jobs\ImportMasterRate($path, $rate, $user));

            // Notify user and redirect
            flash()->info('File Uploaded!', 'Please check your email for results.', true);

            return redirect('rates');
        } else {
            flash()->error('Authentication Error!', 'Please Login and try again.');

            return redirect('/');
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
        if (!empty($myDate)) {
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
