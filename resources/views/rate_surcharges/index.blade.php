@extends('layouts.app')

@section('advanced_search_form')



<input type="hidden" name="filter">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <input type="text" name="date_from" value="{{Input::get('date_from')}}" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="col-sm-2">
            <input type="text" name="date_to" value="{{Input::get('date_to')}}" class="form-control datepicker" placeholder="Date To">
        </div>
        <div class="col-sm-3">
            {!! Form::select('carrier',  dropDown('carriers', 'All Carriers'), Input::get('carrier'), array('class' => 'form-control')) !!}
        </div>
        <div class="col-sm-4">
            {!! Form::select('service', dropDown('services', 'All Services'), Input::get('service'), array('class' => 'form-control')) !!}
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-secondary ">Search</button>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection

@section('content')

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
            <td>{{$fuelSurcharge->from_date}}</td>
            <td>{{$fuelSurcharge->to_date}}</td>
            <td class="text-right">{{$fuelSurcharge->fuel_percent}}</td>
            <td class="text-center">
                @can('delete_fuel_surcharge')<a href="{{ url('/fuel-surcharges/' .  $fuelSurcharge->id . '/delete') }}" title="Delete Fuel Surcharge"><span class="fas fa-times" aria-hidden="true"></span></a>@endcan
                @can('create_fuel_surcharge')<a href="{{ url('/fuel-surcharges/' . $fuelSurcharge->id . '/edit') }}" title="Edit Fuel Surcharge"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@include('partials.no_results', ['title' => 'fuel surcharges', 'results'=> $fuelSurcharges])
@include('partials.pagination', ['results'=> $fuelSurcharges])

@endsection