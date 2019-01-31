@extends('layouts.app')

@section('content')

<h2>Manifest {{$manifest->number}} <small>{{$manifest->manifestProfile->name}}</small>
    <div class="float-right">
        {{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
    </div>
</h2>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>Manifest Number</th>
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
            <td>{{$manifest->carrier->name}}</td>
            <td>{{$manifest->depot->name}}</td>
            <td>{{$manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</td>
            <td class="text-right">{{$manifest->services}}</td>
            <td class="text-right">{{$manifest->shipments->count()}}</td>
            <td class="text-right">{{$manifest->weight}}</td>
        </tr>
    </tbody>
</table>


<div class="table table-striped-responsive">

    @foreach($shipmentsByService as $key => $service)

    <h4 class="mb-2">{{$services[$key]}} <span class="badge badge-pill badge-sm badge-secondary">{{count($shipmentsByService[$key]['shipments'])}}</span></h4>

    <table class="table table-sm table-striped table-bordered mb-5">
        <thead>
            <tr class="active">
                <th width="2%">#</th>
                <th width="6%">Service</th>
                <th width="12%">Consignment No.</th>
                <th width="12%">Carrier Consignment No.</th>
                <th width="15%">Shipper</th>
                <th width="15%">Destination</th>
                <th width="8%">Ship Date </th>
                <th width="8%" class="text-right">Pieces</th>
                <th width="8%" class="text-right">Weight ({{$manifest->depot->localisation->weight_uom}})</th>
            </tr>
        </thead>
        <tbody>

            @foreach($service['shipments'] as $i => $shipment)

            <tr>
                <td>{{$i + 1}}</td>
                <td><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
                <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
                <td>{{$shipment->carrier_consignment_number}}</td>
                <td>{{$shipment->company->company_name ?? ''}}</td>
                <td>{{$shipment->recipient_city}}, {{$shipment->recipient_country_code}}</td>
                <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
                <td class="text-right">{{$shipment->pieces}}</td>
                <td class="text-right">{{$shipment->weight}}</td>
            </tr>

            @endforeach

            <tr class="text-large bg-secondary text-white">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right"><strong>{{$service['pieces']}}</strong></td>
                <td class="text-right"><strong>{{number_format($service['weight'],2)}}</strong></td>
            </tr>
        </tbody>
    </table>

    @endforeach

</div>

@if(count($shipmentsByService) <= 0)
<div class="no-results">Sorry, no shipments found!</div>
@endif


@endsection