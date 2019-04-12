@extends('layouts.app')

@section('content')

    <h2>Add Document: {{$parentModel->consignment_number}}{{$parentModel->number}}</h2>

    <hr>

    {!! Form::Open(['url' => 'documents', 'class' => '', 'autocomplete' => 'off', 'files' => true]) !!}

    {!! Form::hidden('parent', $parent) !!}
    {!! Form::hidden('id', $parentModel->id) !!}

    <div class="row">
        <div class="col-sm-5">

            <div class="form-group row{{ $errors->has('document_type') ? ' has-danger' : '' }}">
                <label class="col-sm-3  col-form-label">
                    Document Type: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-sm-9">
                    {!! Form::select('document_type',['' => 'Please Select', 'invoice' => 'Customs Documentation', 'despatch' => 'Despatch Note', 'other' => 'Other'], old('document_type'), array('id' => 'document_type', 'class' => 'form-control')) !!}

                    @if ($errors->has('document_type'))
                        <span class="form-text">
                    <strong>{{ $errors->first('document_type')}}</strong>
                </span>
                    @endif

                </div>
            </div>

            <div class="form-group row{{ $errors->has('description') ? ' has-danger' : '' }}">
                <label class="col-sm-3  col-form-label">
                    Description: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-sm-9">
                    {!! Form::Text('description', old('description'), ['id' => 'description', 'class' => 'form-control', 'maxlength' => '80']) !!}

                    @if ($errors->has('description'))
                        <span class="form-text">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                    @endif
                </div>
            </div>

            <div class="form-group row{{ $errors->has('file') ? ' has-danger' : '' }}">
                <label class="col-sm-3  col-form-label">
                    PDF document: <abbr title="This information is required.">*</abbr>
                </label>

                <div class="col-sm-9">
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
                <div class="col-sm-9">
                    <a class="back btn btn-secondary" role="button">Cancel</a>
                    <button type="submit" class="btn btn-primary upload-file">Upload Document</button>
                </div>
            </div>

        </div>
    </div>

    {!! Form::Close() !!}

    <!-- Documents -->
    @include('documents.partials.documents', ['parentModel' => $parentModel, 'modelName' => $parent])

@endsection