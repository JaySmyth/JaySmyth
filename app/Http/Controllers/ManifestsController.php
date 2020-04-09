<?php

namespace App\Http\Controllers;

use App\Models\CarrierAPI\Pdf;
use App\Models\Manifest;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ManifestsController extends Controller
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
     * List manifests.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize('view', new Manifest);

        $manifests = $this->search($request);

        return view('manifests.index', compact('manifests'));
    }

    /**
     * Display a manifest.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize($manifest);

        $services = $manifest->getServicesArray();

        $shipmentsByService = [];

        // Loop through the shipments, group them by service and total
        foreach ($manifest->shipments as $shipment) {
            $shipmentsByService[$shipment->service_id]['shipments'][] = $shipment;
            inc($shipmentsByService[$shipment->service_id]['pieces'], $shipment->pieces);
            inc($shipmentsByService[$shipment->service_id]['weight'], $shipment->weight);
        }

        return view('manifests.show', compact('manifest', 'shipmentsByService', 'services'));
    }

    /**
     * Displays add shipment form. Allows a shipment to be added to a manifest
     * after it has been closed out.
     *
     * @param type $id  manifest id
     * @return
     */
    public function addShipment($id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize($manifest);

        return view('manifests.add', compact('manifest'));
    }

    /**
     * Updates the manifest id on a single shipment. Called from add shipment.
     *
     * @param Request $request
     * @param int $id   manifest id
     * @return void
     */
    public function storeShipment(Request $request, $id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize('addShipment', $manifest);

        $this->validate($request, [
            'consignment_number' => 'required',
        ]);

        // load the shipment record from the consignment number
        $shipment = Shipment::whereConsignmentNumber($request->consignment_number)->whereReceived(1)->first();

        if (! $shipment) {
            return redirect()->back()->withInput()->withErrors(['consignment_number' => 'Invalid consignment number or shipment has not been received by IFS.']);
        }

        if ($shipment->manifest_id == $manifest->id) {
            return redirect()->back()->withInput()->withErrors(['consignment_number' => 'Consignment number already on this manifest.']);
        }

        $shipment->manifest_id = $manifest->id;
        $shipment->update();

        flash()->success('Shipment added!', 'Shipment added to manifest.');

        return redirect('manifests');
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param  Request
     * @return Excel document
     */
    public function download(Request $request, $id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize('view', $manifest);

        return Excel::download(new \App\Exports\ManifestExport($manifest, $request->user()), $manifest->number.'.xlsx');
    }

    /**
     * Download manifest PDF.
     *
     * @param type $id
     * @return type
     */
    public function pdf(Request $request, $id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize('view', $manifest);

        $pdf = new Pdf($request->user()->localisation->document_size, 'D');

        return $pdf->createManifest($manifest);
    }

    /**
     * Display a summary screen detailing the shippers.
     *
     * @param type $id
     * @return type
     */
    public function summary($id)
    {
        $manifest = Manifest::findOrFail($id);

        $this->authorize('view', $manifest);

        $shippers = Shipment::whereManifestId($id)
                ->select(DB::raw('count(*) as total, users.name, users.telephone, users.email, shipments.company_id, shipments.sender_company_name'))
                ->join('users', 'shipments.user_id', '=', 'users.id')
                ->groupBy('user_id')
                ->orderBy('sender_company_name')
                ->get();

        return view('manifests.summary', compact('manifest', 'shippers'));
    }

    /*
     * Manifest search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    private function search($request)
    {
        return Manifest::orderBy('created_at', 'DESC')
                        ->filter($request->filter)
                        ->hasProfile($request->manifest_profile_id)
                        ->hasDepot($request->depot_id)
                        ->hasCarrier($request->carrier_id)
                        ->dateBetween($request->date_from, $request->date_to)
                        ->whereIn('depot_id', $request->user()->getDepotIds())
                        ->with('shipments', 'manifestProfile', 'carrier', 'depot')
                        ->paginate(50);
    }
}
