<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomsEntryRequest;
use App\Http\Requests\CustomsEntryCommodityRequest;
use Illuminate\Support\Facades\Storage;
use App\CustomsEntry;
use App\Document;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class CustomsEntriesController extends Controller
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
        $this->authorize(new CustomsEntry);

        $customsEntries = $this->search($request);

        return view('customs_entries.index', compact('customsEntries'));
    }

    /**
     * Display a customs entry.
     * 
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $customsEntry = CustomsEntry::findOrFail($id);

        $this->authorize($customsEntry);

        return view('customs_entries.show', compact('customsEntry'));
    }

    /**
     * Displays new entry form.
     *
     * @param  
     * @return 
     */
    public function create()
    {
        $this->authorize(new CustomsEntry);

        return view('customs_entries.create');
    }

    /**
     * Save the customs entry.
     *
     * @param  
     * @return 
     */
    public function store(CustomsEntryRequest $request)
    {
        $this->authorize(new CustomsEntry);

        $customsEntry = CustomsEntry::create(array_add($request->all(), 'user_id', Auth::user()->id));

        flash()->success('Entry Created!', 'Customs entry created successfully.');

        return redirect('customs-entries');
    }

    /**
     * Displays edit entry form.
     *
     * @param  
     * @return 
     */
    public function edit($id)
    {
        $customsEntry = CustomsEntry::findOrFail($id);

        $this->authorize($customsEntry);

        return view('customs_entries.edit', compact('customsEntry'));
    }

    /**
     * Updates an existing entry.
     *
     * @param  
     * @return 
     */
    public function update(CustomsEntryRequest $request, $id)
    {
        $customsEntry = CustomsEntry::findOrFail($id);

        $this->authorize($customsEntry);

        // Update the customs entry
        $customsEntry->update($request->all());

        flash()->success('Updated!', 'Entry updated successfully.');

        return redirect('customs-entries');
    }

    /**
     * Displays add commodity form.     
     * 
     * @param int $id
     * @return 
     */
    public function addCommodity($id)
    {
        $customsEntry = CustomsEntry::findOrFail($id);

        $this->authorize($customsEntry);

        return view('customs_entry_commodities.create', compact('customsEntry'));
    }

    /**
     * Saves the commodity line.
     * 
     * @param Request $request
     * @param int $id   
     * @return void
     */
    public function storeCommodity(CustomsEntryCommodityRequest $request, $id)
    {
        $customsEntry = CustomsEntry::findOrFail($id);

        $this->authorize('addCommodity', $customsEntry);

        $customsEntry->customsEntryCommodity()->create($request->all());

        flash()->success('Commodity added!', 'Commodity added to entry.');

        if ($customsEntry->customsEntryCommodity->count() >= $customsEntry->commodity_count) {
            return redirect('customs-entries');
        }

        return back();
    }

    /**
     * Delete a customs entry, associated commodities and docs.
     *
     * @param  int $id
     * @return 
     */
    public function destroy(CustomsEntry $customsEntry)
    {

        $this->authorize($customsEntry);

        /*
         * Delete associated commodities
         */
        foreach ($customsEntry->customsEntryCommodity as $commodity) {
            $commodity->delete();
        }

        $documentIds = $customsEntry->documents()->pluck('id');

        // detach documents
        $customsEntry->documents()->detach();

        /*
         * Delete any documents from S3.
         */
        $s3 = Storage::disk('s3');

        $documents = Document::whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            if ($s3->delete($document->path)) {
                $document->delete();
            }
        }

        // delete the customs entry itself
        $customsEntry->delete();

        return response()->json(null, 204);
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
        $query = CustomsEntry::orderBy('date', 'DESC')
                ->filter($request->filter)
                ->dateBetween($request->date_from, $request->date_to)
                ->hasCompany($request->company)
                ->hasCpc($request->cpc)
                ->restrictCompany($request->user()->getAllowedCompanyIds());

        if (!$paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param  Request
     * @return Excel document
     */
    public function download(Request $request)
    {
        $this->authorize('index', new CustomsEntry);

        $customsEntries = $this->search($request, false);

        return Excel::download(new \App\Exports\CustomsEntriesExport($customsEntries), 'customs_entries.xlsx');
    }

    /**
     * Download the result set to an Excel Document.
     *
     * @param  Request
     * @return Excel document
     */
    public function downloadByCommodity(Request $request)
    {
        $this->authorize('index', new CustomsEntry);

        $customsEntries = $this->search($request, false);

        return Excel::download(new \App\Exports\CustomsEntriesByCommodityExport($customsEntries), 'customs_entries.xlsx');
    }

}
