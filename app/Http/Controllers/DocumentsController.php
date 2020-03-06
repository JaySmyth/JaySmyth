<?php

namespace App\Http\Controllers;

use App\Models\Models\CustomsEntry;
use App\Models\Models\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\SeaFreightShipment;
use App\Models\Shipment;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentsController extends Controller
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
     * Displays the upload document form.
     *
     * @param
     * @return
     */
    public function create($parent, $id)
    {
        $parentModel = $this->instantiateModel($parent, $id);

        $this->authorize('view', $parentModel);

        return view('documents.create', compact('parentModel', 'parent'));
    }

    /**
     * Load the parent model that the document will be associated with.
     *
     * @param type $model
     * @param type $id
     * @return type
     */
    private function instantiateModel($model, $id)
    {
        switch ($model) {
            case 'shipment':
                return Shipment::findOrFail($id);
            case 'customs-entry':
                return CustomsEntry::findOrFail($id);
            case 'sea-freight-shipment':
                return SeaFreightShipment::findOrFail($id);
            default:
                return false;
        }
    }

    /**
     * Uploads a file to S3 and saves the record to the database.
     *
     * @param
     * @return
     */
    public function store(DocumentRequest $request)
    {
        $parentModel = $this->instantiateModel($request->parent, $request->id);

        $this->authorize('view', $parentModel);

        // Generate a file path for the document
        $filePath = 'documents/'.$request->parent.'/'.$parentModel->id.'/'.time().Str::random(8).'.pdf';

        // Uploaded file
        $file = $request->file('file');

        // Read the contents of the file
        $fileContents = file_get_contents($file);

        // Upload the file to S3
        $s3 = Storage::disk('s3');
        $s3->put($filePath, $fileContents, 'public');

        if (! $s3->exists($filePath)) {
            flash()->error('Problem Uploading!', 'Unable to upload document. Please try again.');

            return back();
        }

        $result = $parentModel->documents()->create([
            'filename' => $file->getClientOriginalName(),
            'document_type' => $request->document_type,
            'description' => $request->description,
            'path' => $filePath,
            'public_url' => $s3->url($filePath),
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'user_id' => Auth::user()->id,
        ]);

        // Save a copy to the temp directory for batch printing later in the day
        if ($request->parent == 'shipment' && $request->document_type == 'invoice') {
            Storage::disk('local')->put('temp/invoice'.$result->id.'.pdf', $fileContents);
        }

        flash()->success('Document Added!', 'Document uploaded successfully.');

        return back();
    }

    /**
     * Deletes a file that has been uploaded to S3 and removes the
     * associated record from the database.
     *
     * @param int $id document id.
     * @return bool
     */
    public function destroy(Request $request, $id)
    {
        $parentModel = $this->instantiateModel($request->parent, $request->parentId);

        $this->authorize('view', $parentModel);

        // delete the document from the pivot
        $parentModel->documents()->detach($id);

        // load the document record
        $document = Document::findOrFail($id);

        // Set the storage for s3 and delete the file
        $s3 = Storage::disk('s3');

        if ($s3->delete($document->path)) {
            $document->delete();

            return response()->json(null, 204);
        }
    }
}
