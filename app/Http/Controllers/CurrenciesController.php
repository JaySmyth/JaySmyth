<?php

namespace App\Http\Controllers;

use App\Models\Models\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
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
        $this->authorize(new Currency);

        $currencies = Currency::orderBy('display_order')->orderBy('currency')->paginate(50);

        return view('currencies.index', compact('currencies'));
    }

    /**
     * @param
     * @return
     */
    public function create()
    {
        $this->authorize('viewAny', new Currency);

        return view('currencies.create');
    }

    /**
     * @param
     * @return
     */
    public function store(CurrencyRequest $request)
    {
        $this->authorize('viewAny', new Currency);

        $currency = Currency::create($request->all());

        flash()->success('Created!', 'Currency created successfully.');

        return redirect('currencies');
    }

    /**
     * @param
     * @return
     */
    public function edit($id)
    {
        $currency = Currency::findOrFail($id);

        $this->authorize('viewAny', $currency);

        return view('currencies.edit', compact('currency'));
    }

    /**
     * @param
     * @return
     */
    public function update(CurrencyRequest $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $this->authorize('viewAny', $currency);

        $currency->update($request->all());

        flash()->success('Updated!', 'Currency updated successfully.');

        return redirect('currencies');
    }
}
