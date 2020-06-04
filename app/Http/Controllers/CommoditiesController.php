<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CommodityRequest;
use App\Models\Commodity;
use Illuminate\Http\Request;

class CommoditiesController extends Controller
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
     * List commodities.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->search($request, false);
        }

        $commodities = $this->search($request);

        return view('commodities.index', compact('commodities'));
    }

    /**
     * Show a commodity.
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function show($id, Request $request)
    {
        $commodity = Commodity::findOrFail($id);

        $this->authorize($commodity);

        if ($request->ajax()) {
            return $commodity;
        }

        return view('commodities.show', compact('commodity'));
    }

    /**
     * Displays new commodity form.
     *
     * @param
     * @return
     */
    public function create()
    {
        //$this->authorize(new Commodity);
        return view('commodities.create');
    }

    /**
     * Save a new commodity.
     *
     * @param CommodityRequest $request
     * @return type
     */
    public function store(CommodityRequest $request)
    {
        Commodity::create($request->all());

        if ($request->ajax()) {
            return 'true';
        }

        flash()->success('Created!', 'Commodity created successfully.');

        return redirect('commodities');
    }

    /**
     * Display edit commodity form.
     *
     * @param type $id
     * @return type
     */
    public function edit($id)
    {
        $commodity = Commodity::findOrFail($id);

        $this->authorize($commodity);

        return view('commodities.edit', compact('commodity'));
    }

    /**
     * Update commodity.
     *
     * @param type $id
     * @param CommodityRequest $request
     */
    public function update($id, CommodityRequest $request)
    {
        $commodity = Commodity::find($id);

        $this->authorize($commodity);

        $commodity->update($request->all());

        if (! $request->ajax()) {
            flash()->success('Updated!', 'Commodity updated successfully.');

            return redirect('commodities');
        }
    }

    /**
     * Delete a commodity.
     *
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        $commodity = Commodity::find($id);

        $this->authorize($commodity);

        return $commodity->destroy($id);
    }

    /**
     * Search commodities.
     *
     * @param type $request
     * @param type $paginate
     * @return type
     */
    private function search($request, $paginate = true)
    {
        $query = Commodity::filter($request->filter)
                ->hasCompany($request->company_id)
                ->hasCurrency($request->currency_code)
                ->restrictCompany($request->user()->getAllowedCompanyIds())
                ->orderBy('description')
                ->with('company');

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
