<?php

namespace App\Http\Controllers;

use App\Models\Models\Company;
use App\Models\Models\CompanyRates;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Rate;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class CompaniesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List company records.
     *
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize(new Company);

        $companies = $this->search($request);

        return view('companies.index', compact('companies'));
    }

    /**
     * Show a company record.
     *
     * @param
     * @return
     */
    public function show($id)
    {
        $company = Company::findOrFail($id);

        $latestShipments = $company->getLatestShipments();

        $this->authorize($company);

        return view('companies.show', compact('company', 'latestShipments'));
    }

    /**
     * Display create company form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize(new Company);

        return view('companies.create');
    }

    /**
     * Store a new company record.
     *
     * @param
     * @return
     */
    public function store(CompanyRequest $request)
    {
        $this->authorize(new Company);

        // Add a company code to the request array (not captured on form)
        $array = Arr::add($request->all(), 'company_code', Str::random(6));

        $company = Company::create($array);

        $company->log();

        flash()->success('Created!', 'Company created successfully.');

        return redirect('companies/'.$company->id);
    }

    /**
     * Display edit company form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);

        return view('companies.edit', compact('company'));
    }

    /**
     * Update a company record.
     *
     * @param
     * @return
     */
    public function update(CompanyRequest $request, $id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);

        $company->updateWithLog($request->all());

        flash()->success('Updated!', 'Company updated successfully.');

        return redirect('companies/'.$id);
    }

    /**
     * Show the change status form.
     *
     * @param
     * @return
     */
    public function status($id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);

        return view('companies.status', compact('company'));
    }

    /**
     * Change the company status (enabled/disabled).
     *
     * @param
     * @return
     */
    public function updateStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $this->authorize('status', $company);

        $this->validate($request, ['notes' => 'required|min:10|max:255']);

        $company->enabled = $request->enabled;
        $company->update();

        $company->log(($request->enabled) ? 'Company set to enabled' : 'Company set to disabled', $request->notes);

        flash()->success('Updated!', 'Company status changed.');

        Mail::to('rates@antrim.ifsgroup.com')->cc([$request->user()->email, 'it@antrim.ifsgroup.com', 'imaguire@antrim.ifsgroup.com', 'rbeck@antrim.ifsgroup.com', 'pjohnston@antrim.ifsgroup.com'])->queue(new \App\Mail\CompanyStatusChange($company, $request->user()));

        return redirect('companies/'.$id);
    }

    /**
     * Gets the the localisation settings for a company
     * Expects all requests via ajax call.
     *
     * @param   Illuminate\Http\Request
     * @return  json data array
     */
    public function getLocalisation(Request $request)
    {
        if ($request->ajax()) {
            return Company::findOrFail($request->company_id)->localisation;
        }
    }

    /**
     * @param
     * @return
     */
    public function services($id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);

        $services = Service::whereDepotId($company->depot_id)->orderBy('default', 'DESC')->orderBy('carrier_id')->orderBy('name')->orderBy('carrier_name')->get();

        return view('companies.services', compact('company', 'services'));
    }

    /**
     * @param
     * @return
     */
    public function setServices(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $this->authorize('services', $company);

        $company->syncServices($request->services, $request->use_default);

        $company->log('Services set', null, $request->all());

        flash()->success('Services Set!', 'Services set successfully.');

        return redirect('companies/'.$company->id);
    }

    /**
     * @param
     * @return
     */
    public function viewCompanyRates($companyId, $serviceId)
    {
        $company = Company::findOrFail($companyId);

        $this->authorize($company);

        $service = Service::findOrFail($serviceId);

        // Check to see if Rate defined for Company
        $rate = CompanyRates::where('company_id', $companyId)->where('service_id', $serviceId)->first();

        // Defaults
        $fuel_cap = 99.99;
        $discount = 0;

        if ($rate) {
            $fuel_cap = $rate->fuel_cap;
            $discount = $rate->discount;
        }

        return view('rates.rate', compact('company', 'service', 'rate', 'fuel_cap', 'discount'));
    }

    /**
     * @param
     * @return
     */
    public function setCompanyRates(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);
        $companyRates = new CompanyRates();

        // Read existing details
        $oldCompanyRate = CompanyRates::where('company_id', $request->company_id)->where('service_id', $request->service_id)->first();

        // Remove any entries for the old Company/ rate/ service from the Discount table
        if ($oldCompanyRate) {
            $companyRates->closeRateDiscounts($company->id, $oldCompanyRate->rate_id, $request->service_id, date('Y-m-d'));
        }

        // Remove any entries for the new Company/ rate/ service from the Discount table
        $companyRates->closeRateDiscounts($company->id, $request->rate_id, $request->service_id, date('Y-m-d'));

        // Create/ Update Company Rate record and clear special_discount flag
        // $criteria = $this->buildCriteria($request->rate_id, $company->id, $request->service_id);
        $special = ($request->discount == 0) ? 0 : 1;
        $companyRate = CompanyRates::updateOrCreate(
            ['company_id' => $company->id, 'service_id' => $request->service_id],
            ['rate_id' => $request->rate_id,
                'special_discount' => $special,
                'discount' => 0,
                'fuel_cap' => $request->fuel_cap,
            ]
        );

        // CompanyRate record created - now to apply a discount - effective today
        $companyRate->setDiscount($request->discount, date('Y-m-d)'));

        // Log changes
        $action = 'Set Rate. Discount : '.$request->discount.' Fuel Cap : '.$request->fuel_cap;
        $changeLog = logRateChange(Auth::user()->id, $request->company_id, $request->service_id, $request->rate_id, '', '', $action);

        // Log the rate change
        $company->log('Rate set', null, $request->all());

        flash()->success('Rate Set!', 'Rate set successfully.');

        return redirect('companies/'.$company->id);
    }

    public function buildCriteria($rateId, $companyId, $serviceId)
    {
        $criteria = ['company_id' => $companyId, 'service_id' => $serviceId];
        $rate = Rate::find($rateId);
        if ($rate) {
            if ($rate->model == 'domestic') {
                $criteria = [
                    'company_id' => $companyId,
                ];
            }
        }

        return $criteria;
    }

    /**
     * @param type $id
     * @return type
     */
    public function deleteCompanyRates($companyId, $serviceId)
    {
        $company = Company::findOrFail($companyId);

        $this->authorize($company);

        $rate = CompanyRates::where('company_id', $companyId)->where('service_id', $serviceId)->first();
        if ($rate) {
            $result = $rate->delete();

            if ($result) {
                flash()->success('Rate Set!', 'Rate Reset successfully.');
                $company->log('Service deleted', "Service id $serviceId deleted");
            } else {
                flash()->error('Rate Not Set!', 'Unable to Reset Rate.');
            }
        } else {
            flash()->info('No Action Taken', 'Already Using Default Rate');
        }

        return redirect('companies/'.$companyId);
    }

    /*
    * Company search.
    *
    * @param   $request
    * @param   $paginate
    *
    * @return
    */

    private function search($request, $paginate = true)
    {
        $query = Company::orderBy('company_name')
        ->filter($request->filter)
        ->hasDepot($request->depot_id)
        ->hasTesting($request->testing)
        ->hasEnabled($request->enabled)
        ->hasSalesperson($request->sale_id)
        ->whereIn('id', $request->user()->getAllowedCompanyIds())
        ->with('users', 'depot');

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

    /**
     * Display the collection settings form.
     *
     * @param
     * @return
     */
    public function collectionSettings($id)
    {
        $company = Company::findOrFail($id);

        $this->authorize($company);

        $useDefaults = 1;

        if ($company->collectionSettings->count() > 0) {
            $useDefaults = 0;
        }

        return view('companies.collection_settings', compact('company', 'useDefaults'));
    }

    /**
     * Store the collection settings for the company.
     *
     * @param
     * @return
     */
    public function storeCollectionSettings(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $this->authorize('collectionSettings', $company);

        if ($request->use_default) {
            \App\Models\Models\CollectionSetting::whereCompanyId($id)->delete();
        } else {
            $rules = [
                'collection_time' => 'required',
                'delivery_time' => 'required',
                'collection_route' => 'required',
                'delivery_route' => 'required',
            ];

            $messageBags = [];

            foreach ($request->settings as $key => $value) {
                $validator = Validator::make($value, $rules);
                if ($validator->fails()) {
                    $messageBags[$key] = $validator->errors();
                }
            }

            if (count($messageBags) > 0) {
                return redirect('companies/'.$company->id.'/collection-settings')
                ->with('messageBags', $messageBags)
                ->withInput();
            }

            // Save the records
            foreach ($request->settings as $key => $value) {
                \App\Models\Models\CollectionSetting::firstOrCreate(['company_id' => $id, 'day' => $key])->update($value);
            }
        }

        // Update the bulk collections flag
        $company->bulk_collections = $request->bulk_collections;
        $company->save();

        // Log this transaction
        $company->log('Collection settings updated', null, $request->all());

        flash()->success('Collection Settings', 'Settings Saved');

        Mail::to('transport@antrim.ifsgroup.com')->cc([$request->user()->email, 'it@antrim.ifsgroup.com'])->queue(new \App\Mail\CollectionSettingsChange($company, $request->user()));

        return redirect('companies/'.$company->id);
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param  Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize(new Company);

        $companies = $this->search($request, false, 2000);

        return Excel::download(new \App\Exports\CompaniesExport($companies), 'companies.xlsx');
    }
}
