<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Preferences Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles requests from authenticated users to set, update
      | and delete their shipping preferences (default values). All requests are
      | expected to come via an ajax call.
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
     * Sets an authenticated user's shipping preferences for a given company and mode.
     * Expects all requests via ajax call.
     *
     * @param  Illuminate\Http\Request
     * @return void
     */
    public function setPreferences(Request $request)
    {
        if ($request->ajax()) {
            $request->user()->setPreferences($request->company_id, $request->mode_id, $request->values);
        }
    }

    /**
     * Gets an authenticated user's shipping preferences for a given company and mode.
     * Expects all requests via ajax call.
     *
     * @param   Illuminate\Http\Request
     * @return  json data array
     */
    public function getPreferences(Request $request)
    {
        if ($request->ajax()) {
            return $request->user()->getPreferences($request->company_id, $request->mode_id);
        }
    }

    /**
     * Deletes an authenticated user's shipping preferences for a given company and mode.
     * Expects all requests via ajax call.
     *
     * @param  Illuminate\Http\Request
     * @return void
     */
    public function resetPreferences(Request $request)
    {
        if ($request->ajax()) {
            $request->user()->resetPreferences($request->company_id, $request->mode_id);
        }
    }
}
