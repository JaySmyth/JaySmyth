<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Country;
use App\Company;
use App\CarrierAPI\Facades\CarrierAPI;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests;
use Response;

class ServicesController extends Controller {

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
     * 
     *
     * @param  
     * @return 
     */
    public function index(Request $request)
    {
        
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function show($id, Request $request)
    {
        $service = Service::findOrFail($id);

        if ($request->ajax()) {
            return $service;
        }

        return view('service.show', compact('service'));
    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function available(Request $request)
    {
        if ($request->ajax()) {

            // Parse the serialized form data into an array
            parse_str($request->data, $data);

            // Get Company details
            $company = Company::find($data['company_id']);

            // Get the services available from the data provided
            $services = CarrierAPI::getAvailableServices($data);

            if (strtolower($company && $company->carrier_choice) == 'all') {

                // If carrier_choice for company set as "all" then return all fields
                return response()->json($services);
            } else {

                // Define fields we wish to return to the user
                $required = ['id', 'code', 'volumetric_divisor', 'name', 'price', 'price_currency', 'price_detail'];
                $reply = [];

                if ($services) {
                    foreach ($services as $service) {

                        // For each service return only the required data
                        foreach ($required as $value) {
                            $temp[$value] = $service[$value];
                        }
                        $reply[] = $temp;
                    }
                } else {
                    $reply = [];
                }

                return response()->json($reply);
            }
        }
    }

}
