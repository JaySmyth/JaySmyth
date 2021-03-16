@extends('layouts.app')

@section('content')

<h2>Shipment Status Bulk Upload</h2>

{!! Form::Open(['url' => 'shipments/status-upload', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row mt-3">
    <div class="col-sm-5">
        @if(Auth::user()->hasRole('ifsa'))
        <div class="form-group row{{ $errors->has('status_code') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Status Code: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('status_code', dropDown('statusCodes', 'Please select'), old('status_code'), array('id' => 'status_code', 'class' => 'form-control')) !!}

                @if ($errors->has('status_code'))
                <span class="form-text">
                    <strong>{{ $errors->first('status_code') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @else
        {!! Form::hidden('status_code', Auth::user()->status_code, array('id' => 'status_code')) !!}
        @endif

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

    <div class="col-sm-7 float-right">
        <div class="card text-large">
            <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong class="ml-sm-1">Info</strong> <span class="ml-sm-4">Shipment Status Upload</span></div>
            <div class="card-body">
                <p>This facility enables you to set the status on up to 250 shipments using a csv file format.
                </p>

                <strong>Import Results</strong><br>
                <p>Upon a successful upload, a report detailing the results of the import will be automatically sent to you. The report will detail the shipments that have been updated and any failures. Multiple uploads of this file may result in tracking events being duplicated.</p>
                <p>Please allow 5 minutes for the the email to be delivered.</p>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection
