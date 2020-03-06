<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContainerRequest;
use App\Http\Requests\ProcessSeaFreightShipmentRequest;
use App\Http\Requests\SeaFreightShipmentRequest;
use App\Models\Models\Company;
use App\Models\SeaFreightShipment;
use Auth;
use Illuminate\Http\Request;

class SeaFreightShipmentController extends Controller
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
     * List customs entries.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new SeaFreightShipment);

        $shipments = $this->search($request);

        return view('sea_freight_shipments.index', compact('shipments'));
    }

    /**
     * Display a customs entry.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        return view('sea_freight_shipments.show', compact('shipment'));
    }

    /**
     * Displays new entry form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize(new SeaFreightShipment);

        return view('sea_freight_shipments.create');
    }

    /**
     * Save the customs entry.
     *
     * @param
     * @return
     */
    public function store(SeaFreightShipmentRequest $request)
    {
        $this->authorize(new SeaFreightShipment);

        $additionalFields = [
            'number' => nextAvailable('SEA'),
            'user_id' => $request->user()->id,
            'depot_id' => Company::find($request->company_id)->depot_id,
        ];

        $shipment = SeaFreightShipment::create(array_merge($request->all(), $additionalFields));

        $shipment->setStatus('new', $request->user()->id);

        flash()->success('Shipment Created!', 'Shipment created successfully.');

        return redirect('sea-freight');
    }

    /**
     * Displays edit entry form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        return view('sea_freight_shipments.edit', compact('shipment'));
    }

    /**
     * Updates an existing entry.
     *
     * @param
     * @return
     */
    public function update(SeaFreightShipmentRequest $request, $id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        // Update the customs entry
        $shipment->update($request->all());

        flash()->success('Updated!', 'Shipment updated successfully.');

        return redirect('sea-freight');
    }

    /**
     * Displays edit entry form.
     *
     * @param
     * @return
     */
    public function process($id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        if ($shipment->processed && $shipment->containers->count() < $shipment->number_of_containers) {
            return redirect('sea-freight/'.$shipment->id.'/add-container');
        }

        return view('sea_freight_shipments.process', compact('shipment'));
    }

    /**
     * Updates an existing entry.
     *
     * @param
     * @return
     */
    public function storeProcess(ProcessSeaFreightShipmentRequest $request, $id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize('process', $shipment);

        // Update the Shipment
        $shipment->update(array_merge($request->all(), ['processed' => 1]));

        $shipment->setStatus('booking', $request->user()->id);

        flash()->success('Processed!', 'Shipment processed successfully.');

        return redirect('sea-freight/'.$shipment->id.'/add-container');
    }

    /**
     * Displays add commodity form.
     *
     * @param int $id
     * @return
     */
    public function addContainer($id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        if ($shipment->containers->count() >= $shipment->number_of_containers) {
            flash()->error('Already completed!', 'Containers have already been added to this shipment.');

            return back();
        }

        return view('sea_freight_shipments.add_container', compact('shipment'));
    }

    /**
     * Saves the commodity line.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function storeContainer(ContainerRequest $request, $id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize('addContainer', $shipment);

        $shipment->containers()->create($request->all());

        if ($shipment->containers->count() >= $shipment->number_of_containers) {
            flash()->success('Processing Finished!', 'Container added to shipment.');

            return redirect('sea-freight');
        }

        flash()->success('Container added!', 'Container added to shipment.');

        return back();
    }

    /**
     * Displays edit commodity form.
     *
     * @param
     * @return
     */
    public function editContainer($id, $containerId)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize('addContainer', $shipment);

        $container = \App\Models\Models\Container::findOrFail($containerId);

        return view('sea_freight_shipments.edit_container', compact('shipment', 'container'));
    }

    /**
     * Updates an existing user.
     *
     * @param
     * @return
     */
    public function updateContainer(ContainerRequest $request, $id, $containerId)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize('addContainer', $shipment);

        $container = \App\Models\Models\Container::findOrFail($containerId);

        // Update the container
        $container->update($request->all());

        flash()->success('Updated!', 'Container updated successfully.');

        return redirect('sea-freight/'.$shipment->id);
    }

    /**
     * Displays edit seal number form.
     *
     * @param
     * @return
     */
    public function editSeal($id, $containerId)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $container = \App\Models\Models\Container::findOrFail($containerId);

        if ($container->seal_number) {
            flash()->error('Already completed!', 'Seal number has already been set.');

            return back();
        }

        return view('sea_freight_shipments.edit_seal', compact('shipment', 'container'));
    }

    /**
     * Updates seal number.
     *
     * @param
     * @return
     */
    public function updateSeal(Request $request, $id, $containerId)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $container = \App\Models\Models\Container::findOrFail($containerId);

        $this->validate($request, [
            'seal_number' => 'required',
        ]);

        // Update the container
        $container->update($request->all());

        flash()->success('Updated!', 'Seal number updated successfully.');

        return redirect('sea-freight/'.$shipment->id);
    }

    /**
     * Displays edit entry form.
     *
     * @param
     * @return
     */
    public function status($id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize($shipment);

        $statuses = \App\Models\SeaFreightStatus::where('id', '>', $shipment->sea_freight_status_id)->select('name', 'id')->orderBy('id')->pluck('name', 'id');

        //$lastDatetime = $shipment->tracking->first()->datetime->subDay()->toDateString();
        $lastDatetime = '-6 months';

        $dates = getDates($lastDatetime, 'today');

        return view('sea_freight_shipments.status', compact('shipment', 'dates', 'statuses'));
    }

    /**
     * Updates an existing entry.
     *
     * @param
     * @return
     */
    public function updateStatus(Request $request, $id)
    {
        $shipment = SeaFreightShipment::findOrFail($id);

        $this->authorize('status', $shipment);

        $shipment->setStatus($request->sea_freight_status_id, $request->user()->id, gmtToCarbonUtc($request->date.' '.$request->time), $request->message);

        flash()->success('Status updated!', 'Shipment status updated.');

        return redirect('sea-freight/'.$shipment->id);
    }

    /**
     * Set a shipment to cancelled.
     *
     * @param
     * @return
     */
    public function cancel(Request $request, $id)
    {
        if ($request->ajax()) {
            $shipment = SeaFreightShipment::findOrFail($id);

            $this->authorize($shipment);

            $shipment->setStatus('cancelled', $request->user()->id);
        }
    }

    /*
     * Customs entry search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request, $paginate = true)
    {
        $query = SeaFreightShipment::orderBy('id', 'DESC')
                ->filter($request->filter)
                ->dateBetween($request->date_from, $request->date_to)
                ->hasCompany($request->company)
                ->hasStatus($request->status)
                ->restrictCompany($request->user()->getAllowedCompanyIds());

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
