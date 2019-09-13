<?php

namespace App\Http\Controllers;

use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DimCheckController extends Controller
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
     * Process uploaded shipment file.
     *
     * @param Request $request
     * @return type
     */
    public function processUpload(Request $request)
    {
        if (!$request->user()->hasIfsRole()) {
            return null;
        }

        // Validate the request
        $this->validate($request, ['file' => 'required|mimes:csv,txt'], ['file.mimes' => 'Not a valid CSV file - please check for unsupported characters', 'file.required' => 'Please select a file to upload.']);

        // Upload the file to the temp directory
        $path = $request->file('file')->storeAs('temp', 'dimcheck' . time() . '.csv');

        // Check that the file was uploaded successfully
        if (!Storage::disk('local')->exists($path)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');
            return back();
        }

        $results = [];
        $rowNumber = 1;
        $filepath = storage_path('app/' . $path);

        if (($handle = fopen($filepath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {

                if ($rowNumber >= 2) {

                    $dhlActualWeight = $data[30];
                    $dhlVolWeight = $data[31];

                    $shipment = Shipment::where('carrier_consignment_number', $data[2])->where('carrier_id', 5)->first();

                    if ($shipment) {

                        $results[$rowNumber] = [
                            'shipment_id' => $shipment->id,
                            'ship_date' => $shipment->ship_date->format('d-M-y'),
                            'sender_company_name' => $shipment->sender_company_name,
                            'consignment_number' => $shipment->consignment_number,
                            'carrier_consignment_number' => $data[2],
                            'weight' => $shipment->weight,
                            'carrier_weight' => $dhlActualWeight,
                            'volumetric_weight' => $shipment->volumetric_weight,
                            'carrier_volumetric_weight' => $dhlVolWeight,
                            'dims_updated' => ($shipment->supplied_weight) ? true : false,
                            'flag' => (max($dhlActualWeight, $dhlVolWeight) >= max($shipment->weight, $shipment->volumetric_weight)) ? true : false
                        ];
                    } else {
                        $results[$rowNumber] = ['consignment_number' => false, 'carrier_consignment_number' => $data[2]];
                    }
                }

                $rowNumber++;
            }
            fclose($handle);
        }

        unset($filepath);

        return view('dim_check.results', ['results' => $results]);
    }


}
