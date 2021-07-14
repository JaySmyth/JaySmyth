@extends('layouts.app')

@section('content')

<h2>Increase All Customer Rates</h2>

<br>

{!! Form::Open(['url' => "rates/increase", 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('type_id') ? ' has-danger' : '' }}">

            <label class="col-sm-4  col-form-label">
                Increase Type: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('type_id', dropDown('rateTypes', 'Please select'), old('type_id'), array('id' => 'type_id', 'class' => 'form-control')) !!}

                @if ($errors->has('type_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('type_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('effective_from') ? ' has-danger' : '' }}">

            <label class="col-sm-4  col-form-label">
                Effective From: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('effective_from', dropDown('datesFuture'), old('effective_from', date('Y-m-d')), ['id' => 'effective_from', 'class' => 'form-control']) !!}

                @if ($errors->has('effective_from'))
                <span class="form-text">
                    <strong>{{ $errors->first('effective_from') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('effective_to') ? ' has-danger' : '' }}">

            <label class="col-sm-4  col-form-label">
                Effective To: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('effective_to', dropDown('datesLongFuture'), old('effective_to', date('Y-m-d')), ['id' => 'effective_to', 'class' => 'form-control']) !!}

                @if ($errors->has('effective_to'))
                <span class="form-text">
                    <strong>{{ $errors->first('effective_to') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('increase') ? ' has-danger' : '' }}">

            <label class="col-sm-4 col-form-label">
                Percentage Increase: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('increase', old('increase'), ['id' => 'increase', 'class' => 'form-control', 'maxlength' => '8', 'Placeholder' => 'eg. 3.50']) !!}

                @if ($errors->has('increase'))
                <span class="form-text">
                    <strong>{{ $errors->first('increase') }}</strong>
                </span>
                @endif
            </div>
        </div>


        <div class="form-group row buttons-main">
            <div class="col-sm-4">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Increase Rates</button>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection
