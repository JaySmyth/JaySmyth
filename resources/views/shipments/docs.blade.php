@extends('layouts.app')

@section('content')

<h2>Upload Documents - {{$shipment->consignment_number}}</h2>

{!! Form::Open(['url' => 'shipments', 'autocomplete' => 'off']) !!}

<div class="form-group row">
    {!! Form::label('Document Description:') !!}
    {!! Form::text('description') !!}
</div>

<div class="form-group row">
    {!! Form::label('Select File') !!}
    {!! Form::file('image', null) !!}
</div>

<div class="form-group row">
    {!! Form::submit('Upload Document') !!}
</div>

{!! Form::Close() !!}


<h4 class="mb-2">Documents <span class="badge badge-pill badge-secondary">{{ count($shipment->packages)}}</span></h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>File</th>
            <th>Document Description</th>
            <th>Date Uploaded</th>
            <th>Uploaded By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->packages as $package)
        <tr>
            <td>{{$package->index}}</td>
            <td>{{$package->length}} {{$shipment->dims_uom}}</td>
            <td>{{$package->width}} {{$shipment->dims_uom}}</td>
            <td>{{$package->height}} {{$shipment->dims_uom}}</td>
            <td>{{$package->weight}} {{$shipment->weight_uom}}</td>            
        </tr>
        @endforeach
    </tbody>
</table>


@endsection