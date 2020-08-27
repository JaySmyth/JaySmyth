<?php

namespace App\Http\Controllers;

use App\Exports\DimsExport;
use App\Exports\ShipmentsExport;
use App\Exports\ExceptionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\ImportShipments;
use App\Jobs\LogScanningKpis;
use App\Models\Mode;
use App\CarrierAPI\Facades\CarrierAPI;
use App\CarrierAPI\Pdf;
use App\Models\Company;
use App\Models\Shipment;
use App\Models\TransactionLog;
use App\Models\User;
use App\Pricing\Pricing;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ShipmentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'label',
                'labels',
                'batchedCommercialInvoicesBySourcePdf',
                'batchedDespatchNotesBySourcePdf',
                'saveShipment',
            ],
        ]);
    }

    /**
     * List shipments.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new Shipment);
        $company = null;
        $user = null;

        if ($request->company) {
            $company = Company::findOrFail($request->company);
        }

        if ($request->user) {
            $user = User::findOrFail($request->user);
        }

        $shipments = $this->search($request);

        return view('shipments.index', compact('shipments', 'company', 'user'));
    }

    private function search($request, $paginate = true, $limit = false)
    {
        // Default results to "today" for IFS staff to limit large result set
        if ($request->user()->hasIfsRole() && strlen($request->filter) < 5 && ! $request->date_from && ! $request->date_to && ! $request->company && ! $request->user && ! $request->scs_job_number) {
            $request->date_from = Carbon::today();
            $request->date_to = Carbon::today();
        }

        $query = Shipment::select('shipments.*')
            ->orderBy('created_at', 'DESC')
            ->orderBy('shipments.id', 'DESC')
            ->filter($request->filter)
            ->hasScsJobNumber($request->scs_job_number)
            ->hasManifestNumber($request->manifest_number)
            ->hasMode($request->mode)
            ->hasCompany($request->company)
            ->hasStatus($request->status)
            ->hasSource($request->source)
            ->hasDestination($request->destination)
            ->hasRecipientType($request->recipient_type)
            ->hasPieces($request->pieces)
            ->hasService($request->service)
            ->traffic($request->traffic)
            ->hasCarrier($request->carrier)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->createdBy($request->user)
            ->recipientFilter($request->recipient_filter)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->restrictMode($request->user()->getAllowedModeIds())
            ->with('service', 'status', 'department', 'mode', 'company', 'depot');

        if ($request->has('received')) {
            $query->where('received', $request->received);
        }

        if ($limit) {
            $query->limit($limit);
        }

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

    /**
     * Show a shipment.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $shipment = Shipment::findOrFail($id);

        $this->authorize($shipment);

        return view('shipments.show', compact('shipment'));
    }

    /**
     * Show a shipment - form view.
     *
     * @param type $id
     * @return type
     */
    public function formView($id)
    {
        $shipment = Shipment::findOrFail($id);

        if (! $shipment->formViewAvailable()) {
            flash()->error('Not available!', 'Sorry, form view not available for this shipment.', true);

            return back();
        }

        $this->authorize('view', $shipment);

        $mode = Mode::findOrFail($shipment->mode_id);

        // Load the values to pass to the form into an array
        $arrays = $this->prepareForm($mode, $shipment->company_id);

        $formView = true;

        return view('shipments.create', compact('shipment', 'mode', 'arrays', 'formView'));
    }

    /**
     * Returns the values to pass to the create/edit shipment form.
     *
     * @param   $mode
     * @param   $shipment
     * @return  multidimensional array
     */
    private function prepareForm($mode, $companyId = null)
    {
        // Use previous form submission values or default values
        $packages = null !== old('packages') ? old('packages') : [
            0 => [
                'packaging_code' => '',
                'weight' => '',
                'length' => '',
                'width' => '',
                'height' => ''
            ]
        ];
        $contents = null !== old('contents') ? old('contents') : [];

        if (! $companyId) {
            // If old company ID doesn't exist, default to authenticated user's company id
            $companyId = null !== old('company_id') ? old('company_id') : Auth::user()->company_id;
        }

        $company = Company::findOrFail($companyId);
        $packaging = $company->getPackagingTypes($mode->id)->pluck('description', 'code');
        $localisation = $company->localisation;

        return compact('packages', 'contents', 'packaging', 'localisation');
    }

    /**
     * Display create shipment form.
     *
     * @param Request $request
     * @return type
     */
    public function create(Request $request)
    {
        if ($request->user()->getOnlyMode() == 'sea') {
            return redirect('sea-freight/create');
        }

        // Determine which mode we will be generating a shipment against
        $mode = Mode::getMode($request->mode);

        $this->authorize(new Shipment);

        // Load the values to pass to the form into an array
        $arrays = $this->prepareForm($mode);

        return view('shipments.create', compact('mode', 'arrays'));
    }

    /**
     * Create shipment request - calls API.
     *
     * @param Request $request
     * @return type
     */
    public function store(Request $request)
    {
        $shipment = ($request->shipment_id) ? Shipment::findOrFail($request->shipment_id) : new Shipment;

        $this->authorize($shipment);

        if ($request->shipment_id && $shipment->status_id != 1) {
            flash()->error('Attention!', 'Saved shipment already processed', true);

            return redirect('shipments/create');
        }

        $response = CarrierAPI::createShipment($request->all());

        if (isset($response['errors']) && $response['errors'] != []) {
            $route = ($request->shipment_id) ? 'shipments/' . $request->shipment_id . '/edit' : 'shipments/create';
            flash()->error('Errors found!', $response['errors'], true);

            return redirect($route)->withInput();
        }

        return redirect('shipments/create')->with('token', $response['token']);
    }

    /**
     * Saved shipment form.
     *
     * @param type $id
     * @return type
     */
    public function edit(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $this->authorize($shipment);

        $mode = Mode::findOrFail($shipment->mode_id);

        // Load the values to pass to the form into an array
        $arrays = $this->prepareForm($mode, $shipment->company_id);

        return view('shipments.create', compact('shipment', 'mode', 'arrays'));
    }

    /**
     * Save a new shipment or update existing.
     *
     * @param Request $request
     * @return type
     */
    public function save(Request $request)
    {
        if ($request->ajax()) {
            $shipment = ($request->shipment_id) ? Shipment::findOrFail($request->shipment_id) : new Shipment;

            $this->authorize('update', $shipment);

            // Serialized form string to array
            parse_str($request->values, $values);

            // Flatten the multi-dimensional array into 1D array using dot notation
            $values = Arr::dot($values);

            $shipment->recipient_name = $request->recipient_name;
            $shipment->recipient_company_name = $request->recipient_company_name;
            $shipment->recipient_city = $request->recipient_city;
            $shipment->recipient_country_code = $request->recipient_country_code;
            $shipment->pieces = $request->pieces;
            $shipment->shipment_reference = $request->shipment_reference;
            $shipment->user_id = $request->user()->id;
            $shipment->company_id = $request->company_id;
            $shipment->mode_id = $request->mode_id;
            $shipment->collection_date = Carbon::parse($request->collection_date);
            $shipment->ship_date = Carbon::parse($request->collection_date);
            $shipment->form_values = json_encode($values);
            $shipment->status_id = 1;
            $shipment->depot_id = 1;
            $shipment->route_id = 1;
            $shipment->department_id = 1;
            $shipment->carrier_id = 1;
            $shipment->service_id = 1;

            // New shipment (not an update), set the consignment number
            if ($shipment->save() && ! is_numeric($request->shipment_id)) {
                $shipment->consignment_number = nextAvailable('CONSIGNMENT');
                $shipment->save();
            }

            return json_encode(['shipment_id' => $shipment->id, 'consignment_number' => $shipment->consignment_number]);
        }
    }

    /**
     * Ajax call for saved shipment.
     *
     * @param Request $request
     * @return type
     */
    public function getSaved(Request $request)
    {
        if ($request->ajax()) {
            $shipment = Shipment::findOrFail($request->id);

            $this->authorize('update', $shipment);

            return $shipment->form_values;
        }
    }

    /**
     * Display update DIMs page.
     *
     * @param
     * @return
     */
    public function dims(Request $request)
    {
        $this->authorize(new Shipment);

        $dateFrom = new Carbon($request->date_from);
        $dateTo = new Carbon($request->date_to);

        $shipments = Shipment::orderBy('created_at', 'DESC')
            ->orderBy('shipments.id', 'DESC')
            ->whereNull('supplied_volumetric_weight')
            ->filter($request->filter)
            ->hasMode($request->mode)
            ->hasDepot($request->depot)
            ->hasService($request->service)
            ->hasCompany($request->company)
            ->shipDateBetween($dateFrom, $dateTo)
            ->with('service', 'packages', 'company')
            ->paginate(50);

        return view('shipments.update_dims', compact('shipments'));
    }

    /**
     * Update a shipment's DIMS.
     *
     * @param Request $request
     * @param type $id
     * @return string
     */
    public function updateDims(Request $request, $id)
    {
        $this->authorize('dims', new Shipment);

        if ($request->ajax()) {
            $shipment = Shipment::findOrFail($id);

            parse_str($request->packages, $array);

            foreach ($array['packages'] as $values) {
                foreach ($values as $value) {
                    if (! is_numeric($value)) {
                        return 'error';
                    }
                }
            }

            $weight = 0;
            $volumetricWeight = 0;
            $packages = [];

            foreach ($shipment->packages as $package) {
                if (! $shipment->supplied_weight || ! $shipment->supplied_volumetric_weight) {
                    $package->supplied_length = $package->length;
                    $package->supplied_width = $package->width;
                    $package->supplied_height = $package->height;
                    $package->supplied_weight = $package->weight;
                    $package->supplied_volumetric_weight = $package->volumetric_weight;
                }

                $package->length = $array['packages'][$package->index]['length'];
                $package->width = $array['packages'][$package->index]['width'];
                $package->height = $array['packages'][$package->index]['height'];
                $package->weight = $array['packages'][$package->index]['weight'];
                $package->volumetric_weight = calcVolume(
                    $package->length,
                    $package->width,
                    $package->height,
                    $shipment->volumetric_divisor
                );

                $weight += $package->weight;
                $volumetricWeight += $package->volumetric_weight;

                $packages[] = $package->toArray();
                $package->save();
            }

            if (! $shipment->supplied_weight || ! $shipment->supplied_volumetric_weight) {
                $shipment->supplied_weight = $shipment->weight;
                $shipment->supplied_volumetric_weight = $shipment->volumetric_weight;
            }

            $shipment->weight = $weight;
            $shipment->volumetric_weight = $volumetricWeight;

            // Build Shipment array for repricing
            $shipmentArray = $shipment->toArray();
            $shipmentArray['packages'] = $packages;

            // Reprice Shipment with new dims etc.
            $pricing = new Pricing();
            $price = $pricing->price($shipmentArray, $shipmentArray['service_id']);
            $shipment->quoted = json_encode($price);
            $shipment->shipping_charge = $price['shipping_charge'];
            $shipment->shipping_cost = $price['shipping_cost'];
            $shipment->cost_currency = $price['cost_currency'];
            $shipment->sales_currency = $price['sales_currency'];

            $shipment->save();

            $shipment->log('DIMS updated');

            return 'success';
        }
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
            $shipment = Shipment::findOrFail($id);

            $this->authorize($shipment);

            if ($shipment->status->code == 'saved') {
                return $shipment->destroy($id);
            }

            return $shipment->setCancelled(Auth::user()->id);
        }
    }

    /**
     * Set a shipment to hold.
     *
     * @param
     * @return
     */
    public function hold($id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->authorize($shipment);
        $shipment->toggleHold(Auth::user()->id);

        return back();
    }

    /**
     * Set shipment to received (web scan).
     *
     * @param Request $request
     * @param type $id
     */
    public function receive(Request $request, $id)
    {
        if ($request->ajax()) {
            $shipment = Shipment::findOrFail($id);
            $this->authorize($shipment);
            $shipment->setReceived(null, Auth::user()->id);
            $shipment->addTracking($shipment->status->code, false, 0, 'Shipment received (web scan)');
        }
    }

    /**
     * Download PDF label for the shipment. Allows unauthenticated guest access.
     * Print format parameter useful for specifying label size in emailed URL for example.
     * This is required as we cannot access print format for an unauthenticated user.
     *
     * @param string $token
     * @param string $printFormatCode
     * @return type
     */
    public function label($token, $printFormatCode = 'A4')
    {
        // user authenticated so use their preferred label size
        if (! Auth::guest()) {
            // warehouse login
            if (in_array(Auth::user()->id, [3026, 283, 2534])) {
                flash()->error('Warning', 'Not authorised to print label.', true);

                return back();
            }

            $printFormatCode = Auth::user()->printFormat->code;
        }

        // Log the event (model loaded as code below expects a collection)
        $shipment = Shipment::whereToken($token)->firstOrFail();

        if (Carbon::now()->subSeconds(5) > $shipment->created_at) {
            $shipment->log('Downloaded Label');
        }

        // load the shipment model from the token
        $shipment = Shipment::whereToken($token)->get();

        // call the API for the label - download to browser
        $docs = $this->getDocs($shipment, $printFormatCode, '');

        return $this->docResponse($docs);
    }

    public function getDocs($shipment, $printFormatCode = 'A4', $docSet = '')
    {
        $pdf = new Pdf($printFormatCode, 'D');

        $labels = $pdf->createShippingDocs($shipment, $docSet);

        return $this->docResponse($labels);
    }

    public function docResponse($labels)
    {
        if ($labels == 'not found') {
            // 404 for guests
            if (Auth::guest()) {
                abort(404);
            }

            // flash message for authenticatd user
            flash()->error('Cannot find label!', 'Sorry, label(s) not available for this shipment.', true);

            return back();
        } else {
            return $labels;
        }
    }

    /**
     * Download Batch of PDF labels.
     *
     * @param  $token   Unique shipment file upload identifier
     * @return pdf document
     */
    public function labels($source, $userId, $labelType = '')
    {
        $user = Auth::user();

        if (empty($user)) {
            // Load User that generated the labels
            $user = User::findOrFail($userId);
        }

        // Load the shipment model from the token ensuring in same order as created
        $shipments = Shipment::whereSource($source)->orderBy('id')->get();

        return $this->getDocs($shipments, $user->printFormat->code, $labelType);
    }

    /**
     * Download PDF commercial invoice for the shipment.
     *
     * @param  $token   Unique consignment identifier
     * @return pdf document
     */
    public function commercialInvoice($token, Request $request)
    {
        // Load the shipment from the token
        $shipment = Shipment::whereToken($token)->firstOrFail();

        // Build an array of any dynamic parameters we have been passed
        $parameters = [
            'type' => $request->type,
            'ultimate_destination' => $request->ultimate_destination,
            'comments' => $request->comments,
            'incoterm' => $request->incoterm,
        ];

        // Call the API for invoice
        if (! $commercialInvoice = CarrierAPI::getCommercialInvoice(
            $token,
            $parameters,
            Auth::user()->localisation->document_size,
            'D'
        )) {
            abort(404);
        }
    }

    /**
     * Display upload shipments form.
     *
     * @param Request $request
     * @return type
     */
    public function upload()
    {
        $this->authorize(new Shipment);

        return view('shipments.upload');
    }

    /**
     * Process uploaded shipment file.
     *
     * @param Request $request
     * @return type
     */
    public function storeUpload(Request $request)
    {
        $this->authorize('upload', new Shipment);

        // Validate the request
        $this->validate($request, ['import_config_id' => 'required|numeric', 'file' => 'required'], [
            'import_config_id.required' => 'Please select an upload profile.',
            'file.mimes' => 'Not a valid CSV file - please check for unsupported characters',
            'file.required' => 'Please select a file to upload.'
        ]);

        // Upload the file to the temp directory
        $path = $request->file('file')->storeAs('temp', 'original_' . Str::random(12) . '.csv');

        // Generate a filename (hash generated from file contents)
        $filename = 'temp/' . md5_file(storage_path('app/' . $path)) . '.csv';

        // Check that this file has not already been uploaded
        if (Storage::exists($filename)) {
            // Delete the duplicate
            Storage::delete($path);

            flash()->error('File already uploaded!');
            return back();
        }

        // Rename the temp upload to the hashed filename
        Storage::move($path, $filename);

        // Check that the file was uploaded successfully
        if (! Storage::disk('local')->exists($filename)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');
            return back();
        }

        dispatch(new ImportShipments(
            storage_path('app/' . $filename),
            $request->import_config_id,
            $request->user()
        ))->onQueue('import');

        // Notify user and redirect
        flash()->info('File Uploaded!', 'Please check your email for results.', true);

        return back();
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize(new Shipment);

        $shipments = $this->search($request, false, 5000);

        return Excel::download(new ShipmentsExport($shipments), 'shipments.xlsx');
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function downloadDims(Request $request)
    {
        $this->authorize('dims', new Shipment);

        $shipments = $this->search($request, false, 2000);

        return Excel::download(new DimsExport($shipments), 'dims.xlsx');
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function downloadExceptions(Request $request)
    {
        $this->authorize('viewAny', new Shipment);

        if ($request->status) {
            $status = [$request->status];
        } else {
            $status = [8, 9, 10, 11, 12, 17];
        }

        $shipments = Shipment::orderBy('ship_date', 'DESC')
            ->filter($request->filter)
            ->hasCompany($request->company)
            ->shipDateBetween($request->date_from, $request->date_to)
            ->hasMode(1)
            ->where('service_id', '<>', 4)
            ->whereReceived(1)
            ->whereIn('status_id', $status)
            ->traffic($request->traffic)
            ->hasService($request->service)
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->with('service')
            ->paginate(250);

        return Excel::download(new ExceptionsExport($shipments), 'exceptions.xlsx');
    }

    /**
     * Download collection manifest PDF.
     *
     * @param Request $request
     * @return type
     */
    public function collectionManifestPdf(Request $request)
    {
        $this->authorize('viewAny', new Shipment);

        $shipments = Shipment::orderBy('ship_date', 'DESC')
            ->hasCompany($request->company)
            ->hasStatus('pre_transit')
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->get();

        $pdf = new Pdf($request->user()->localisation->document_size, 'D');

        return $pdf->createCollectionManifest($shipments);
    }

    /**
     * Download Batch of PDF labels.
     *
     * @param  $token   Unique shipment file upload identifier
     * @return pdf document
     */
    public function batchedShippingDocsPdf(Request $request, $labelType)
    {
        if (! Auth::guest()) {
            // warehouse login
            if (Auth::user()->id == 3026) {
                flash()->error('Warning', 'Not authorised to print labels.', true);

                return back();
            }
        }

        $this->authorize('viewAny', new Shipment);

        $shipments = $this->search($request, false, false);

        $shipments = $shipments->whereNotIn('status_id', [1, 7])->whereNotIn(
            'carrier_id',
            [9, 10, 11, 12, 13]
        )->where('legacy', '!=', 1)->where('on_hold', 0)->sortBy('route_id');

        $printFormatCode = 'A4';

        if (in_array(strtoupper($labelType), ['MASTER', 'PACKAGE'])) {
            $printFormatCode = $request->user()->printFormat->code;
        }

        $pdf = new Pdf($printFormatCode, 'D');
        $docs = $pdf->createShippingDocs($shipments, strtoupper($labelType));

        return $this->docResponse($docs);
    }

    /**
     * Download Batch of PDF commercial invoices.
     *
     * @param  $token   Unique shipment file upload identifier
     * @return pdf document
     */
    public function batchedCommercialInvoicesBySourcePdf($source)
    {
        $shipments = Shipment::orderBy('shipments.id')->notEu()->notDomestic()->notUkDomestic()->whereSource($source)->get();

        $pdf = new Pdf('A4', 'D');
        $docs = $pdf->createShippingDocs($shipments, 'INVOICE');

        return $this->docResponse($docs);
    }

    /**
     * Display links for labels generated by today's upload.
     *
     * @return type
     */
    public function todaysLabels(Request $request)
    {
        $shipments = Shipment::orderBy('id', 'desc')
            ->groupBy('source')
            ->restrictCompany($request->user()->getAllowedCompanyIds())
            ->restrictMode($request->user()->getAllowedModeIds())
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->paginate(50);

        return view('shipments.todays_labels', compact('shipments'));
    }

    /**
     * Dumps out shipment model.
     *
     * @param type $id
     */
    public function rawData($id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->authorize($shipment);
        dd($shipment);
    }

    /**
     * Dumps out shipment model.
     *
     * @param type $id
     */
    public function sendTestEmail(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->authorize($shipment);
        $shipment->sendTestEmails($request->user()->email);

        flash()->success('Mail Sent!', 'Check your inbox.');

        return redirect("shipments/$id");
    }

    /**
     * Remove POD.
     *
     * @param type $id
     */
    public function removePod(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->authorize($shipment);

        $transportJob = $shipment->transportJobs->where('type', 'd')->where('completed', 1)->first();

        if ($transportJob) {
            $transportJob->undoClose();
            flash()->success('POD removed!', 'POD and tracking event removed from shipment');
        } else {
            flash()->error('Unable to remove POD!', 'Unable to remove POD for this shipment');
        }

        return redirect("shipments/$id");
    }

    /**
     * Undo Cancel.
     *
     * @param type $id
     */
    public function undoCancel(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $this->authorize($shipment);

        $shipment->undoCancel(Auth::user()->id);

        flash()->success('Cancel undone!');

        return redirect("shipments/$id");
    }

    /*
     * Shipment search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    /**
     * Dumps out shipment model.
     *
     * @param type $id
     */
    public function transactionLog($id)
    {
        $shipment = Shipment::findOrFail($id);
        $this->authorize('rawData', $shipment);
        $transactionLog = TransactionLog::where(
            'msg',
            'like',
            '%' . $shipment->carrier_consignment_number . '%'
        )->get();
        dd($transactionLog);
    }

    /**
     * Save a shipment called from Vendorvillage.
     *
     * @param Request $request
     */
    public function saveShipment(Request $request)
    {
        $data = $request->get('shipment');

        $shipment = Shipment::whereCompanyId($data['company_id'])->whereShipmentReference($data['shipment_reference'])->first();

        if (! $shipment) {
            $shipment = Shipment::create($data);

            if ($shipment) {
                $shipment->consignment_number = nextAvailable('CONSIGNMENT');
                $shipment->sender_state = getStateCode($shipment->sender_country_code, $shipment->sender_state);
                $shipment->recipient_state = getStateCode(
                    $shipment->recipient_country_code,
                    $shipment->recipient_state
                );
                $shipment->save();

                return response()->json($shipment, 201);
            }
        }

        return response()->json(['error' => ['message' => 'Failed to create shipment']], 404);
    }

    /**
     * Price/ re-price a shipment.
     *
     * @param Shipment $shipments
     */
    public function price(Shipment $shipments)
    {
        $result = $shipments->price(true);

        if ($result['errors'] == []) {
            flash()->success('Shipment priced!', 'Shipment successfully priced/ repriced');
        } else {
            flash()->success('Unable to price!', 'Unable to price/ reprice this shipment');
        }

        return redirect("shipments/$shipments->id");
    }

    /**
     * Download PDF commercial invoice for the shipment.
     *
     * @param  $token   Unique consignment identifier
     * @return pdf document
     */
    public function despatchNote($token, Request $request)
    {
        // Load the shipment from the token
        $shipment = Shipment::whereToken($token)->firstOrFail();

        // Call the API for invoice
        if (! $despatchNote = CarrierAPI::getDespatchNote($token, Auth::user()->localisation->document_size, 'D')) {
            abort(404);
        }
    }

    /**
     * Download Batch of PDF commercial invoices.
     *
     * @param  $token   Unique shipment file upload identifier
     * @return pdf document
     */
    public function batchedDespatchNotesBySourcePdf($source)
    {
        $shipments = Shipment::orderBy('shipments.id')->whereSource($source)->get();

        $pdf = new Pdf('A4', 'D');
        $docs = $pdf->createShippingDocs($shipments, 'DESPATCH');

        return $this->docResponse($docs);
    }

    /**
     * Testing route.
     *
     * @return type
     */
    public function test(Request $request)
    {
        foreach ([' bt43de', 'bt43de ', ' bt4 3de ', 'bt412nq', 'bt4', 'sw134fr', 'bt41 2nq'] as $postcode) {
            echo formatUkPostcode($postcode) . '<br>';
        }
    }


    /**
     * Display reset shipment form.
     */
    public function reset(Shipment $shipment)
    {
        $this->authorize('reset', $shipment);

        $referer = request()->headers->get('referer');

        return view('shipments.reset', ['shipment' => $shipment, 'referer' => $referer]);
    }


    /**
     * Handle reset shipment form.
     *
     * @param Shipment $shipment
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function resetShipment(Shipment $shipment, Request $request)
    {
        $this->authorize('reset', $shipment);

        $request->validate([
            'service' => 'required|integer',
            'reprice' => 'required|boolean'
        ]);

        // Original pricing
        $originalQuoted = json_decode($shipment->quoted, true);
        $shipmentId= $shipment->id;

        $shipment->reset();

        /*
         * Build a data array and submit a new shipment.
         */

        $packages = [];

        foreach ($shipment->packages as $package) {
            $packages[] = [
                "dry_ice_weight" => $package->dry_ice_weight,
                "packaging_code" => $package->packaging_code,
                "weight" => $package->weight,
                "length" => $package->length,
                "width" => $package->width,
                "height" => $package->height,
            ];
        }

        $contents = [];

        foreach ($shipment->contents as $content) {
            $contents[] = [
                  "description" => $content->description,
                  "product_code" => $content->product_code,
                  "currency_code" => $content->currency_code,
                  "country_of_manufacture" => $content->country_of_manufacture,
                  "manufacturer" => $content->manufacturer,
                  "uom" => $content->uom,
                  "commodity_code" => $content->commodity_code,
                  "harmonized_code" => $content->harmonized_code,
                  "quantity" =>  $content->quantity,
                  "unit_weight" => $content->unit_weight,
                  "unit_value" => $content->unit_value,
            ];
        }

        $data = [
            "shipment_id" => $shipment->id,
            "user_id" => $shipment->user_id,
            "print_formats_id" => $shipment->print_formats_id,
            "mode" => "courier",
            "mode_id" => "1",
            "dims_uom" => $shipment->dims_uom,
            "weight_uom" => $shipment->weight_uom,
            "date_format" => "dd-mm-yyyy",
            "currency_code" => $shipment->customs_value_currency_code,
            "weight" => $shipment->weight,
            "service_id" => $request->service,
            "customs_value" => $shipment->customs_value,
            "customs_value_currency_code" => $shipment->customs_value_currency_code,
            "sender_name" => $shipment->sender_name,
            "sender_company_name" => $shipment->sender_company_name,
            "sender_type" => $shipment->sender_type,
            "sender_address1" => $shipment->sender_address1,
            "sender_address2" => $shipment->sender_address2,
            "sender_address3" => $shipment->sender_address3,
            "sender_city" => $shipment->sender_city,
            "sender_country_code" => $shipment->sender_country_code,
            "sender_state" => $shipment->sender_state,
            "sender_postcode" => $shipment->sender_postcode,
            "sender_telephone" => $shipment->sender_telephone,
            "sender_email" => $shipment->sender_email,
            "company_id" => $shipment->company_id,
            "recipient_name" => $shipment->recipient_name,
            "recipient_company_name" => $shipment->recipient_company_name,
            "recipient_type" => $shipment->recipient_type,
            "recipient_address1" => $shipment->recipient_address1,
            "recipient_address2" => $shipment->recipient_address2,
            "recipient_address3" => $shipment->recipient_address3,
            "recipient_city" => $shipment->recipient_city,
            "recipient_country_code" => $shipment->recipient_country_code,
            "recipient_state" => $shipment->recipient_state,
            "recipient_postcode" => $shipment->recipient_postcode,
            "recipient_telephone" => $shipment->recipient_telephone,
            "recipient_email" => $shipment->recipient_email,
            "recipient_account_number" => $shipment->recipient_account_number,
            "pieces" => $shipment->pieces,
            "shipment_reference" => $shipment->shipment_reference,
            "ship_reason" => $shipment->ship_reason,
            "collection_date" => date('d-m-Y', time()),
            "hazardous" => $shipment->hazardous,
            "special_instructions" => $shipment->special_instructions,
            "bill_shipping" => $shipment->bill_shipping,
            "bill_tax_duty" => $shipment->bill_tax_duty,
            "bill_shipping_account" => $shipment->bill_shipping_account,
            "bill_tax_duty_account" => $shipment->bill_tax_duty_account,
            "invoice_type" => $shipment->invoice_type,
            "terms_of_sale" => $shipment->terms_of_sale,
            "eori" => $shipment->eori,
            "ultimate_destination_country_code" => $shipment->ultimate_destination_country_code,
            "commercial_invoice_comments" => $shipment->commercial_invoice_comments,
            "insurance_value" => $shipment->insurance_value,
            "lithium_batteries" => $shipment->lithium_batteries,
            "packages" => $packages,
            "contents" => $contents,
            "goods_description" => $shipment->goods_description
        ];

        $response = CarrierAPI::createShipment($data);

        if (isset($response['errors']) && $response['errors'] != []) {
            flash()->error('Errors found!', $response['errors'], true);
            return redirect('shipments/' . $shipment->id . '/edit')->withInput();
        }

        // Refresh Shipment
        $shipment = Shipment::find($shipmentId);

        // Reinstate original if we do not want to reprice the shipment
        if (! $request->reprice) {

            // Get the Repriced details
            $newQuoted=json_decode($shipment->quoted, true);

            // Update newQuoted array with previous values
            $newQuoted['shipping_charge'] = $originalQuoted['shipping_charge'];
            $newQuoted['fuel_charge'] = $originalQuoted['fuel_charge'];
            $newQuoted['sales_vat_amount'] = $originalQuoted['sales_vat_amount'];
            $newQuoted['sales_vat_code'] = $originalQuoted['sales_vat_code'];
            $newQuoted['sales_currency'] = $originalQuoted['sales_currency'];
            $newQuoted['sales'] = $originalQuoted['sales'];
            $newQuoted['sales_debug'] = $originalQuoted['sales_debug'];
            $newQuoted['sales_detail'] = $originalQuoted['sales_detail'];
            $newQuoted['sales_zone'] = $originalQuoted['sales_zone'];
            $newQuoted['sales_model'] = $originalQuoted['sales_model'];
            $newQuoted['sales_rate_id'] = $originalQuoted['sales_rate_id'];
            $newQuoted['sales_packaging'] = $originalQuoted['sales_packaging'];

            // Update Shipment record
            $shipment->shipping_charge = $newQuoted['shipping_charge'];
            $shipment->fuel_charge = $newQuoted['fuel_charge'];
            $shipment->sales_currency = $newQuoted['sales_currency'];

            // Set the json quote field
            $shipment->quoted = json_encode($newQuoted);

            $shipment->save();

            $shipment->log('Shipment reset (not repriced)');
        }

        // Notify user and redirect
        flash()->info('Shipment Reset!');

        if($request->redirect){
            return redirect($request->redirect);
        }

        return redirect("shipments");
    }
}
