@extends('layouts.app')

@section('navSearchPlaceholder', 'Company search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Company Name or Address:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Depot:
    </label>
    <div class="col-sm-8">
        {!! Form::select('depot_id', dropDown('associatedDepots', 'All Depots'), Request::get('depot_id'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Testing:
    </label>
    <div class="col-sm-8">
        {!! Form::select('testing',  dropDown('testing', 'All Modes'), Request::get('testing'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Status:
    </label>
    <div class="col-sm-8">
        {!! Form::select('enabled', dropDown('enabled', 'All Statuses'), Request::get('enabled'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3  col-form-label">
        Salesperson:
    </label>

    <div class="col-sm-8">
        {!! Form::select('sale_id', dropDown('sales', 'All Salespersons'), Request::get('sale_id'), array('class' => 'form-control')) !!}
    </div>
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@section('toolbar')   

@if(Auth::user()->hasIfsRole())
    @can('download_companies')<a href="companies/download?{{Request::getQueryString()}}" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>@endcan        
@endif

@endsection      


@include('partials.title', ['title' => 'companies', 'results'=> $companies, 'create' => 'company'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Company</th> 
            <th>Address</th>                                                               
            <th>Telephone</th>
            <th class="text-center">Depot</th>
            <th class="text-center">Users</th>                                                
            <th class="text-center">Mode</th>
            <th class="text-center">Status</th> 
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $company)
        <tr>                
            <td><a href="{{ url('/companies', $company->id) }}">{{$company->company_name}}</a></td>      
            <td>{{$company->address1}}, {{$company->city}}, {{$company->state}}, {{$company->postcode}}</td>                               
            <td>{{$company->telephone}}</td>
            <td class="text-center">
                @if($company->depot_id == 4)
                <span class="badge badge-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span>
                @elseif($company->depot_id != 1)
                <span class="badge badge-warning" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span>
                @else
                <span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span>
                @endif
            </td>
            <td class="text-center">{{$company->users->count()}}</td>                
            <td class="text-center">@if($company->testing)<span class="text-danger">Testing</span>@else<span class="text-success">Live</span>@endif</td>
            <td class="text-center">@if($company->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>                
            <td class="text-center text-nowrap">
                @can('update_company')<a href="{{ url('/companies/' . $company->id . '/edit') }}" title="Edit Company"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan
                @can('change_company_status')<a href="{{ url('/companies/' . $company->id . '/status') }}" title="Change Status"><span class="fas fa-ban ml-sm-2" aria-hidden="true"></span></a>@endcan
                @can('courier')<a href="{{url('/shipments?company=' . $company->id)}}" title="Shipment History"><span class="fas fa-history ml-sm-2" aria-hidden="true"></span></a>@endcan                                      
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'companies', 'results'=> $companies]) 
@include('partials.pagination', ['results'=> $companies])

@endsection