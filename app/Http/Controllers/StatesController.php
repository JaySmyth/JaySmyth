<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\State;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | States Controller
      |--------------------------------------------------------------------------
      |
      |
      |
      |
      |
     */

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
     * Gets the states for a given country code.
     * Expects all requests via ajax call.
     *
     * @param   Illuminate\Http\Request
     * @return  json data array
     */
    public function getStates(Request $request)
    {
        if ($request->ajax()) {
            return State::where('country_code', $request->country_code)->orderBy('name')->pluck('name', 'code');
        }
    }
}
