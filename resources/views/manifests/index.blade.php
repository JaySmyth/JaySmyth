@extends('layouts.app')

@section('navSearchPlaceholder', 'Manifest search...')

@section('advanced_search_form')

<input type="hidden" name="mode" value="{{Request::get('mode')}}">

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Manifest Number:
    </label>
    <div class="col-sm-7">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date From:
    </label>
    <div class="col-sm-7">
        <input type="text" name="date_from" value="{{Request::get('date_from')}}" class="form-control datepicker">
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date To:
    </label>
    <div class="col-sm-7">
        <input type="text" name="date_to" value="{{Request::get('date_to')}}" class="form-control datepicker">
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Type:
    </label>
    <div class="col-sm-7">
        {!! Form::select('manifest_profile_id', dropDown('manifestProfiles', 'All Types'), Request::get('manifest_profile_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Carriers:
    </label>
    <div class="col-sm-7">
        {!! Form::select('carrier_id', dropDown('carriers', 'All Carriers'), Request::get('carrier_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Depot:
    </label>
    <div class="col-sm-7">
        {!! Form::select('depot_id', dropDown('depots', 'All Depots'), Request::get('depot_id'), array('class' => 'form-control')) !!}
    </div>                        
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'manifests', 'results'=> $manifests])

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Manifest Number</th>                                
                <th>Profile</th>
                <th>Date / Time Created</th>   
                <th>Depot</th> 
                <th>Carrier</th>                                        
                
                <th class="text-right">Shipments</th>     
                <th class="text-right">Weight</th> 
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            @foreach($manifests as $manifest)
            <tr>
                <td><a href="{{ url('/manifests', $manifest->id) }}">{{$manifest->number}}</a></td>                                
                <td>{{$manifest->manifestProfile->name}}</td>
                <td>{{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</td>
                <td><span class="badge badge-secondary">{{$manifest->depot->code}}</span></td>
                <td>{{$manifest->carrier->name}}</td>                                
                
                <td class="text-right">{{$manifest->shipments->count()}}</td>
                <td class="text-right">{{$manifest->weight}}</td>
                <td class="text-center">                    
                    <a href="{{ url('/manifests/' .  $manifest->id . '/add-shipment') }}" title="Add Shipment"><span class="fas fa-plus-circle" aria-hidden="true"></span></a>
                    <a href="{{ url('/manifests/' . $manifest->id . '/download') }}" title="Download Excel"><span class="far fa-file-excel ml-sm-2" aria-hidden="true"></span></a>                    
                    <a href="{{ url('/manifests/' . $manifest->id . '/pdf') }}" title="Download PDF"><span class="fas fa-print ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/manifests/' . $manifest->id . '/summary') }}" title="Shipper Suimmary"><span class="fas fa-user ml-sm-2" aria-hidden="true"></span></a>
                </td>                                
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@include('partials.no_results', ['title' => 'manifests', 'results'=> $manifests])
@include('partials.pagination', ['results'=> $manifests])

@endsection