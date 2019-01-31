<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransportAddress;

class TransportAddressesController extends Controller
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
     * List addresses.
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->search($request, false);
        }
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
        if ($request->ajax()) {
            return TransportAddress::findOrFail($id);
        }
    }

    /**
     * Handle create address form. Save the address.
     * 
     * @param AddressRequest $request
     * @return type
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            return TransportAddress::create($request->all())->id;
        }
    }

    /**
     * Handle edit address form. Updates the address record.
     * 
     * @param type $id
     * @param AddressRequest $request
     * @return type
     */
    public function update($id, Request $request)
    {
        if ($request->ajax()) {
            $address = TransportAddress::findOrFail($id);
            $address->update($request->all());
            return $address->id;
        }
    }

    /**
     * Delete an address.
     *
     * @param  
     * @return 
     */
    public function destroy($id)
    {
        return TransportAddress::findOrFail($id)->destroy($id);
    }

    /**
     * Address search.
     * 
     * @param type $request
     * @return type
     */
    private function search($request)
    {
        return TransportAddress::filter($request->filter)
                        ->hasCity($request->city)
                        ->hasCountry($request->country_code)
                        ->orderBy('name')
                        ->orderBy('company_name')
                        ->get();
    }

}
