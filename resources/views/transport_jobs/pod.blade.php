@extends('layouts.app')

@section('content')

{!! Form::Open(['url' => 'transport-jobs/pod', 'class' => '']) !!}

<div class="row">

    <div class="col-sm-4">

        <h2>Admin POD</h2>
        
        <hr>
        
        <div class="form-group row{{ $errors->has('number') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">
                Job / Consignment: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('number', old('number'), ['class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('number'))
                <span class="form-text">
                    <strong>{{ $errors->first('number') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('signature') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Signature: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('signature', old('signature'), ['class' => 'form-control', 'maxlength' => '30']) !!}

                @if ($errors->has('signature'))
                <span class="form-text">
                    <strong>{{ $errors->first('signature') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-5  col-form-label">
                Date / Time: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-4">
                {!! Form::select('date', dropDown('dates'), old('date', date('d-m-Y', strtotime('today'))), array('id' => 'date', 'class' => 'form-control')) !!}

                @if ($errors->has('date'))
                <span class="form-text">
                    <strong>{{ $errors->first('date') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-3">
                {!! Form::select('time', dropDown('times'), old('time', date('H:i')), array('id' => 'time', 'class' => 'form-control')) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">POD Shipment</button>
            </div>
        </div>

    </div>
    <div class="col-sm-1"></div>

</div>

{!! Form::Close() !!}

@endsection