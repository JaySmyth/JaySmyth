@extends('layouts.app')

@section('content')

    <h2 class="mb-5">DIM Check Upload</h2>

    {!! Form::Open(['url' => 'dim-check', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

    <div class="row mt-3">
        <div class="col-sm-5">

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