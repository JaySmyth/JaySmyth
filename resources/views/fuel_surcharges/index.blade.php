@extends('layouts.app')

@section('navSearchPlaceholder', 'Service code...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Service Code:
    </label>
    <div class="col-sm-6">
        <input type="text" name="filter" id="filter" value="{{Input::get('filter')}}" class="form-control"> 
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Carrier:
    </label>
    <div class="col-sm-6">
        {!! Form::select('carrier',  dropDown('carriers', 'All Carriers'), Input::get('carrier'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        From Date:
    </label>
    <div class="col-sm-6">
        <input type="text" name="from_date" value="{{Input::get('from_date')}}" class="form-control datepicker" placeholder="From Date">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        To Date:
    </label>
    <div class="col-sm-6">
        <input type="text" name="to_date" value="{{Input::get('to_date')}}" class="form-control datepicker" placeholder="To Date">
    </div>   
</div>

@endsection


@include('partials.modals.advanced_search')

@section('content')

@can('create_fuel_surcharge')
    @section('toolbar')        
        <a href="{{ url('/fuel-surcharges/upload') }}" class="btn btn-success btn-sm btn-xs ml-sm-3 pr-sm-2 text-white" title="Fuel Surcharges Upload" role="button"><span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span>Upload</a>
    @endsection
@endcan

@include('partials.title', ['title' => 'fuel surcharges', 'results'=> $fuelSurcharges, 'create' => 'fuel_surcharge'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Carrier</th>
            <th>Service</th>
            <th>Date From</th>
            <th>Date To</th>
            <th class="text-right">Surcharge %</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fuelSurcharges as $fuelSurcharge)
        <tr>
            <td>{{$fuelSurcharge->carrier->name}}</td>
            <td><span class="badge badge-secondary text-uppercase">{{$fuelSurcharge->service_code}}</span></td>
            <td>{{$fuelSurcharge->from_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td>{{$fuelSurcharge->to_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-right">{{$fuelSurcharge->fuel_percent}}</td>
            <td class="text-center">
                
                @can('delete_fuel_surcharge')
                <a href="{{ url('/fuel-surcharges/' . $fuelSurcharge->id) }}" title="Delete Fuel Surcharge {{$fuelSurcharge->service_code}}" class="mr-2 delete" data-record-name="fuelSurcharge"><i class="fas fa-times"></i></a>
                @elsecan
                <i class="fas fa-times faded mr-2"></i>
                @endcan

                @can('create_fuel_surcharge')<a href="{{ url('/fuel-surcharges/' . $fuelSurcharge->id . '/edit') }}" title="Edit Fuel Surcharge"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@include('partials.no_results', ['title' => 'fuel surcharges', 'results'=> $fuelSurcharges])
@include('partials.pagination', ['results'=> $fuelSurcharges])

@endsection