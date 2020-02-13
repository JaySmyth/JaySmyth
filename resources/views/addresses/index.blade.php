@extends('layouts.app')

@section('navSearchPlaceholder', 'Address search...')

@section('advanced_search_form')

<input type="hidden" name="definition" value="{{Request::get('definition')}}">

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Name, Company or City:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Country:
    </label>
    <div class="col-sm-8">
        {!! Form::select('country_code', dropDown('countries', 'All Countries'), Request::get('country_code'), array('class' => 'form-control')) !!}
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

@endsection

@include('partials.modals.advanced_search')

@section('content')

@if(Request::get('definition') == 'sender')
@section('toolbar')                
<a href="{{ url('/addresses/create?definition=sender') }}" class="btn btn-success btn-sm btn-xs ml-sm-3 pr-sm-2 text-white" title="New Sender" role="button"><span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span>New</a>
@endsection
@else
@section('toolbar')        
<a href="{{ url('/addresses/import-recipient-addresses') }}" class="btn btn-success btn-sm btn-xs ml-sm-3 pr-sm-2 text-white" title="Import Recipients" role="button"><span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span>Import</a>
<a href="{{ url('/addresses/create?definition=recipient') }}" class="btn btn-success btn-sm btn-xs ml-sm-3 pr-sm-2 text-white" title="New Recipient" role="button"><span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span>New</a>
@endsection
@endif

@include('partials.title', ['title' => Request::get('definition') . 's', 'results'=> $addresses])

{!! Form::Open(['url' => 'addresses', 'autocomplete' => 'off']) !!}

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Country</th>                
                <th>Shipper</th>
                <th class="text-center">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($addresses as $address)
            <tr>
                <td><a href="{{ url('/addresses/' . $address->id . '/edit') }}" title="Edit Address">@if($address->name){{$address->name}}@else <i>Unknown</i> @endif</a></td>
                <td>
                    @if($address->company_name)
                    {{$address->company_name}},                
                    @endif
                    {{$address->address1}}, {{$address->city}}</td>
                <td>{{$address->country}}</td>
                <td>{{$address->company->site_name}}</td>
                <td class="text-center text-nowrap">                                        
                    <a href="{{ url('/addresses/' . $address->id) }}" title="Delete {{$address->name}}" class="delete mr-2" data-record-name="address"><i class="fas fa-times"></i></a>
                    <a href="{{ url('/addresses/' . $address->id . '/edit') }}" title="Edit Address"><i class="fas fa-edit" aria-hidden="true"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{!! Form::Close() !!}

@include('partials.no_results', ['title' => 'addresses', 'results'=> $addresses])
@include('partials.pagination', ['results'=> $addresses])

@endsection