@extends('layouts.app')

@section('content')

<h2>Fuel Surcharges Upload</h2>

{!! Form::Open(['url' => 'fuel-surcharges/upload', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row mt-3">
    <div class="col-sm-5 pt-3">

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
        <div class="card  text-large">
            <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong class="ml-sm-3">Info</strong> <span class="ml-sm-4">Fuel Surcharge Upload from CSV File</span></div>
            <div class="card-body">
                <p>This facility enables you to upload Fuel Surcharge rates using a csv file format.</p>

                <strong>Required CSV Format</strong><br>
                <p>Please download this <a href="#">example CSV file</a>, containing sample data.</p>
                <strong>Required Information</strong><br>
                <p>Your CSV file should contain the following fields in this order</p>

                <ol>
                    <li>Carrier *</li>
                    <li>Service *</li>
                    <li>Percentage *</li>
                    <li>From Date*</li>
                    <li>To Date (YYYY-mm-dd) *</li>
                </ol>

                <strong>Import Results</strong><br>
                <p>Upon a successful upload, a report detailing the results of the import will be automatically sent to you. If you upload the same file again with any corrections, any previously changes will be ignored.</p>
                <p>Please allow 5 minutes for the the email to be delivered.</p>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection