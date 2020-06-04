<?php

namespace App\Http\Controllers;

use App\Exports\SurchargesExport;
use App\Http\Requests\SurchargeDetailRequest;
use App\Models\Surcharge;
use App\Models\SurchargeDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SurchargeDetailsController extends Controller
{
    private $companyId;
    private $companyIds;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function setCompanyIds($request)
    {
        if (isset($request->company) && $request->company > 0) {
            $this->companyId = $request->company;
            $this->companyIds = [0, $request->company];
        } else {
            $this->companyId = 0;
            $this->companyIds = [0];
        }
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
        $title = 'Surcharge Details';

        $this->setCompanyIds($request);
        $companyId = $this->companyId;

        // Get Surcharge Details
        $surcharges = SurchargeDetail::where('surcharge_id', $request->surcharge)
        ->whereIn('company_id', $this->companyIds)
        ->where('from_date', '<=', date('Y-m-d'))
        ->where('to_date', '>=', date('Y-m-d'))
        ->orderBy('name')
        ->orderBy('to_date')
        ->orderBy('company_id', 'desc')
        ->get();

        /*
         * ****************************************
         * If Company specified then cycle through
         * the charges and remove the default
         * charge if company specific charge exists
         * Company charge will always appear first
         * ****************************************
         */

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

        if ($request->company > 0) {
            $query->whereIn('company_id', ['0', $request->company]);
        } else {
            $query->whereIn('company_id', ['0']);
        }

        $query->whereDate('from_date', '<=', $effectiveDate)
            ->whereDate('to_date', '>=', $effectiveDate);

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

    /**
     * Displays new surcharge form.
     *
     * @param
     * @return
     */
    public function create(Surcharge $surcharge)
    {
        $this->authorize('viewAny', new Surcharge);

        // Can only get Surcharge Codes already configured
        $surchargeCodes = SurchargeDetail::where('surcharge_id', $surcharge->id)
        ->where('company_id', '0')
        ->where('to_date', '>=', date('Y-m-d'))
        ->orderBy('name')
        ->orderBy('to_date')
        ->pluck('name', 'id');

        return view('surcharge_details.create', compact('surcharge', 'surchargeCodes'));
    }

    /**
     * Store surcharge.
     *
     * @param
     * @return
     */
    public function store(Request $request, $surcharge, $company)
    {
        $this->authorize('viewAny', new Surcharge);

        // If no Company specified assume all - 0
        $companyId = (empty($request->company_id)) ? 0 : $request->company_id;

        // Get charge name from default charge (company 0)
        $surcharge = SurchargeDetail::where('code', $request->code)
                ->where('surcharge_id', $request->surcharge_id)
                ->where('company_id', '0')
                ->first();

        if ($surcharge) {
            $request->name = $surcharge->name;
        } else {
            flash()->error('Failed!', 'Sorry, default surcharge does not exist.', true);

            return back();
        }

        // Check to see if record already exists covering this period
        $surchargeDetails = SurchargeDetail::where('code', $request->code)
            ->where('surcharge_id', $request->surcharge_id)
            ->where('company_id', $companyId)
            ->where('to_date', '>=', $request->from_date)
            ->first();

        // If it exists then close it off
        if ($surchargeDetails) {
            $newToDate = date('Y-m-d', strtotime($request->from_date.' -1 day'));
            if ($surchargeDetails->from_date >= $newToDate) {
                $surchargeDetails->delete();
            } else {
                $surchargeDetails->to_date = $newToDate;
                $surchargeDetails->save();
            }
        }

        // Finally create new surcharge detail record
        SurchargeDetail::create($request->all());
        flash()->success('Created!', 'Surcharge created successfully.');

        return redirect("surchargedetails/$surcharge/$company/index");
    }

    /**
     * Display edit surcharge form.
     *
     * @param
     * @return
     */
    public function edit(SurchargeDetail $surcharge)
    {
        $this->authorize('viewAny', new Surcharge);

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
        $this->authorize('viewAny', new Surcharge);

        $surchargeDetails = SurchargeDetail::find($request->charge_id);

        if ($surchargeDetails) {
            $name = $surchargeDetails->name;

            $closeDate = Carbon::createFromformat('d-m-Y', $request->from_date)->addDay(-1);
            $fromDate = Carbon::createFromformat('d-m-Y', $request->from_date);

            // Check for and remove any future records
            SurchargeDetail::where('company_id', $request->company_id)
                ->where('surcharge_id', $request->surcharge_id)
                ->where('code', $request->code)
                ->where('from_date', '>=', $fromDate->format('Y-m-d'))
                ->where('to_date', '>=', $fromDate->format('Y-m-d'))
                ->delete();

            // Close old Record
            $surchargeDetails->to_date = $closeDate->format('d-m-Y');
            $surchargeDetails->save();

            // Add new record
            $surcharge->create(array_merge($request->all(), ['name' => $name]));
            flash()->success('Updated!', 'Surcharge updated successfully.');

            $surchargeId = (isset($request->surcharge_id)) ? $request->surcharge_id : 0;
            $companyId = (isset($request->company_id)) ? $request->company_id : 0;
            $url = 'surchargedetails'.'/'.$surcharge->surcharge_id.'/'.$companyId.'/index';

            return redirect($url);
        }
        flash()->error('Failed!', 'Sorry, surcharge not found.', true);

        return back();
    }

    /**
     * Delete the surcharge.
     *
     * @param
     * @return
     */
    public function destroy(SurchargeDetail $surcharge, Request $request)
    {
        $this->authorize('viewAny', new Surcharge);

        if ($request->ajax()) {
            return $surcharge->delete();
        }
    }

    /*
     * Surcharge search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize('viewAny', new Surcharge);

        $surcharges = $this->search($request, false);

        return Excel::download(new SurchargesExport($surcharges), 'surcharges.xlsx');
    }
}
