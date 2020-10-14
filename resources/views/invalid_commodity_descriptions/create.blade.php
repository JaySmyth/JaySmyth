@extends('layouts.app')

@section('content')

    <h2>Add Invalid Commodity Description</h2>

    <hr>

    {!! Form::Open(['url' => 'invalid-commodity-descriptions', 'class' => '', 'autocomplete' => 'off']) !!}

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('description') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">
                Invalid Description: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('description', old('description'), ['id' => 'description', 'class' => 'form-control', 'maxlength' => '100']) !!}

                @if ($errors->has('description'))
                    <span class="form-text"><strong>{{ $errors->first('description') }}</strong></span>
                @endif

            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Add Invalid Description</button>
        </div>
    </div>

    {!! Form::Close() !!}


@endsection