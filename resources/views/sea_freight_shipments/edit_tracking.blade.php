@extends('layouts.app')

@section('content')

    <h2>Edit Tracking: {{$seaFreightTracking->status_name}}
        <small>({{ $seaFreightTracking->seaFreightShipment->number }})</small>
    </h2>

    {!! Form::model($seaFreightTracking, ['method' => 'POST', 'url' => 'sea-freight-tracking/' . $seaFreightTracking->id, 'autocomplete' => 'off']) !!}

    {{ method_field('PATCH') }}

    <div class="row mt-4">
        <div class="col-sm-6">

            <div class="form-group row{{ $errors->has('date') ? ' has-danger' : '' }}">
                <label class="col-sm-3  col-form-label">
                    Date / Time:
                </label>

                <div class="col-sm-4">
                    {!! Form::select('date', dropDown('datesLong'), old('date', date('d-m-Y', strtotime($seaFreightTracking->datetime))), array('id' => 'date', 'class' => 'form-control')) !!}

                    @if ($errors->has('date'))
                        <span class="form-text">
                <strong>{{ $errors->first('date') }}</strong>
            </span>
                    @endif

                </div>

                <div class="col-sm-3">
                    {!! Form::select('time', dropDown('times'), old('time', date('H:i', strtotime($seaFreightTracking->datetime))), array('id' => 'time', 'class' => 'form-control')) !!}

                    @if ($errors->has('time'))
                        <span class="form-text">
                <strong>{{ $errors->first('time') }}</strong>
            </span>
                    @endif

                </div>

            </div>

            <div class="form-group row{{ $errors->has('message') ? ' has-danger' : '' }}">
                <label class="col-sm-3  col-form-label">
                    Custom Message:
                </label>

                <div class="col-sm-7">
                    {!! Form::Text('message', old('message'), ['id' => 'message', 'class' => 'form-control']) !!}

                    @if ($errors->has('message'))
                        <span class="form-text">
                <strong>{{ $errors->first('message') }}</strong>
            </span>
                    @endif

                </div>
            </div>

            <div class="form-group row buttons-main">
                <div class="col-sm-5">&nbsp;</div>
                <div class="col-sm-6">
                    <a class="back btn btn-secondary" role="button">Cancel</a>
                    <button size="submit" class="btn btn-primary">Update Tracking</button>
                </div>
            </div>
        </div>

    </div>

    {!! Form::Close() !!}

@endsection