@extends('layouts.app')

@section('content')

<h2>Revert Customer Rates</h2>

<br>

{!! Form::Open(['url' => "rates/revert", 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('company_id') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Company: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('company_id', dropDown('companies', 'Please select'), old('company_id'), array('id' => 'company_id', 'class' => 'form-control')) !!}

                @if ($errors->has('company_id'))
                <span class="form-text">
                    <strong>{{ $errors->first('company_id') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('revertToDate') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Restore Rate as at: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('revertToDate', dropDown('dates'), old('revertToDate', date('d-m-Y', strtotime('-1 week'))), array('id' => 'revertToDate', 'class' => 'form-control')) !!} 

                @if ($errors->has('file'))
                <span class="form-text">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('effectiveDate') ? ' has-danger' : '' }}">

            <label class="col-sm-3  col-form-label">
                Effective from: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('effectiveDate', dropDown('dates'), old('effectiveDate', date('d-m-Y', strtotime('-1 week'))), array('id' => 'effectiveDate', 'class' => 'form-control')) !!} 

                @if ($errors->has('file'))
                <span class="form-text">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('discount') ? ' has-danger' : '' }}">
            
            <label class="col-sm-3  col-form-label">
                Discount to Original: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('discount', old('discount'), ['id' => 'discount', 'class' => 'form-control', 'maxlength' => '8']) !!}

                @if ($errors->has('discount'))
                <span class="form-text">
                    <strong>{{ $errors->first('discount') }}</strong>
                </span>
                @endif
            </div>
        </div>


        <div class="form-group row buttons-main">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary upload-file">Revert Rate</button>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection