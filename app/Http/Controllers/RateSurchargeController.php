<?php

namespace App\Http\Controllers;

use App\Company;
use App\RateSurcharge;
use Illuminate\Http\Request;

class RateSurchargeController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(new RateSurcharge);

        dd('Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(new RateSurcharge);

        dd('Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(new RateSurcharge);

        dd('Store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize(new RateSurcharge);

        dd('Show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RateSurcharge $rateSurcharge, Company $company)
    {
        $rateSurcharge = \App\RateSurcharge::find(1);

        echo $rateSurcharge->name;
        //dd($rateSurcharge);

        //tr

        $this->authorize($rateSurcharge);

        dd('Edit2');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize(new RateSurcharge);

        dd('Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RateSurcharge $rateSurcharge)
    {
        $this->authorize(new RateSurcharge);

        $rateSurcharge->delete();

        flash()->success('Success!', 'Surcharge Reset');

        return back();
    }
}
