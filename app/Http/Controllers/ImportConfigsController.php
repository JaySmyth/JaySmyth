<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportConfigRequest;
use App\Models\Models\ImportConfig;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportConfigsController extends Controller
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
     * List configs.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $importConfigs = ImportConfig::orderBy('company_name')->paginate(50);

        return view('import_configs.index', compact('importConfigs'));
    }

    /**
     * Display a config.
     *
     * @param type $id
     * @return type
     */
    public function show(ImportConfig $importConfig)
    {
        return view('import_configs.show', compact('importConfig'));
    }

    /**
     * Displays create form.
     *
     * @param
     * @return
     */
    public function create()
    {
        $fields = dropDown('importConfigFields', 'Please select');

        $importConfig = \App\Models\Models\ImportConfig::whereCompanyName('*TEMPLATE*')->first();

        return view('import_configs.create', compact('importConfig', 'fields'));
    }

    /**
     * Handle create ImportConfig form. Save the config fields.
     *
     * @param AddressRequest $request
     * @return type
     */
    public function store(ImportConfigRequest $request)
    {
        ImportConfig::Create($request->all());

        flash()->success('Created!', 'Configuration details stored successfully.');

        return redirect('import-configs');
    }

    /**
     * Show edit form.
     *
     * @param type $id
     * @return type
     */
    public function edit(ImportConfig $importConfig)
    {
        $fields = dropDown('importConfigFields', 'Please select');

        return view('import_configs.edit', compact('importConfig', 'fields'));
    }

    /**
     * Update a company record.
     *
     * @param
     * @return
     */
    public function update(ImportConfig $importConfig, ImportConfigRequest $request)
    {
        $importConfig->update($request->all());

        flash()->success('Updated!', 'Configuration updated successfully.');

        return redirect('import-configs');
    }

    /**
     * Delete.
     *
     * @param Quotation $quotation
     * @return string
     */
    public function destroy(ImportConfig $importConfig)
    {
        $importConfig->delete();

        return response()->json(null, 204);
    }

    /**
     * Download a sample CSV file.
     *
     * @param ImportConfig $importConfig
     */
    public function downloadExample(ImportConfig $importConfig, Faker $faker)
    {
        return Excel::download(new \App\Exports\ShipmentImportExampleExport($importConfig, $faker), strtolower(Str::snake($importConfig->company_name)).'.csv');
    }
}
