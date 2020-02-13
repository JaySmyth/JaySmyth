@extends('layouts.app')

@section('navSearchPlaceholder', 'Commodity search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Description, part number or commodity code:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Shipper:
    </label>
    <div class="col-sm-8">
        {!! Form::select('company_id', dropDown('sites', 'All Shippers'), Request::get('company_id'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Currency:
    </label>
    <div class="col-sm-8">
        {!! Form::select('currency_code', dropDown('currencies', 'All Currencies'), Request::get('currency_code'), array('class' => 'form-control')) !!}
    </div>   
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'commodities', 'results'=> $commodities,  'create' => 'commodities'])

{!! Form::Open(['url' => 'commodities', 'autocomplete' => 'off']) !!}

<table class="table table-striped">
    <thead>                        
        <tr>
            <th>Description</th>
            <th>Product Code</th>
            <th>Manufacturer</th>                 
            <th class="text-center">Country</th>
            <th>Unit Value</th>
            <th>Commodity Code</th>                
            <th>Shipper</th>
            <th class="text-center">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($commodities as $commodity)
        <tr>
            <td><a href="{{ url('/commodities/' . $commodity->id . '/edit') }}" title="Edit Commodity">{{$commodity->description ?? 'Unknown'}}</a></td>
            <td>
                @if($commodity->product_code)
                {{$commodity->product_code}}
                @else
                <i>Unknown</i>
                @endif
            </td>
            <td>
                @if($commodity->manufacturer)
                {{$commodity->manufacturer}}
                @else
                <i>Unknown</i>
                @endif                    
            </td>
            <td class="text-center">{{$commodity->country_of_manufacture}}</td>
            <td>{{$commodity->unit_value}} {{$commodity->currency_code}}</td>
            <td>
                @if($commodity->commodity_code)
                {{$commodity->commodity_code}}
                @else
                <i>Unknown</i>
                @endif                    
            </td>                                
            <td>{{$commodity->company->site_name}}</td>
            <td class="text-center text-nowrap">                    
                <a href="{{ url('/commodities/' . $commodity->id) }}" title="Delete Commodity" class="delete mr-2" data-record-name="commodity"><i class="fas fa-times"></i></a>
                <a href="{{ url('/commodities/' . $commodity->id . '/edit') }}" title="Edit Commodity"><span class="fas fa-edit" aria-hidden="true"></span></a>                           
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! Form::Close() !!}

@include('partials.no_results', ['title' => 'commodities', 'results'=> $commodities])
@include('partials.pagination', ['results'=> $commodities])

@endsection