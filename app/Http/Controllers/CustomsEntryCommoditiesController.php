<?php

namespace App\Http\Controllers;

use App\CustomsEntryCommodity;
use App\Http\Requests\CustomsEntryCommodityRequest;

class CustomsEntryCommoditiesController extends Controller
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
     * Displays edit commodity form.
     *
     * @param
     * @return
     */
    public function edit($id)
    {
        $customsEntryCommodity = CustomsEntryCommodity::findOrFail($id);

        return view('customs_entry_commodities.edit', compact('customsEntryCommodity'));
    }

    /**
     * Updates an existing user.
     *
     * @param
     * @return
     */
    public function update(CustomsEntryCommodityRequest $request, $id)
    {
        $customsEntryCommodity = CustomsEntryCommodity::findOrFail($id);

        // Update the customs entry
        $customsEntryCommodity->update($request->all());

        flash()->success('Updated!', 'Commodity updated successfully.');

        return redirect('customs-entries/'.$customsEntryCommodity->customs_entry_id);
    }

    /**
     * Delete a commodity line.
     *
     * @param CustomsEntryCommodity $customsEntryCommodity
     * @return type
     */
    public function destroy(CustomsEntryCommodity $customsEntryCommodity)
    {
        //$this->authorize($customsEntryCommodity);

        $customsEntryCommodity->delete();

        return response()->json(null, 204);
    }
}
