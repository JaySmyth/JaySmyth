<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Vehicle;
use App\Http\Requests\VehicleRequest;

class VehiclesController extends Controller
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
     * List vehicles.
     *
     * @param Request $request
     * @return type
     */
    public function index()
    {
        $this->authorize(new Vehicle);

        $vehicles = Vehicle::orderBy('registration')->paginate(50);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Display create vehicle record.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('index', new Vehicle);

        return view('vehicles.create');
    }

    /**
     * Save vehicle record.
     *
     * @param
     * @return
     */
    public function store(VehicleRequest $request)
    {
        $this->authorize('index', new Vehicle);

        $vehicle = Vehicle::create($request->all());

        flash()->success('Created!', 'Vehicle created successfully.');

        return redirect('vehicles');
    }

    /**
     * Display edit vehicle form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $this->authorize('index', $vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update vehicle record.
     *
     * @param
     * @return
     */
    public function update(VehicleRequest $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $this->authorize('index', $vehicle);

        $vehicle->update($request->all());

        flash()->success('Updated!', 'Vehicle updated successfully.');

        return redirect('vehicles');
    }

}
