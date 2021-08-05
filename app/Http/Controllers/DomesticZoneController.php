<?php

namespace App\Http\Controllers;

use App\Models\DomesticZone;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DomesticZoneController extends Controller
{
    public function index()
    {
        $models = DomesticZone::groupBy('model')->orderBy('model')->get();

        return view('domestic_zones.index', compact('models'));
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
    public function download($model)
    {
        if ($model) {
            $domesticZones = new DomesticZone();

            return $domesticZones->download($model);
        }

        if ($download) {
            return view('errors.404');
        } else {
            return [];
        }
    }

    /**
     * Master Rate Upload CSV screen.
     *
     * @return type
     */
    public function upload($model)
    {
        // $this->authorize(new Rate);

        return view('domestic_zones.upload', compact('model'));
    }

    /**
     * Process CSV upload.
     *
     * @param Request $request
     * @return type
     */
    public function storeUpload($model, Request $request)
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

            dispatch(new \App\Jobs\ImportDomesticZones($path, $model, $user));

            // Notify user and redirect
            flash()->info('File Uploaded!', 'Please check your email for results.', true);

            return redirect('domestic-zones');
        } else {
            flash()->error('Authentication Error!', 'Please Login and try again.');

            return redirect('logout');
        }
    }
}
