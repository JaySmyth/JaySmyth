@extends('layouts.app')

@section('content')

<h2>Shipper Summary - Manifest {{$manifest->number}} <small>{{$manifest->carrier->name}}</small>
    <div class="float-right">
        {{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
    </div>
</h2>

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
            <td><a href="{{ url('/manifests', $manifest->id) }}">{{$manifest->number}}</a></td>
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

<h4 class="mb-2">Shippers <span class="badge badge-pill badge-sm badge-secondary">{{$shippers->count()}}</span></h4>

<table class="table table-sm table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>#</th>
            <th>Company Name</th>
            <th>Name</th>
            <th>Telephone</th>
            <th>Email</th>
            <th class="text-right">Total Shipments</th>                
        </tr>
    </thead>
    <tbody>

        @foreach($shippers as $shipper)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{ url('/companies', $shipper->company_id) }}">{{$shipper->sender_company_name}}</a></td>
            <td>{{$shipper->name}}</td>
            <td>{{$shipper->telephone}}</td>
            <td><a href="mailto:{{$shipper->email}}">{{$shipper->email}}</a></td>
            <td class="text-right">{{$shipper->total}}</td>
        </tr>
        @endforeach

    </tbody>
</table>


@endsection