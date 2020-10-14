<?php

namespace App\Http\Controllers;

use App\Models\InvalidCommodityDescription;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class InvalidCommodityDescriptionController extends Controller
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
     * List descriptions.
     *
     * @param  Request  $request
     *
     * @return Factory|Application|View
     */
    public function index()
    {
        $this->authorize(new InvalidCommodityDescription);

        return view('invalid_commodity_descriptions.index', ['descriptions' => InvalidCommodityDescription::orderBy('description')->paginate(100)]);
    }


    /**
     * Store description.
     *
     * @param  Request  $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('viewAny', new InvalidCommodityDescription);

        $request->validate([
            'description' => 'required'
        ]);

        InvalidCommodityDescription::create(['description' => $request->description]);

        flash()->success('Created!', 'Description created successfully.');

        return redirect('invalid-commodity-descriptions');
    }


    /**
     * Delete.
     *
     * @param  InvalidCommodityDescription  $description
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(InvalidCommodityDescription $description)
    {
        $this->authorize('viewAny', $description);

        $description->delete();

        return response()->json(null, 204);
    }
}
