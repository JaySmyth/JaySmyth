@extends('layouts.app')

@section('content')

    <h2>IFS Non Delivery Postcode</h2>

    <hr>

    {!! Form::Open(['url' => 'ifs-nd-postcodes', 'class' => '', 'autocomplete' => 'off']) !!}

    <div class="col-sm-6">

        <div class="form-group row{{ $errors->has('postcode') ? ' has-danger' : '' }}">
            <label class="col-sm-5 col-form-label">
                Postcode: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-6">
                {!! Form::Text('postcode', old('postcode'), ['id' => 'postcode', 'class' => 'form-control', 'maxlength' => '12']) !!}

                @if ($errors->has('postcode'))
                    <span class="form-text">
                <strong>{{ $errors->first('postcode') }}</strong>
            </span>
                @endif

            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Add Postcode</button>
        </div>
    </div>

    {!! Form::Close() !!}


@endsection