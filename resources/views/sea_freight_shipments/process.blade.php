@extends('layouts.app')

@section('content')

<h2>Process Shipment: {{$shipment->number}} / {{$shipment->reference}}</h2>

<hr>

{!! Form::model($shipment, ['method' => 'POST', 'url' => 'sea-freight/' . $shipment->id . '/process', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="col-sm-6">

    <div class="form-group row{{ $errors->has('shipping_line_id') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Shipping Line: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">            
            {!! Form::select('shipping_line_id', dropDown('shippingLines', 'Please select'), old('shipping_line_id'), array('id' => 'shipping_line_id', 'class' => 'form-control')) !!}

            @if ($errors->has('shipping_line_id'))
            <span class="form-text">
                <strong>{{ $errors->first('shipping_line_id') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('number_of_containers') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            No. of Containers: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">            
            {!! Form::select('number_of_containers', dropDown('numeric'), old('number_of_containers'), array('id' => 'number_of_containers', 'class' => 'form-control')) !!}

            @if ($errors->has('number_of_containers'))
            <span class="form-text">
                <strong>{{ $errors->first('number_of_containers') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('bill_of_lading') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Bill of Lading: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('bill_of_lading', old('bill_of_lading'), ['id' => 'bill_of_lading', 'class' => 'form-control', 'maxlength' => '30']) !!}

            @if ($errors->has('bill_of_lading'))
            <span class="form-text">
                <strong>{{ $errors->first('bill_of_lading') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('estimated_departure_date') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Estimated Departure Date: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('estimated_departure_date', dropDown('dates'), old('estimated_departure_date', date('d-m-Y', strtotime('today'))), array('id' => 'estimated_departure_date', 'class' => 'form-control')) !!} 

            @if ($errors->has('estimated_departure_date'))
            <span class="form-text">
                <strong>{{ $errors->first('estimated_departure_date') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('estimated_arrival_date') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Estimated Arrival Date: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::select('estimated_arrival_date', dropDown('dates'), old('estimated_arrival_date', date('d-m-Y', strtotime('today'))), array('id' => 'estimated_arrival_date', 'class' => 'form-control')) !!} 

            @if ($errors->has('estimated_arrival_date'))
            <span class="form-text">
                <strong>{{ $errors->first('estimated_arrival_date') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('port_of_loading') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Port of Loading: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('port_of_loading', old('port_of_loading'), ['id' => 'port_of_loading', 'class' => 'form-control', 'maxlength' => '30']) !!}

            @if ($errors->has('port_of_loading'))
            <span class="form-text">
                <strong>{{ $errors->first('port_of_loading') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('port_of_discharge') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Port of Discharge: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('port_of_discharge', old('port_of_discharge'), ['id' => 'port_of_discharge', 'class' => 'form-control', 'maxlength' => '100']) !!}

            @if ($errors->has('port_of_discharge'))
            <span class="form-text">
                <strong>{{ $errors->first('port_of_discharge') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('vessel') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            Vessel: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('vessel', old('vessel'), ['id' => 'vessel', 'class' => 'form-control', 'maxlength' => '100']) !!}

            @if ($errors->has('vessel'))
            <span class="form-text">
                <strong>{{ $errors->first('vessel') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="form-group row{{ $errors->has('scs_job_number') ? ' has-danger' : '' }}">
        <label class="col-sm-5  col-form-label">
            SCS Job Number: <abbr title="This information is required.">*</abbr>
        </label>

        <div class="col-sm-6">
            {!! Form::Text('scs_job_number', old('scs_job_number'), ['id' => 'scs_job_number', 'class' => 'form-control', 'maxlength' => '100']) !!}

            @if ($errors->has('scs_job_number'))
            <span class="form-text">
                <strong>{{ $errors->first('scs_job_number') }}</strong>
            </span>
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">&nbsp;</div>
        <div class="col-sm-6">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button enabled="submit" class="btn btn-primary ml-sm-4">Process Shipment</button>
        </div>
    </div>

</div>

{!! Form::Close() !!}

@endsection