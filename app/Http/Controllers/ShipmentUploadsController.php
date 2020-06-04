<?php

namespace App\Http\Controllers;

use App\Models\ShipmentUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ShipmentUploadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('ifsAdmin');
    }

    /**
     * List uploads.
     *
     * @param Request $request
     * @return type
     */
    public function index()
    {
        $shipmentUploads = ShipmentUpload::orderBy('id', 'ASC')->paginate(50);

        return view('shipment_uploads.index', compact('shipmentUploads'));
    }

    /**
     * Display an upload.
     *
     * @param type $id
     * @return type
     */
    public function show(ShipmentUpload $shipmentUpload)
    {
        return view('shipment_uploads.show', compact('shipmentUpload'));
    }

    /**
     * Displays create form.
     *
     * @param
     * @return
     */
    public function create()
    {
        return view('shipment_uploads.create');
    }

    /**
     * Save the upload.
     *
     * @param
     * @return
     */
    public function store(Request $request)
    {
        $request->validate([
            'directory' => 'required|min:8|max:150|unique:shipment_uploads,directory,'.$request->directory,
            'import_config_id' => 'required',
        ]);

        $shipmentUpload = ShipmentUpload::create(Arr::except($request->all(), ['create_sftp_account']));

        $shipmentUpload->log();

        if ($request->create_sftp_account) {
            if (! file_exists($shipmentUpload->directory)) {
                $username = substr($shipmentUpload->directory, 6, stripos($shipmentUpload->directory, '/uploads') - 6);
            }
        }

        flash()->success('Shipment Upload Created!', 'Upload config created successfully.');

        return redirect('shipment-uploads');
    }

    /**
     * Displays edit form.
     *
     * @param
     * @return
     */
    public function edit(ShipmentUpload $shipmentUpload)
    {
        return view('shipment_uploads.edit', compact('shipmentUpload'));
    }

    /**
     * Updates an existing entry.
     *
     * @param
     * @return
     */
    public function update(ShipmentUpload $shipmentUpload, Request $request)
    {
        $request->validate([
            'directory' => 'required|min:8|max:150',
            'import_config_id' => 'required',
        ]);

        $shipmentUpload->updateWithLog($request->all());

        flash()->success('Updated!', 'Config updated successfully.');

        return redirect('shipment-uploads');
    }

    /**
     * Delete.
     *
     * @param Quotation $quotation
     * @return string
     */
    public function destroy(ShipmentUpload $shipmentUpload)
    {
        $shipmentUpload->delete();

        return response()->json(null, 204);
    }
}
