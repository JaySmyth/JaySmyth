@extends('layouts.app')

@section('content')

<h2>Create Easypost Tracker</h2>



{!! Form::Open(['url' => 'create-tracker', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('tracking_code') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Tracking Code: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('tracking_code', old('tracking_code'), ['id' => 'tracking_code', 'class' => 'form-control', 'maxlength' => '50']) !!}

            @if ($errors->has('tracking_code'))
            <span class="form-text">
                <strong>{{ $errors->first('tracking_code') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('carrier') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Carrier: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('carrier', $carriers, old('carrier'), array('id' => 'carrier', 'class' => 'form-control')) !!}

            @if ($errors->has('carrier'))
            <span class="form-text">
                <strong>{{ $errors->first('carrier') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div>
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Create Tracker</button>
        </div>
    </div>

</div>

{!! Form::Close() !!}

@endsection 