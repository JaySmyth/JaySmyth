@extends('layouts.app')

@section('content')

<h2>Add to Manifest {{$manifest->number}} <small>{{$manifest->carrier->name}}</small>
    <div class="float-right">
        {{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
    </div>
</h2>

<hr>

{!! Form::model($manifest, ['method' => 'POST', 'url' => ['manifests/add-shipment', $manifest->id], 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('consignment_number') ? ' has-danger' : '' }}">  

            <label class="col-sm-5  col-form-label">IFS Consignment Number:</label>

            <div class="col-sm-7">
                {!! Form::Text('consignment_number', old('consignment_number'), ['id' => 'consignment_number', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('consignment_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('consignment_number') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Shipment</button>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

<h4 class="mb-2">{{$manifest->number}}</h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>Manifest Number</th>
            <th>Mode</th>
            <th>Carrier</th>
            <th>Depot</th>
            <th>Date / Time Created</th>
            <th class="text-right">Services</th>
            <th class="text-right">Shipments</th>
            <th class="text-right">Weight ({{$manifest->depot->localisation->weight_uom}})</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$manifest->number}}</td>
            <td>{{$manifest->mode->label}}</td>
            <td>{{$manifest->carrier->name}}</td>
            <td>{{$manifest->depot->name}}</td>
            <td>{{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</td>
            <td class="text-right">{{$manifest->services}}</td>
            <td class="text-right">{{$manifest->shipments->count()}}</td>
            <td class="text-right">{{$manifest->weight}}</td>
        </tr>
    </tbody>
</table>


@endsection