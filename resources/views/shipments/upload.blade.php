@extends('layouts.app')

@section('content')

<h2>Shipment Upload</h2>

{!! Form::Open(['url' => 'shipments/upload', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row mt-3">
    <div class="col-sm-5">

        @if(Auth::user()->hasMultipleImportConfigs())

        <div class="form-group row{{ $errors->has('import_config_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Upload Profile: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('import_config_id', dropDown('importConfigs', 'Please select'), old('import_config_id'), array('id' => 'import_config_id', 'class' => 'form-control')) !!}

                @if ($errors->has('import_config_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('import_config_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        @else
        {!! Form::hidden('import_config_id', Auth::user()->import_config_id, array('id' => 'import_config_id')) !!}
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
            <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong class="ml-sm-1">Info</strong> <span class="ml-sm-4">Shipment Upload from CSV File</span></div>
            <div class="card-body">
                <p>This facility enables you to create up to 250 shipments using a csv file format.
                    @if(Auth::user()->hasMultipleImportConfigs())
                    The shipments will be created against the upload profile that you select adjacent. 
                    @endif
                </p>               
                              
                <Strong>International Shipments</strong>
                <p>These will default to Recipient pays Duty/ Taxes.</p>

                <strong>Import Results</strong><br>
                <p>Upon a successful upload, a report detailing the results of the import will be automatically sent to you. The report will detail the shipments that have been created and any failures. If you upload the same file again with any corrections, any previously created shipments will be ignored.</p>
                <p>Please allow 5 minutes for the the email to be delivered.</p>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection