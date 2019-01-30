@extends('layouts.app')

@section('content')

<h2>Rate Upload</h2>

<br>

{!! Form::Open(['url' => "company-rate/$company_id/$service_id/upload", 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('rate_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Base Rate: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('rate_id', dropDown('standardSalesRates', 'Please select'), old('rate_id'), array('id' => 'rate_id', 'class' => 'form-control')) !!}

                @if ($errors->has('rate_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('rate_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('file') ? ' has-danger' : '' }}">
            <label class="col-sm-3  col-form-label">
                CSV File: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::file('file', old('file'), ['id' => 'file', 'class' => 'form-control']) !!}

                @if ($errors->has('file'))
                <span class="form-text">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary upload-file">Upload File</button>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection