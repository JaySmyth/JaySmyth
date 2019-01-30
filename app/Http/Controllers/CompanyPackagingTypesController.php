<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyPackagingTypesController extends Controller
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
     * Gets a companies packaging types for a given mode of transport.
     * Expects all requests via ajax call.
     *
     * @param   Illuminate\Http\Request
     * @return  json data array
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $company = Company::find($request->company_id);
            return $company->getPackagingTypes($request->mode_id);
        }
    }

    /**
     * Get the DIMS for the package.
     * 
     * @param Request $request
     * @return type
     */
    public function dims(Request $request)
    {
        if ($request->ajax()) {            
            $company = Company::find($request->company_id);            
            return $company->getPackagingTypes($request->mode_id)->where('code', $request->code)->first()->only(['weight', 'length', 'width', 'height']);            
        }
    }

}
