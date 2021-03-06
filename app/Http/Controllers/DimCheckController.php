<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
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
        if (! $request->user()->hasIfsRole()) {
            return;
        }

        // Validate the request
        $this->validate($request, ['file' => 'required'], ['file.mimes' => 'Not a valid CSV file - please check for unsupported characters', 'file.required' => 'Please select a file to upload.']);

        // Upload the file to the temp directory
        $path = $request->file('file')->storeAs('temp', 'dimcheck'.time().'.csv');

        // Check that the file was uploaded successfully
        if (! Storage::disk('local')->exists($path)) {
            flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

            return back();
        }

        $results = [];
        $rowNumber = 1;
        $filepath = storage_path('app/'.$path);

        if (($handle = fopen($filepath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000)) !== false) {
                if ($rowNumber >= 2) {
                    $dhlActualWeight = (! empty($data[30]) && is_numeric($data[30])) ? $data[30] : 0;
                    $dhlVolWeight = (! empty($data[31]) && is_numeric($data[31])) ? $data[31] : 0;

                    $shipment = Shipment::where('carrier_consignment_number', $data[2])->where('carrier_id', 5)->first();

                    if ($shipment) {
                        $results[$rowNumber] = [
                            'shipment_id' => $shipment->id,
                            'ship_date' => $shipment->ship_date->format('d-M-y'),
                            'sender_company_name' => $shipment->sender_company_name,
                            'consignment_number' => $shipment->consignment_number,
                            'scs_job_number' => $shipment->scs_job_number,
                            'carrier_consignment_number' => $data[2],
                            'weight' => $shipment->weight,
                            'carrier_weight' => $dhlActualWeight,
                            'volumetric_weight' => $shipment->volumetric_weight,
                            'carrier_volumetric_weight' => $dhlVolWeight,
                            'carrier_service' => $data['3'],
                            'dims_updated' => ($shipment->supplied_weight) ? true : false,
                            'difference' => round(abs(max($dhlActualWeight, $dhlVolWeight) - max($shipment->weight, $shipment->volumetric_weight)), 2),
                            'costs_zone' => (isset($shipment->quoted_array['costs_zone'])) ? $shipment->quoted_array['costs_zone'] : '',
                            'sales_zone' => (isset($shipment->quoted_array['sales_zone'])) ? $shipment->quoted_array['sales_zone'] : '',
                            'flag' => (max($dhlActualWeight, $dhlVolWeight) >= max($shipment->weight, $shipment->volumetric_weight)) ? true : false,
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
