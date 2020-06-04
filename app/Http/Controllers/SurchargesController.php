<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurchargeRequest;
use App\Models\Surcharge;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SurchargesController extends Controller
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

        $surcharges = $this->search($request);
        $companyId = 0;

        return view('surcharges.index', compact('surcharges', 'companyId'));
    }

    /**
     * Displays new surcharge form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('viewAny', new Surcharge);

        return view('surcharges.create');
    }

    /**
     * Store surcharge.
     *
     * @param
     * @return
     */
    public function store(SurchargeRequest $request)
    {
        $this->authorize('viewAny', new Surcharge);

        // Check to see if record already exists
        $surcharge = Surcharge::where('service_id', $request->service_id)
                ->where('company_id', $request->company_id)
                ->where('code', $request->code)
                ->first();

        if ($surcharge) {
            flash()->error('Failed!', 'Sorry, Surcharge already exists.', true);

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
    public function edit(Surcharge $surcharge)
    {
        $this->authorize('viewAny', new Surcharge);

        return view('surcharges.edit', compact('surcharge'));
    }

    /**
     * Update the surcharge.
     *
     * @param
     * @return
     */
    public function update(Surcharge $surcharge, SurchargeRequest $request)
    {
        $this->authorize('viewAny', new Surcharge);

        $surcharge->update($request->all());
        flash()->success('Updated!', 'Surcharge updated successfully.');

        return redirect('surcharges');
    }

    /**
     * Delete the surcharge.
     *
     * @param
     * @return
     */
    public function destroy(Surcharge $surcharge)
    {
        $this->authorize('viewAny', new Surcharge);

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
        $this->authorize('viewAny', new Surcharge);

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
        $query = Surcharge::select('surcharges.*')
                ->orderBy('type')
                ->orderBy('name')
                ->filter($request->filter);

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
