<?php

namespace App\Http\Controllers;

use Auth;
use App\SeaFreightTracking;
use Illuminate\Http\Request;

class SeaFreightTrackingController extends Controller
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
     * Displays edit form.
     *
     * @param
     * @return
     */
    public function edit(SeaFreightTracking $seaFreightTracking)
    {
        $this->authorize(new SeaFreightTracking);

        return view('sea_freight_shipments.edit_tracking', compact('seaFreightTracking'));
    }

    /**
     * Updates an existing entry.
     *
     * @param
     * @return
     */
    public function update(SeaFreightTracking $seaFreightTracking, Request $request)
    {
        $this->authorize($seaFreightTracking);

        $attributes = [
            'datetime' => gmtToCarbonUtc($request->date . ' ' . $request->time),
            'message' => $request->message
        ];

        $seaFreightTracking->update($attributes);

        flash()->success('Updated!', 'Tracking updated successfully.');

        return redirect('sea-freight/' . $seaFreightTracking->seaFreightShipment->id);
    }

}
