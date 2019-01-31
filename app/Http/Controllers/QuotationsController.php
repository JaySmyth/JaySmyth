<?php

namespace App\Http\Controllers;

use Auth;
use App\Quotation;
use App\CarrierAPI\Pdf;
use Illuminate\Http\Request;
use App\Http\Requests\QuotationRequest;

class QuotationsController extends Controller
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
        $this->authorize(new \App\Quotation);

        $quotations = $this->search($request);

        return view('quotations.index', compact('quotations'));
    }

    /**
     * Display a customs entry.
     * 
     * @param type $id
     * @return type
     */
    public function show(Quotation $quotation)
    {
        $this->authorize($quotation);

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Displays new entry form.
     *
     * @param  
     * @return 
     */
    public function create()
    {
        $this->authorize(new Quotation);

        return view('quotations.create');
    }

    /**
     * Save the customs entry.
     *
     * @param  
     * @return 
     */
    public function store(QuotationRequest $request)
    {
        $this->authorize(new Quotation);

        $quotation = Quotation::create(array_add($request->all(), 'user_id', Auth::user()->id));

        $quotation->log();

        flash()->success('Quotation Created!', 'Quotation created successfully.');

        return redirect('quotations');
    }

    /**
     * Displays edit form.
     *
     * @param  
     * @return 
     */
    public function edit(Quotation $quotation)
    {
        $this->authorize($quotation);

        return view('quotations.edit', compact('quotation'));
    }

    /**
     * Updates an existing entry.
     *
     * @param  
     * @return 
     */
    public function update(Quotation $quotation, QuotationRequest $request)
    {
        $this->authorize($quotation);

        $quotation->updateWithLog($request->all());

        flash()->success('Updated!', 'Quotation updated successfully.');

        return redirect('quotations');
    }

    /**
     * Set the success flag.
     *
     * @param
     * @return
     */
    public function status(Quotation $quotation, Request $request)
    {
        if ($request->ajax()) {

            $this->authorize($quotation);

            $quotation->log(($request->successful) ? 'Set to unsuccessful' : 'Set to successful');

            echo booleanToYn($quotation->toggleSuccessful());
        }
    }

    /**
     * Return a quotation PDF.
     *
     * @param type $id
     * @return type
     */
    public function pdf(Quotation $quotation, Request $request)
    {
        $this->authorize($quotation);

        $quotation->log('Downloaded PDF');

        $pdf = new Pdf($request->user()->localisation->document_size, 'I');

        return $pdf->createQuotation($quotation);
    }

    /**
     * Delete.
     * 
     * @param Quotation $quotation
     * @return string
     */
    public function destroy(Quotation $quotation)
    {
        $this->authorize($quotation);

        $quotation->delete();

        return response()->json(null, 204);
    }

    /*
     * Quotation search.
     * 
     * @param   $request
     * @param   $paginate
     * 
     * @return
     */

    private function search($request, $paginate = true)
    {
        $query = Quotation::orderBy('id', 'DESC')
                ->filter($request->filter)
                ->dateBetween($request->date_from, $request->date_to)
                ->hasDepartment($request->department)
                ->hasSuccessful($request->successful);

        if (!$paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

}
