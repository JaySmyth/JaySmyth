<?php

namespace App\Http\Controllers;

use App\CarrierAPI\Facades\CarrierAPI;
use App\Models\Company;
use App\Models\Service;
use App\Models\ServiceMessage;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Response;

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
     * List services.
     *
     * @param  Request  $request
     *
     * @return Factory|Application|View
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
     * Show a service.
     *
     * @param $id
     * @param  Request  $request
     *
     * @return Factory|Application|View
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
     * Get service message.
     *
     * @param $id
     * @param  Request  $request
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
                        if ($message->ifs_only && ! $user->hasIfsRole()) {
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
     * Get services available.
     *
     * @param  Request  $request
     *
     * @return JsonResponse|void
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

    /**
     * Show edit form.
     *
     * @param  Service  $service
     * @param  Request  $request
     *
     * @return Factory|Application|View
     * @throws AuthorizationException
     */
    public function edit(Service $service, Request $request)
    {
        $this->authorize($service);

        return view('services.edit', compact('service'));
    }


    /**
     * Update a Service record.
     *
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize($service);

        $service->updateWithLog($request->all());

        flash()->success('Updated!', 'Service updated successfully.');

        return redirect('services');
    }
}
