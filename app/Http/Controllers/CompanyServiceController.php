<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyServiceRequest;
use App\Models\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class CompanyServiceController extends Controller
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
    }

    /**
     * Show a company record.
     *
     * @param
     * @return
     */
    public function show($id)
    {
    }

    /**
     * Display create company form.
     *
     * @param
     * @return
     */
    public function create()
    {
    }

    /**
     * Display edit company form.
     *
     * @param
     * @return
     */
    public function edit($companyId, $serviceId)
    {
        $companyService = CompanyService::where('company_id', $companyId)->where('service_id', $serviceId)->first();
        if (isset($companyService)) {
            $this->authorize($companyService);

            return view('company_service.edit', compact('companyService'));
        }

        flash()->error('Error!', 'Cannot set Filter on Defaulted Services.');
        return redirect('companies/'.$companyId);
    }

    /**
     * Update a company record.
     *
     * @param
     * @return
     */
    public function update(CompanyServiceRequest $request)
    {
        $companyService = CompanyService::where('company_id', $request->company_id)->where('service_id', $request->service_id)->first();

        $this->authorize($companyService);

        $companyService->updateWithLog($request->all());

        flash()->success('Updated!', 'CompanyService updated successfully.');

        return redirect('companies/'.$request->company_id);
    }
}
