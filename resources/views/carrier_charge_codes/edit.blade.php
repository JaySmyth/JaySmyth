@extends('layouts.app')

@section('content')

<h2>Edit Charge Code: {{$carrierChargeCode->description}}</h2>

<hr>

<ul class="float-right mr-sm-5 text-large">
    <li>Carrier: {{$carrierChargeCode->carrier->name}}</li>
    <li>Carrier Code: {{$carrierChargeCode->code}}</li>
    <li>SCS Code: {{$carrierChargeCode->scs_code}}</li>
    <li>Charge Description: {{$carrierChargeCode->description}}</li>
</ul>

{!! Form::model($carrierChargeCode, ['method' => 'POST', 'url' => ['carrier-charge-codes', $carrierChargeCode->id]]) !!}

{{ method_field('PATCH') }}

<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('scs_code') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">SCS Code:</label>

        <div class="col-sm-6">{!! Form::select('scs_code', dropDown('scsChargeCodes'), old('scs_code'), array('class' => 'form-control')) !!}</div>
    </div>

    <div class="form-group row{{ $errors->has('code') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Description:            
        </label>

        <div class="col-sm-6">
            {!! Form::Text('description', old('description'), ['id' => 'description', 'class' => 'form-control', 'maxlength' => '100']) !!}

            @if ($errors->has('description'))
            <span class="form-text">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Update Charge Code</button>
        </div>
    </div>

</div>

{!! Form::Close() !!}

@endsection