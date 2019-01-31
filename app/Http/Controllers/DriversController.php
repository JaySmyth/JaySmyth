<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Http\Requests\DriverRequest;

class DriversController extends Controller
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
     * List drivers.
     *
     * @return type
     */
    public function index()
    {
        $this->authorize(new Driver);

        $drivers = Driver::orderBy('name')->paginate(50);

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Display create diver form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('index', new Driver);

        return view('drivers.create');
    }

    /**
     * Save driver record.
     *
     * @param
     * @return
     */
    public function store(DriverRequest $request)
    {
        $this->authorize('index', new Driver);

        $driver = Driver::create([
                    'name' => $request->name,
                    'enabled' => $request->enabled,
                    'telephone' => $request->telephone,
                    'vehicle_id' => ($request->vehicle_id) ? $request->vehicle_id : null,
                    'depot_id' => $request->depot_id
        ]);

        flash()->success('Created!', 'Driver created successfully.');

        return redirect('drivers');
    }

    /**
     * Display edit driver form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);

        $this->authorize('index', $driver);

        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update driver record.
     *
     * @param
     * @return
     */
    public function update(DriverRequest $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $this->authorize('index', $driver);

        $driver->update([
            'name' => $request->name,
            'enabled' => $request->enabled,
            'telephone' => $request->telephone,
            'vehicle_id' => ($request->vehicle_id) ? $request->vehicle_id : null,
            'depot_id' => $request->depot_id
        ]);

        flash()->success('Updated!', 'Driver updated successfully.');

        return redirect('drivers');
    }

}
