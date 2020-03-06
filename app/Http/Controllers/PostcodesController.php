<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostcodeRequest;
use App\Models\Models\IfsNdPostcode;
use App\Models\Postcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostcodesController extends Controller
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
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $this->authorize(new Postcode);

        $postcodes = Postcode::all();
        $postcodes = $postcodes->sortBy('postcode', SORT_NATURAL);

        return view('postcodes.index', compact('postcodes'));
    }

    /**
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('viewAny', new Postcode);

        return view('postcodes.create');
    }

    /**
     * @param
     * @return
     */
    public function store(PostcodeRequest $request)
    {
        $this->authorize('viewAny', new Postcode);

        $postcode = Postcode::create($request->all());

        flash()->success('Created!', 'Postcode created successfully.');

        return redirect('postcodes');
    }

    /**
     * @param
     * @return
     */
    public function edit($id)
    {
        $postcode = Postcode::findOrFail($id);

        $this->authorize('viewAny', $postcode);

        return view('postcodes.edit', compact('postcode'));
    }

    /**
     * @param
     * @return
     */
    public function update(PostcodeRequest $request, $id)
    {
        $postcode = Postcode::findOrFail($id);

        $this->authorize('viewAny', $postcode);

        $postcode->update($request->all());

        flash()->success('Updated!', 'Postcode updated successfully.');

        return redirect('postcodes');
    }

    /**
     * List ND postcodes.
     *
     * @param
     * @return
     */
    public function ifsNonDeliveryPostcodes()
    {
        $this->authorize('viewAny', new Postcode);

        $postcodes = IfsNdPostcode::orderBy('postcode')->paginate(1000);

        return view('postcodes.ifs_nd_postcodes', compact('postcodes'));
    }

    /**
     * Store ND postcode.
     *
     * @param
     * @return
     */
    public function storeIfsNonDeliveryPostcode(Request $request)
    {
        $this->authorize('viewAny', new Postcode);

        $validator = Validator::make($request->all(), [
            'postcode' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        IfsNdPostcode::create($request->all());

        flash()->success('Created!', 'Postcode added successfully.');

        return redirect('ifs-nd-postcodes');
    }

    /**
     * Delete.
     *
     * @param Delete $postcode
     * @return string
     */
    public function deleteIfsNonDeliveryPostcode(IfsNdPostcode $postcode)
    {
        $postcode->delete();

        return response()->json(null, 204);
    }
}
