<?php

namespace App\Http\Controllers;

use App\FuelSurcharge;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelSurchargeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuelSurchargesController extends Controller
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
     * List the fuel surcharges.
     *
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize(new FuelSurcharge);

        $fuelSurcharges = $this->search($request);

        return view('fuel_surcharges.index', compact('fuelSurcharges'));
    }

    /**
     * Display create record form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize(new FuelSurcharge);

        return view('fuel_surcharges.create');
    }

    /**
     * Store a new record.
     *
     * @param
     * @return
     */
    public function store(FuelSurchargeRequest $request)
    {
        $this->authorize(new FuelSurcharge);

        $fuelSurcharge = FuelSurcharge::create($request->all());

        flash()->success('Created!', 'Fuel surcharge created successfully.');

        return redirect('fuel-surcharges');
    }

    /**
     * Display edit screen.
     *
     * @param
     * @return
     */
    public function edit(FuelSurcharge $fuelSurcharge)
    {
        $this->authorize($fuelSurcharge);

        return view('fuel_surcharges.edit', compact('fuelSurcharge'));
    }

    /**
     * Update changes.
     *
     * @param
     * @return
     */
    public function update(FuelSurcharge $fuelSurcharge, FuelSurchargeRequest $request)
    {
        $this->authorize($fuelSurcharge);

        $fuelSurcharge->update($request->all());

        flash()->success('Updated!', 'Fuel surcharge updated successfully.');

        return redirect('fuel-surcharges');
    }

    /**
     * Upload CSV screen.
     *
     * @return type
     */
    public function upload()
    {
        $this->authorize(new FuelSurcharge);

        return view('fuel_surcharges.upload');
    }

    /**
     * Process CSV upload.
     *
     * @param Request $request
     * @return type
     */
    public function storeupload(Request $request)
    {
        $this->authorize('upload', new FuelSurcharge);

        // Validate the request
        $this->validate($request, ['file' => 'required|mimes:csv,txt'], ['file.required' => 'Please select a file to upload.']);

        // Upload the file to the temp directory
        $path = $request->file('file')->storeAs('temp', 'original_'.str_random(12).'.csv');

        // Check that the file was uploaded successfully
        if (! Storage::disk('local')->exists($path)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

            return back();
        }

        dispatch(new \App\Jobs\ImportFuelSurcharges($path, $request->user()));

        // Notify user and redirect
        flash()->info('File Uploaded!', 'Please check your email for results.', true);

        return back();
    }

    /**
     * Delete.
     *
     * @param Quotation $quotation
     * @return string
     */
    public function destroy(FuelSurcharge $fuelSurcharge)
    {
        $this->authorize($fuelSurcharge);

        $fuelSurcharge->delete();

        return response()->json(null, 204);
    }

    /*
     * Fuel surcharge search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request, $paginate = true)
    {
        $query = FuelSurcharge::orderBy('id', 'DESC')
                ->filter($request->filter)
                ->fromDate($request->from_date)
                ->toDate($request->to_date)
                ->hasCarrier($request->carrier);

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
