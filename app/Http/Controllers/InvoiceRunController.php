<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\InvoiceRun;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceRunController extends Controller
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
     * List the invoice runs and paginate the results
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new InvoiceRun);

        $invoiceRuns = InvoiceRun::orderBy('id', 'desc')->with(['user', 'department', 'shipments'])->paginate(10);

        return view('invoice_runs.index', compact('invoiceRuns'));
    }

    /**
     * Display the invoice run
     *
     * @param $id
     * @return type
     */
    public function show($id)
    {
        $invoiceRun = InvoiceRun::findOrFail($id);

        $this->authorize($invoiceRun);

        return view('invoice_runs.show', compact('invoiceRun'));
    }

    /**
     * Display all shipments available for invoicing
     *
     * @return type
     */
    public function create(Request $request)
    {
        $this->authorize(new InvoiceRun);

        // Get uninvoiced shipments
        $shipments = Shipment::select('shipments.*')
                ->hasCompany($request->company)
                ->hasDepartment($request->department)
                ->where('ship_date', '<', Carbon::today())
                ->uninvoiced()
                ->orderBy('sender_company_name', 'consignment_number')
                ->with(['company', 'service'])
                ->get();

        // Attempt to price unpriced shipments
        $unpricedShipments = $shipments->where('shipping_charge', 0);

        if ($unpricedShipments) {
            foreach ($unpricedShipments as $shipment) {
                $shipment->price();
            }
        }

        return view('invoice_runs.create', compact('shipments'));
    }

    /**
     * Handles the create invoice run form. Job dispatched to create an invoice run record and generate
     * the multifreight sales XML.

     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->authorize(new InvoiceRun);

        dispatch(new \App\Jobs\GenerateMultifreightSalesXml($request->shipments, $request->department, Auth::user()));

        // Notify user and redirect
        flash()->info('Processing', 'Generating MultiFreight sales XML.', true);

        return redirect('invoice-runs');
    }

}
