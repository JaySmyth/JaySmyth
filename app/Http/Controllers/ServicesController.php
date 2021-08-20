<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ServiceRequest;
use App\CarrierAPI\Facades\CarrierAPI;
use App\Models\Company;
use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceMessage;
use Illuminate\Http\Request;
use Response;
use Auth;

class ServicesController extends Controller
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
        $serviceItems = Service::orderBy('depot_id')->orderBy('carrier_id')->orderBy('code')->get();
        foreach ($serviceItems as $service) {
            $services[$service->depot_id][$service->carrier_id][] = $service;
        }

        return view('services.index', compact('services'));
    }

    /**
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

    public function edit(Service $service, Request $request)
    {
        $this->authorize($service);

        return view('services.edit', compact('service'));
    }

    /**
     * Update a Service record.
     *
     * @param
     * @return
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize($service);

        $service->updateWithLog($request->all());

        flash()->success('Updated!', 'Service updated successfully.');

        return redirect('services');
    }

    /**
     * @param
     * @return
     */
    public function serviceMessage($id, Request $request)
    {
        $today = date('Y-m-d');
        $service = Service::findOrFail($id);
        if ($service && $request->ajax()) {
            $message = ServiceMessage::where('service_id', $id)
                ->where('valid_from', '<=', $today)
                ->where('valid_to', '>=', $today)
                ->first();
            if ($message) {
                $user = Auth::User();
                if ($message->enabled) {
                    if ($message->sticky || (! $message->sticky && $message->users()->where('user_id', $user->id)->count() == 0)) {
                        if ($message->ifs_only && !$user->hasIfsRole()) {
                            return;
                        }

                        // Insert a record to indicate that the message has been view by the user
                        $message->users()->syncWithoutDetaching($user->id);

                        return $message;
                    }
                }
            }
        }
    }

    /**
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
