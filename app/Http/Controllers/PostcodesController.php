<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\IfsNdPostcode;
use Illuminate\Http\Request;
use App\Http\Requests\PostcodeRequest;
use App\Postcode;

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
     *
     *
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
     *
     *
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('index', new Postcode);

        return view('postcodes.create');
    }

    /**
     *
     *
     * @param
     * @return
     */
    public function store(PostcodeRequest $request)
    {
        $this->authorize('index', new Postcode);

        $postcode = Postcode::create($request->all());

        flash()->success('Created!', 'Postcode created successfully.');

        return redirect('postcodes');
    }

    /**
     *
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $postcode = Postcode::findOrFail($id);

        $this->authorize('index', $postcode);

        return view('postcodes.edit', compact('postcode'));
    }

    /**
     *
     *
     * @param
     * @return
     */
    public function update(PostcodeRequest $request, $id)
    {
        $postcode = Postcode::findOrFail($id);

        $this->authorize('index', $postcode);

        $postcode->update($request->all());

        flash()->success('Updated!', 'Postcode updated successfully.');

        return redirect('postcodes');
    }

    /**
     *
     *
     * @param
     * @return
     */
    public function ifsNonDeliveryPostcodes()
    {
        $this->authorize('index', new Postcode);

        $postcodes = IfsNdPostcode::all();
        $postcodes = $postcodes->sortBy('postcode', SORT_NATURAL);

        return view('postcodes.ifs_nd_postcodes', compact('postcodes'));
    }

}
