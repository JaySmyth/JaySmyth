<?php

namespace App\Http\Controllers;

use Auth;
use App\Document;
use App\Shipment;
use App\CustomsEntry;
use App\SeaFreightShipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Storage;

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
        $filePath = 'documents/' . $request->parent . '/' . $parentModel->id . '/' . time() . str_random(8) . '.pdf';

        // Uploaded file
        $file = $request->file('file');

        // Read the contents of the file
        $fileContents = file_get_contents($file);

        // Upload the file to S3
        $s3 = Storage::disk('s3');
        $s3->put($filePath, $fileContents, 'public');

        if (!$s3->exists($filePath)) {
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
            'user_id' => Auth::user()->id
        ]);
 
        // Save a copy to the temp directory for batch printing later in the day
        if ($request->parent == 'shipment' && $request->document_type == 'invoice') {
            Storage::disk('local')->put('temp/invoice' . $result->id . '.pdf', $fileContents);
        }

        flash()->success('Document Added!', 'Document uploaded successfully.');

        switch ($request->parent) {
            case 'customs-entry':
                return redirect('customs-entries');
                break;
            case 'sea-freight-shipment':
                return redirect('sea-freight');
                break;
            default:
                return redirect($request->parent . 's');
                break;
        }
    }

    /**
     * Deletes a file that has been uploaded to S3 and removes the 
     * associated record from the database.
     *
     * @param  integer  $id     document id.
     * @return boolean
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

}
