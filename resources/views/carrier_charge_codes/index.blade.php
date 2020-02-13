@extends('layouts.app')

@section('navSearchPlaceholder', 'Charge code search...')

@section('advanced_search_form')

<input type="hidden" name="mode" value="{{Request::get('mode')}}">

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Carrier Charge Code or Description:
    </label>
    <div class="col-sm-8">
        {!! Form::Text('filter', Request::get('filter'), ['class' => 'form-control', 'maxlength' => '50']) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        SCS Charge Code:
    </label>
    <div class="col-sm-8">
        {!! Form::select('scs_code',  dropDown('scsChargeCodes', 'All SCS Codes'), Request::get('scs_code'), array('class' => 'form-control')) !!}
    </div>                        
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Carrier:
    </label>
    <div class="col-sm-8">
        {!! Form::select('carrier',  dropDown('carriers', 'All Carriers'), Request::get('carrier'), array('class' => 'form-control')) !!}
    </div>                        
</div>

@endsection

@include('partials.modals.advanced_search')


@section('content')

@include('partials.title', ['title' => 'carrier charge codes', 'results'=> $carrierChargeCodes])

<table class="table table-striped">
    <thead>
        <tr>            
            <th>Carrier Code</th>
            <th>SCS Code</th>
            <th>Description</th>                                            
            <th>Carrier</th>            
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($carrierChargeCodes as $charge)
        <tr>
            <td><a href="{{ url('/carrier-charge-codes/' . $charge->id . '/edit') }}">{{$charge->code}}</a></td>
            <td>{{$charge->scs_code}}</td>
            <td>{{$charge->description}}</td>            
            <td>{{$charge->carrier->name}}</td>            
            <td><a href="{{ url('/carrier-charge-codes/' . $charge->id . '/edit') }}" title="Edit Charge"><span class="fas fa-edit" aria-hidden="true"></span></a></td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'carrier charge codes', 'results'=> $carrierChargeCodes])
@include('partials.pagination', ['results'=> $carrierChargeCodes])

@endsection