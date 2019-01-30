@extends('layouts.app')

@section('content')

<h2>Import Recipients</h2>

<br>

{!! Form::Open(['url' => 'import-recipients', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row">
    <div class="col-sm-5">

        @if(Auth::user()->hasMultipleCompanies())

        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Shipper: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' => 'form-control')) !!}

                @if ($errors->has('company_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @else
        {!! Form::hidden('company_id', Auth::user()->company_id, array('id' => 'company_id')) !!}
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
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary upload-file">Import Recipients</button>
            </div>
        </div>

    </div>

    <div class="col-sm-6 float-right">
        <div class="card text-large">
            <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong class="ml-sm-1">Info</strong> <span class="ml-sm-4">Recipient Address Import from CSV File</span></div>
            <div class="card-body">
                <p>This facility allows you to import recipient addresses from a CSV file upload.
                    @if(Auth::user()->hasMultipleCompanies())
                    The addresses will be associated with the shipper that you select adjacent. 
                    @endif
                </p>
                <strong>Required CSV Format</strong><br>
                <p>Please download this <a href="{{url('download/addresses_example.csv')}}">example CSV file</a>, containing sample data for 3 addresses.</p>
                <strong>Required Information</strong>
                <p>Your CSV file should contain the following fields in this order (* indicates mandatory):</p>

                <ol class="row">
                    <li class="col-sm-6">Name *</li>
                    <li class="col-sm-6">Company Name</li>
                    <li class="col-sm-6">Address Line 1 *</li>
                    <li class="col-sm-6">Address Line 2</li>
                    <li class="col-sm-6">Address Line 3</li>
                    <li class="col-sm-6">City *</li>
                    <li class="col-sm-6">County / State *</li>
                    <li class="col-sm-6">Postcode *</li>
                    <li class="col-sm-6">Country Code *</li>
                    <li class="col-sm-6">Telephone</li>
                    <li class="col-sm-6">Email</li>
                    <li class="col-sm-6">Type * <span class="small text-muted">(r)esidential (c)omercial</span></li>
                </ol>

                <strong>Import Results</strong><br>
                <p>Upon a successful upload, a report detailing the results of the import will be automatically sent to you. It usually takes a couple of minutes for the email to be delivered.</p>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection