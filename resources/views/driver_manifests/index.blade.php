@extends('layouts.app')

@section('navSearchPlaceholder', 'Driver manifest search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Manifest number:
    </label>
    <div class="col-sm-7">
        <input type="text" name="filter" id="filter" value="{{Input::get('filter')}}" class="form-control">
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date:
    </label>
    <div class="col-sm-7">
        <input type="text" name="date" value="@if(!Input::get('date')){{date(Auth::user()->date_format)}}@else{{Input::get('date')}}@endif" class="form-control datepicker">
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Driver:
    </label>
    <div class="col-sm-7">
        {!! Form::select('driver_id',  dropDown('drivers', 'All Drivers'), Input::get('driver_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Vehicle:
    </label>
    <div class="col-sm-7">
        {!! Form::select('vehicle_id',  dropDown('vehicles', 'All Vehicles'), Input::get('vehicle_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Depot:
    </label>
    <div class="col-sm-7">
        {!! Form::select('depot_id', dropDown('associatedDepots', 'All Depots'), Input::get('depot_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'driver manifests', 'results'=> $driverManifests, 'create' => 'driver_manifest'])

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>           
                <th>Number</th>
                <th>Driver</th>                 
                <th>Date</th>                
                <th>Registration</th>
                <th>Vehicle Type</th>                  
                <th>Depot</th>
                <th class="text-right">Collections</th>
                <th class="text-right">Deliveries</th>
                <th class="text-right">Total Jobs</th>                
                <th class="text-right">Locations</th>                
                <th class="text-center">Status</th>                
                @can('close_driver_manifest')<th class="text-center">&nbsp;</th>@endcan
            </tr>
        </thead>
        <tbody>
            @foreach($driverManifests as $driverManifest)
            <tr>
                <td><a href="{{ url('/driver-manifests', $driverManifest->id) }}">{{$driverManifest->number}}</a></td>                                             
                <td>{{$driverManifest->driver->name}}</td>                  
                <td>{{$driverManifest->date->timezone(Auth::user()->time_zone)->format('l, jS F')}}</td>                   
                <td>{{$driverManifest->vehicle->registration}}</td>
                <td>{{$driverManifest->vehicle->type}}</td>
                <td><span class="badge badge-secondary">{{$driverManifest->depot->code}}</span></td>
                <td class="text-right">{{$driverManifest->collection_count}}</td>
                <td class="text-right">{{$driverManifest->delivery_count}}</td>
                <td class="text-right">{{$driverManifest->total_count}}</td>
                <td class="text-right"><strong>{{$driverManifest->location_count}}</strong></td>
                <td class="text-center">@if(!$driverManifest->closed)<span class="text-success">Open</span>@else Closed @endif</td>   

                @can('close_driver_manifest')

                <td class="text-center">

                    @if($driverManifest->isOpenable())
                    <a href="{{ url('/driver-manifests/' . $driverManifest->id . '/open') }}" title="Re-open manifest"><span class="fas fa-open" aria-hidden="true"></span></a>
                    @else
                    @if(!$driverManifest->closed)
                    <a href="{{ url('/driver-manifests/' . $driverManifest->id . '/close') }}" title="Close manifest"><span class="fas fa-times-circle" aria-hidden="true"></span></a>
                    @else
                    <span class="fas fa-times-circle faded" aria-hidden="true"></span>
                    @endif

                    @endif
                    <a href="{{ url('/driver-manifests/' . $driverManifest->id . '/pdf') }}" title="Download PDF"><span class="fas fa-print ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/driver-manifests/' . $driverManifest->id . '/dockets') }}" title="Download POD Dockets"><span class="fas fa-pencil ml-sm-2" aria-hidden="true"></span></a>
                </td>

                @endcan

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('partials.no_results', ['title' => 'driver manifests', 'results'=> $driverManifests])
@include('partials.pagination', ['results'=> $driverManifests])

@endsection