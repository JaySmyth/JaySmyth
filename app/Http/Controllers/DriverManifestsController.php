<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarrierAPI\Pdf;
use App\Models\Driver;
use App\Models\DriverManifest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriverManifestsController extends Controller
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
     * List driver manifests.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new DriverManifest);

        $driverManifests = $this->search($request);

        return view('driver_manifests.index', compact('driverManifests'));
    }

    /**
     * Show a driver manifest.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $driverManifest = DriverManifest::findOrFail($id);

        $this->authorize($driverManifest);

        $jobsByLocation = $driverManifest->getJobsByLocation();

        return view('driver_manifests.show', compact('driverManifest', 'jobsByLocation'));
    }

    /**
     * Display create manifests form.
     *
     * @param Request $request
     * @return type
     */
    public function create(Request $request)
    {
        $this->authorize(new DriverManifest);

        $drivers = Driver::whereEnabled(1)->orderBy('name')->get();

        return view('driver_manifests.create', compact('drivers'));
    }

    /**
     * Store driver manifests.
     *
     * @param Request $request
     * @return type
     */
    public function store(Request $request)
    {
        $this->authorize(new DriverManifest);

        $this->validate($request, ['drivers' => 'required'], ['drivers.required' => 'You must check at least one driver for manifest creation!']);

        foreach ($request->drivers as $driverId => $val) :

            $driverManifest = DriverManifest::where('driver_id', $driverId)
                    ->where('vehicle_id', $request->driver[$driverId]['vehicle'])
                    ->whereBetween('date', [Carbon::parse($request->driver[$driverId]['date'])->startOfDay(), Carbon::parse($request->driver[$driverId]['date'])->endOfDay()])
                    ->where('depot_id', 1)
                    ->first();

        if (! $driverManifest) {
            DriverManifest::create([
                    'number' => \App\Models\Sequence::whereCode('DRIVER')->lockForUpdate()->first()->getNextAvailable(),
                    'driver_id' => $driverId,
                    'vehicle_id' => $request->driver[$driverId]['vehicle'],
                    'date' => strtotime($request->driver[$driverId]['date']),
                    'depot_id' => 1,
                ]);
        }

        endforeach;

        flash()->success('Created!', 'Driver manifests created successfully.');

        return redirect('driver-manifests');
    }

    /**
     * Set a driver manifest to open.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function open(Request $request, $id)
    {
        $this->authorize(new DriverManifest);

        $driverManifest = DriverManifest::findOrFail($id);
        $driverManifest->closed = false;
        $driverManifest->save();

        flash()->success('Manifest opened');

        return back();
    }

    /**
     * Close a driver manifest.
     *
     * @param Request $request
     * @param type $id
     * @return void
     */
    public function close(Request $request, $id)
    {
        $this->authorize(new DriverManifest);

        $driverManifest = DriverManifest::findOrFail($id);
        $driverManifest->close();

        flash()->success('Manifest closed');

        return back();
    }

    /**
     * Show a driver manifest.
     *
     * @param type $id
     * @return type
     */
    public function pdf(Request $request, $id)
    {
        $driverManifest = DriverManifest::findOrFail($id);

        //$this->authorize($driverManifest);

        $pdf = new Pdf($request->user()->localisation->document_size, 'I');

        return $pdf->createDriverManifest($driverManifest);
    }

    /**
     * Download POD docket.
     *
     * @param Request $request
     * @return type
     */
    public function dockets(Request $request, $id)
    {
        $driverManifest = DriverManifest::findOrFail($id);

        $this->authorize('view', $driverManifest);

        $pdf = new Pdf($request->user()->printFormat->code, 'D');

        return $pdf->createDriverManifestDockets($driverManifest);
    }

    /*
     * Purchase invoice search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request, $paginate = true)
    {
        // Default results to "today"
        if (! $request->date) {
            $request->date = Carbon::today();
        }

        $query = DriverManifest::select('driver_manifests.*')
                ->orderBy('name')
                ->filter($request->filter)
                ->hasDate($request->date)
                ->hasDriver($request->driver_id)
                ->hasVehicle($request->vehicle_id)
                ->hasDepot($request->depot_id)
                ->hasJobs()
                ->join('drivers', 'driver_manifests.driver_id', '=', 'drivers.id');

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
