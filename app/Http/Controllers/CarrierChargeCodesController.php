<?php

namespace App\Http\Controllers;

use App\Models\Models\CarrierChargeCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarrierChargeCodesController extends Controller
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
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize(new CarrierChargeCode);

        $carrierChargeCodes = $this->search($request);

        return view('carrier_charge_codes.index', compact('carrierChargeCodes'));
    }

    /**
     * @param
     * @return
     */
    public function edit($id)
    {
        $carrierChargeCode = CarrierChargeCode::findOrFail($id);

        $this->authorize('viewAny', $carrierChargeCode);

        return view('carrier_charge_codes.edit', compact('carrierChargeCode'));
    }

    /**
     * @param
     * @return
     */
    public function update(Request $request, $id)
    {
        $carrierChargeCode = CarrierChargeCode::findOrFail($id);

        $this->authorize('viewAny', $carrierChargeCode);

        $carrierChargeCode->scs_code = $request->scs_code;
        $carrierChargeCode->description = $request->description;
        $carrierChargeCode->save();

        flash()->success('Updated!', 'Carrier charge code updated');

        return redirect('carrier-charge-codes');
    }

    /*
     * User search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request)
    {
        return CarrierChargeCode::orderBy('code')
                        ->filter($request->filter)
                        ->hasCarrier($request->carrier)
                        ->hasScsCode($request->scs_code)
                        ->paginate(50);
    }
}
