@extends('layouts.app')

@section('content')

<h2>Update Shipment Status: {{$shipment->number}}</h2>

<hr>

{!! Form::model($shipment, ['method' => 'POST', 'url' => 'sea-freight/' . $shipment->id . '/status', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('sea_freight_status_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Status: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-7">            
            {!! Form::select('sea_freight_status_id', $statuses, old('sea_freight_status_id'), array('id' => 'sea_freight_status_id', 'class' => 'form-control')) !!}

            @if ($errors->has('sea_freight_status_id'))
            <span class="form-text">
                <strong>{{ $errors->first('sea_freight_status_id') }}</strong>
            </span>
            @endif

        </div>
    </div>    
    <div class="form-group row{{ $errors->has('date') ? ' has-danger' : '' }}">
        <label class="col-sm-3  col-form-label">
            Date / Time:
        </label>

        <div class="col-sm-4">
            {!! Form::select('date', $dates, old('date', date('d-m-Y', strtotime('today'))), array('id' => 'date', 'class' => 'form-control')) !!} 

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
   
    <div class="row mt-4">
        <div class="col-sm-3">&nbsp;</div>
        <div class="col-sm-7">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Update Status</button>
        </div>
    </div>

</div>

{!! Form::Close() !!}

@endsection