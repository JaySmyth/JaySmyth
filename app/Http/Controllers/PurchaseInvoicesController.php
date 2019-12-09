<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\PurchaseInvoice;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\PurchaseInvoicesExport;

class PurchaseInvoicesController extends Controller
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
     * List the invoices.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize('view', new PurchaseInvoice);

        $purchaseInvoices = $this->search($request);

        return view('purchase_invoices.index', compact('purchaseInvoices'));
    }

    /**
     * Search.
     *
     * @param $request
     * @param bool $paginate
     * @param bool $limit
     * @return mixed
     */
    private function search($request, $paginate = true, $limit = false)
    {
        $query = PurchaseInvoice::orderBy('date', 'DESC')
            ->orderBy('invoice_number', 'DESC')
            ->filter($request->filter)
            ->dateBetween($request->date_from, $request->date_to)
            ->hasConsignmentOrScsJob($request->consignment)
            ->hasCarrier($request->carrier)
            ->hasStatus($request->status)
            ->hasType($request->type)
            ->hasImportExport($request->import_export)
            ->hasReceived($request->received)
            ->hasQueried($request->queried)
            ->hasCosts($request->costs)
            ->hasCopyDocs($request->copy_docs)
            ->with('carrier');

        if ($limit) {
            $query->limit($limit);
        }

        if (!$paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

    /**
     * Show Purchase Invoice.
     *
     * @param
     * @return
     */
    public function show($id)
    {
        $this->authorize(new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        return view('purchase_invoices.show', compact('purchaseInvoice'));
    }

    /**
     * Show detailed view.
     *
     * @param
     * @return
     */
    public function detail($id)
    {
        $this->authorize('view', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        return view('purchase_invoices.detail', compact('purchaseInvoice'));
    }

    /**
     * Compare costs.
     *
     * @param type $id
     * @return type
     */
    public function compare($id)
    {
        $this->authorize('view', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        $costComparisions = $purchaseInvoice->getCostComparisons();

        return view('purchase_invoices.compare', compact('purchaseInvoice', 'costComparisions'));
    }

    /**
     * Pass an invoice.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function pass(Request $request, $id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setPassed($request->user()->id)) {
            flash()->success('Invoice Passed!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has been passed.", true);
            return back();
        }

        flash()->info('Already Passed!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has already been passed.", true);
        return back();
    }

    /**
     * Export an invoice.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function export(Request $request, $id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setExported()) {
            flash()->success('Invoice Exported!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number will be included in the next XML generation.", true);
            return back();
        }

        flash()->info('Already xported!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has already been exported.", true);
        return back();
    }

    /**
     * Toggle the received flag.
     *
     * @param type $id
     * @return type
     */
    public function receive($id)
    {
        $this->authorize('flags', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setReceived()) {
            flash()->success('Invoice Received!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has been flagged as physically received.", true);
            return back();
        }

        flash()->info('Receipt Flag Removed!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has been flagged as not rceived.", true);
        return back();
    }

    /**
     * Toggle the query flag.
     *
     * @param type $id
     * @return type
     */
    public function query($id)
    {
        $this->authorize('flags', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setQueried()) {
            flash()->success('Invoice Queried!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has been flagged as queried.", true);
            return back();
        }

        flash()->info('Query Flag Removed!', "The query flag has been removed from " . $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number.", true);
        return back();
    }

    /**
     * Toggle the costs flag.
     *
     * @param type $id
     * @return type
     */
    public function costs($id)
    {
        $this->authorize('flags', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setCosts()) {
            flash()->success('Costs Entered!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number has been flagged as having SCS costs entered.", true);
            return back();
        }

        flash()->info('Costs Flag Removed!', "The costs flag has been removed from " . $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number.", true);
        return back();
    }

    /**
     * Toggle the copy docs flag.
     *
     * @param type $id
     * @return type
     */
    public function copyDocs($id)
    {
        $this->authorize('flags', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        if ($purchaseInvoice->setCopyDocs()) {
            flash()->success('Copy Docs Flag Set!', $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number will be included within the next copy docs email request.", true);
            return back();
        }

        flash()->info('Copy Docs Flag Removed!', "The copy docs flag has been removed from " . $purchaseInvoice->carrier->name . " invoice $purchaseInvoice->invoice_number.", true);
        return back();
    }

    /**
     * Send negative variances email.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function negativeVariancesEmail(Request $request, $id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        Mail::to($request->user()->email)->queue(new \App\Mail\NegativeVariances($purchaseInvoice));

        flash()->success('Email sent!', 'Negative variances emailed.');

        return back();
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function negativeVariancesDownload($id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        $purchaseInvoiceLines = $purchaseInvoice->getNegativeVariances();

        return Excel::download(new \App\Exports\PurchaseInvoiceNegativeVariancesExport($purchaseInvoiceLines), 'NV_' . $purchaseInvoice->invoice_number . '.xlsx');
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function costComparisonDownload($id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        return Excel::download(new \App\Exports\PurchaseInvoiceCostComparisonExport($purchaseInvoice), $purchaseInvoice->invoice_number . '.xlsx');
    }

    /**
     * Send copy docs email.
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function copyDocsEmail(Request $request)
    {
        $this->authorize('admin', new PurchaseInvoice);

        // Retrieve invoices flagged for export
        $purchaseInvoices = PurchaseInvoice::whereCopyDocs(1)->whereCopyDocsEmailSent(0)->get();

        if ($purchaseInvoices->count() > 0) {

            PurchaseInvoice::whereCopyDocs(1)->whereCopyDocsEmailSent(0)->update(['copy_docs_email_sent' => 1]);

            dispatch(new \App\Jobs\RequestPurchaseInvoiceCopyDocs($purchaseInvoices, $request->user()));

            flash()->success('Email sent!', 'Copy docs email sent.');

            return back();
        }

        flash()->error('No invoices flagged for copy docs request!');
        return back();
    }

    /**
     * Dispatch flagged invoices for export.
     *
     * @return type
     */
    public function exportInvoices(Request $request)
    {
        $this->authorize('admin', new PurchaseInvoice);

        // Retrieve invoices flagged for export
        $purchaseInvoices = PurchaseInvoice::whereExported(1)->whereXmlGenerated(0)->pluck('id');

        if ($purchaseInvoices->count() > 0) {

            dispatch(new \App\Jobs\ExportPurchaseInvoices($purchaseInvoices, $request->user()));

            flash()->success('Exporting Invoices', 'Invoices are being processed, you will receive an email once complete.');

            return back();
        }

        flash()->error('No invoices flagged for export!');

        return back();
    }

    /**
     * Preview multifreight XML on screen - useful for matching queries / debugging.
     *
     * @param type $id
     * @return type
     */
    public function previewXml($id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        $xml = $purchaseInvoice->getMultifreightXml();

        return view('purchase_invoices.xml', compact('purchaseInvoice', 'xml'));
    }

    /**
     * Download invoice as multifreight XML.
     *
     * @param type $id
     * @return file download
     */
    public function downloadXml($id)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);

        $xml = $purchaseInvoice->getMultifreightXml();

        $pathToFile = 'temp/' . $purchaseInvoice->invoice_number . '.xml';

        // Write to temp storage
        Storage::disk('local')->put($pathToFile, $xml);

        $headers = [
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=$purchaseInvoice->invoice_number.xml",
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'text/xml'
        ];

        return response()->file(storage_path() . '/app/' . $pathToFile, $headers);
    }

    /*
     * Purchase invoice search.
     *
     * @param   $request
     * @param   $paginate
     *
     * @return
     */

    /**
     * Download the result set to an Excel Document.
     *
     * @param Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize('admin', new PurchaseInvoice);

        $invoices = $this->search($request, false, 2000);

        return Excel::download(new PurchaseInvoicesExport($invoices), 'invoices.xlsx');
    }

}
