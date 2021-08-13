<?php

namespace App\Http\Controllers;

use App\Models\PricingZones;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PricingZoneController extends Controller
{
    public function index()
    {
        $models = [
            '0' => 'Generic',
            '2' => 'Fedex Intl',
            '3' => 'UPS',
            '4' => 'TNT',
            '5' => 'DHL',
            '6' => 'Countrywide',
            '7' => 'XDP',
        ];

        $services = PricingZones::where('to_date', '>=', date('Y-m-d'))
            ->groupBy('model_id')
            ->groupBy('service_code')
            ->orderBy('model_id')
            ->orderBy('service_code')
            ->get();

        return view('pricing_zones.index', compact('models', 'services'));
    }

    /**
     * Download rate - excel.
     *
     * @param Company $company
     * @param Service $service
     * @param type $effectiveDate
     *
     * @return Excel document
     */
    public function download($modelId, $serviceCode = '', $reqDate = '')
    {
        $pricingZones = new PricingZones();

        return $pricingZones->download($modelId, $serviceCode, $reqDate);
    }

    /**
     * Master Rate Upload CSV screen.
     *
     * @return type
     */
    public function upload($modelId, $serviceCode = '')
    {
        // $this->authorize(new Rate);

        return view('pricing_zones.upload', compact('modelId', 'serviceCode'));
    }

    /**
     * Process CSV upload.
     *
     * @param Request $request
     * @return type
     */
    public function storeUpload($modelId, $serviceCode, Request $request)
    {
        // $this->authorize('upload', new Rate);

        $user = Auth::user();
        if ($user) {

            // Validate the request
            $this->validate($request, ['file' => 'required|mimes:csv,txt'], ['file.required' => 'Please select a file to upload.']);

            // Upload the file to the temp directory
            $path = $request->file('file')->storeAs('temp', 'original_'.Str::random(12).'.csv');

            // Check that the file was uploaded successfully
            if (! Storage::disk('local')->exists($path)) {
                flash()->error('Problem Uploading!', 'Unable to upload file. Please try again.');

                return back();
            }

            dispatch(new \App\Jobs\ImportPricingZones($path, $modelId, $serviceCode, $user));

            // Notify user and redirect
            flash()->info('File Uploaded!', 'Please check your email for results.', true);

            return redirect('pricing-zones');
        } else {
            flash()->error('Authentication Error!', 'Please Login and try again.');

            return redirect('logout');
        }
    }
}
