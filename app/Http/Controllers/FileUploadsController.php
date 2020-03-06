<?php

namespace App\Http\Controllers;

use App\Models\Models\FileUpload;
use Illuminate\Http\Request;

class FileUploadsController extends Controller
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
     * List mail reports.
     *
     * @param Request $request
     * @return type
     */
    public function index()
    {
        $this->authorize('view', new FileUpload);

        $fileUploads = FileUpload::select('file_uploads.*', 'companies.company_name')->orderBy('company_name')->orderBy('type', 'ASC')
                ->join('companies', 'companies.id', '=', 'file_uploads.company_id')
                ->paginate(50);

        return view('file_uploads.index', compact('fileUploads'));
    }

    /**
     * Displays a user record.
     *
     * @param
     * @return
     */
    public function show($id)
    {
        $fileUpload = FileUpload::findOrFail($id);

        $this->authorize($fileUpload);

        return view('file_uploads.show', compact('fileUpload'));
    }

    /**
     * Retry file upload.
     *
     * @param type $id
     * @return type
     */
    public function retry($id)
    {
        $fileUpload = FileUpload::findOrFail($id);

        $this->authorize($fileUpload);

        $fileUpload->retry();

        flash()->info('File Upload', 'File upload has been scheduled to run in 2 minutes time.', true);

        return back();
    }
}
