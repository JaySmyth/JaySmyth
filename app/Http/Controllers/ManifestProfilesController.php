<?php

namespace App\Http\Controllers;

use App\Country;
use App\ManifestProfile;
use App\Service;
use Illuminate\Http\Request;

class ManifestProfilesController extends Controller
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
     * List manifest profiles.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize('view', new ManifestProfile);

        $manifestProfiles = ManifestProfile::orderBy('name', 'ASC')
                ->whereIn('depot_id', $request->user()->getDepotIds())
                ->with('carrier', 'route', 'depot')
                ->paginate(50);

        return view('manifest_profiles.index', compact('manifestProfiles'));
    }

    /**
     * Display create manifest profile form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize(new ManifestProfile);

        $services = Service::all();

        $countries = Country::all();

        return view('manifest_profiles.create', compact('manifestProfile', 'services', 'countries'));
    }

    /**
     * Store a new manifest profile.
     *
     * @param
     * @return
     */
    public function store(Request $request)
    {
        $this->authorize(new ManifestProfile);

        $manifestProfile = ManifestProfile::create($request->all());

        if (is_array($request->services)) {
            $manifestProfile->services()->sync($request->services);
        }

        if (is_array($request->countries)) {
            $manifestProfile->countries()->sync($request->countries);
        }

        flash()->success('Created!', 'Manifest profile created successfully.');

        return redirect('manifest-profiles');
    }

    /**
     * @param
     * @return
     */
    public function edit($id)
    {
        // Load the manifest profile
        $manifestProfile = ManifestProfile::findOrFail($id);

        $this->authorize($manifestProfile);

        $services = Service::all();

        $countries = Country::all();

        return view('manifest_profiles.edit', compact('manifestProfile', 'services', 'countries'));
    }

    /**
     * @param
     * @return
     */
    public function update(Request $request, $id)
    {
        $manifestProfile = ManifestProfile::findOrFail($id);

        $this->authorize($manifestProfile);

        $manifestProfile->update($request->all());

        if (is_array($request->services)) {
            $manifestProfile->services()->sync($request->services);
        } else {
            $manifestProfile->services()->detach();
        }

        if (is_array($request->countries)) {
            $manifestProfile->countries()->sync($request->countries);
        } else {
            $manifestProfile->countries()->detach();
        }

        flash()->success('Profile Updated!', 'Manifest profile updated successfully.');

        return redirect('manifest-profiles');
    }

    /**
     * Shipments available for manifesting.
     *
     * @param Request $request
     * @return type
     */
    public function run(Request $request)
    {
        // Load the manifest profile
        $manifestProfile = ManifestProfile::findOrFail($request->id);

        $this->authorize('view', $manifestProfile);

        // load the shipments available
        $shipmentsAvailable = $manifestProfile->getShipments();

        // load eligible shipments that are on hold
        $shipmentsOnHold = $manifestProfile->getShipments(1);

        $previousManifest = \App\Manifest::whereManifestProfileId($manifestProfile->id)->orderBy('id', 'desc')->first();

        $withinTimePeriod = false;

        $hour = \Carbon\Carbon::now()->timezone($request->user()->time_zone)->hour;

        if ($hour < 18) {
            $withinTimePeriod = true;
        }

        return view('manifest_profiles.shipments', compact('manifestProfile', 'shipmentsAvailable', 'shipmentsOnHold', 'previousManifest', 'withinTimePeriod'));
    }

    /**
     * Manifest shipments.
     *
     * @param type $id
     * @return type
     */
    public function runManifest(Request $request, $id)
    {
        // Load the manifest profile
        $manifestProfile = ManifestProfile::findOrFail($id);

        $this->authorize($manifestProfile);

        if ($request->append) {
            $result = $manifestProfile->run($request->manifest_id);
        } else {
            $result = $manifestProfile->run();
        }

        if ($result) {
            flash()->success('Shipments Manifested!', 'Manifest created successfully.');
        } else {
            flash()->error('Problem manifesting!', 'Unable to create manifest.');
        }

        return back();
    }

    /**
     * Bulk hold/release.
     *
     * @param Request $request
     */
    public function bulkHold(Request $request)
    {
        // Load the manifest profile
        $manifestProfile = ManifestProfile::findOrFail($request->id);

        $this->authorize($manifestProfile);

        $result = $manifestProfile->bulkHold($request->hold, $request->company_id);

        if ($result) {
            if ($request->hold) {
                flash()->success('Shipments Released!');
            } else {
                flash()->success('Shipments Held!');
            }
        } else {
            flash()->error('Invalid selection!');
        }

        return back();
    }
}
