<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Address;
use App\Http\Requests;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class AddressesController extends Controller
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
     * List addresses. Also supports ajax call.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Address::select('id', 'name', 'company_name', 'address1', 'city', 'postcode', 'country_code')
                            ->ofDefinition($request->definition)
                            ->hasCompany($request->company_id)
                            ->groupBy('name')
                            ->groupBy('address1')
                            ->groupBy('postcode')
                            ->orderBy('name')
                            ->orderBy('company_name')
                            ->get();
        }

        $addresses = $this->search($request);

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show an address.
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function show($id, Request $request)
    {
        $address = Address::findOrFail($id);

        $this->authorize($address);

        if ($request->ajax()) {
            return $address;
        }

        return view('addresses.show', compact('address'));
    }

    /**
     * Show create address form.
     *
     * @return type
     */
    public function create(Request $request)
    {
        if ($request->definition == 'sender' || $request->definition == 'recipient') {
            return view('addresses.create');
        }

        \App::abort(404);
    }

    /**
     * Handle create address form. Save the address.
     *
     * @param AddressRequest $request
     * @return type
     */
    public function store(AddressRequest $request)
    {
        $address = Address::create($request->all());

        if ($request->ajax()) {
            return $address->id;
        }

        flash()->success('Created!', 'Address created successfully.');

        return redirect('addresses?definition='.$address->definition);
    }

    /**
     * Show import form.
     *
     * @return type
     */
    public function import(Request $request)
    {
        return view('addresses.import');
    }

    /**
     * Handle import form. Save the addresses.
     *
     * @param Request $request
     * @return type
     */
    public function storeImport(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'company_id' => 'required|numeric',
                //'file' => 'required|mimes:csv,txt',
        ]);

        // Upload the file
        $path = $request->file('file')->storeAs('temp', time().Str::random(3).'.csv');

        // Check that the file was uploaded successfully
        if (! Storage::disk('local')->exists($path)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

            return back();
        }

        // Dispatch import job
        dispatch(new \App\Jobs\ImportAddresses($path, $request->company_id, $request->user()));

        // Notify user and redirect
        flash()->success('File Uploaded!', 'Please check your email for import results.', true);

        return redirect('addresses?definition=recipient');
    }

    /**
     * Show edit address form.
     *
     * @param type $id
     * @return type
     */
    public function edit($id)
    {
        $address = Address::findOrFail($id);

        $this->authorize($address);

        return view('addresses.edit', compact('address'));
    }

    /**
     * Handle edit address form. Updates the address record.
     *
     * @param type $id
     * @param AddressRequest $request
     * @return type
     */
    public function update($id, AddressRequest $request)
    {
        $address = Address::findOrFail($id);

        $this->authorize($address);

        $address->update($request->all());

        if ($request->ajax()) {
            return $address->id;
        }

        flash()->success('Updated!', 'Address updated successfully.');

        return redirect('addresses?definition='.$address->definition);
    }

    /**
     * Autocomplete field. Returns json array - jquery ui autocomplete.
     *
     * @param Request $request
     * @return type
     */
    public function autocomplete(Request $request)
    {
        if ($request->ajax()) {
            $results = [];

            $addresses = Address::select('id', 'name', 'company_name')
                    ->filter($request->term)
                    ->hasCompany($request->company_id)
                    ->ofDefinition($request->definition)
                    ->restrictCompany($request->user()->getAllowedCompanyIds())
                    ->groupBy('name')
                    ->groupBy('address1')
                    ->groupBy('postcode')
                    ->take(5)
                    ->get();

            foreach ($addresses as $address) {
                $results[] = ['id' => $address->id, 'value' => $address->full_name];
            }

            return Response::json($results);
        }
    }

    /**
     * Delete an address.
     *
     * @param Address $address
     * @return string
     */
    public function destroy(Address $address)
    {
        $this->authorize($address);

        $address->delete();

        return response()->json(null, 204);
    }

    /**
     * Search for an address.
     *
     * @param type $request
     * @param type $paginate
     * @return type
     */
    private function search($request)
    {
        return Address::ofDefinition($request->definition)
                        ->filter($request->filter)
                        ->hasCity($request->city)
                        ->hasCountry($request->country_code)
                        ->hasCompany($request->company_id)
                        ->restrictCompany($request->user()->getAllowedCompanyIds())
                        ->orderBy('name')
                        ->orderBy('company_name')
                        ->with('company')
                        ->paginate(50);
    }
}
