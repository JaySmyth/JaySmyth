<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurchargeDetailRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Surcharge;
use App\SurchargeDetail;
use Maatwebsite\Excel\Facades\Excel;

class SurchargeDetailsController extends Controller
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
     * List surcharges.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new Surcharge);
        $title = "Surcharge Details";
        $surcharges = $this->search($request);

        /*
         * ****************************************
         * If Company specified then cycle through
         * the charges and remove the default 
         * charge if company specific charge exists
         * Company charge will always appear first
         * ****************************************
         */
        if (isset($request->company)) {
            $companyId = $request->company;
        } else {
            $companyId = 0;
        }
        if ($surcharges) {

            $chargeCodes = [];
            foreach ($surcharges as $key => $surcharge) {

                // If Charge already added then ignore
                if (in_array($surcharge->code, $chargeCodes)) {

                    $surcharges->forget($key);
                } else {

                    // Charge not already added so add
                    $chargeCodes[] = $surcharge->code;
                }
            }
        }

        return view('surcharge_details.index', compact('title', 'surcharges', 'companyId'));
    }

    /**
     * Displays new surcharge form.
     *
     * @param
     * @return
     */
    public function create()
    {

        $this->authorize('index', new Surcharge);

        return view('surcharge_details.create');
    }

    /**
     * Store surcharge.
     *
     * @param
     * @return
     */
    public function store(SurchargeDetailsRequest $request)
    {

        $this->authorize('index', new Surcharge);

        // Check to see if record already exists
        $surcharge = Surcharge::where('service_id', $request->service_id)
                ->where('company_id', $request->company_id)
                ->where('code', $request->code)
                ->first();

        if ($surcharge) {
            flash()->error('Failed!', "Sorry, Surcharge already exists.", true);
            return back();
        } else {
            Surcharge::create($request->all());
            flash()->success('Created!', 'Surcharge created successfully.');
            return redirect('surcharges');
        }
    }

    /**
     * Display edit surcharge form.
     *
     * @param
     * @return
     */
    public function edit(SurchargeDetail $surcharge)
    {

        $this->authorize('index', new Surcharge);

        return view('surcharge_details.edit', compact('surcharge'));
    }

    /**
     * Update the surcharge.
     *
     * @param
     * @return
     */
    public function update(SurchargeDetail $surcharge, SurchargeDetailRequest $request)
    {
        $this->authorize('index', new Surcharge);

        $surcharge->update($request->all());
        flash()->success('Updated!', 'Surcharge updated successfully.');
        
        $surchargeId = (isset($request->surcharge_id)) ? $request->surcharge_id : 0;
        $companyId = (isset($request->company_id)) ? $request->company_id : 0;
        $url = 'surchargedetails' . '/' . $surchargeId . '/' . $companyId . '/index';

        return redirect($url);
    }

    /**
     * Delete the surcharge.
     *
     * @param
     * @return
     */
    public function destroy(SurchargeDetail $surcharge)
    {
        $this->authorize('index', new Surcharge);

        if ($request->ajax()) {
            return $surcharge->delete();
        }
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param  Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize('index', new Surcharge);

        $surcharges = $this->search($request, false);

        return Excel::download(new \App\Exports\SurchargesExport($surcharges), 'surcharges.xlsx');
    }

    /*
     * Surcharge search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request, $paginate = true)
    {

        if (isset($request->effective_date)) {
            $effectiveDate = $request_date;
        } else {
            $effectiveDate = date('Y-m-d');
        }

        $query = SurchargeDetail::select('surcharge_details.*')
                ->join('surcharges', 'surcharges.id', '=', 'surcharge_details.surcharge_id')
                ->orderBy('surcharge_details.name')
                ->orderBy('surcharge_details.company_id', 'desc')
                ->orderBy('surcharge_details.name')
                ->filter($request->filter)
                ->hasSurcharge($request->surcharge);

        if (isset($request->company)) {
            $query->whereIn('company_id', ['0', $request->company]);
        } else {
            $query->whereIn('company_id', ['0']);
        }

        $query->whereDate('from_date', '<=', $effectiveDate)
                ->whereDate('to_date', '>=', $effectiveDate);

        if (!$paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

}
