@extends('layouts.mail')

@section('content')

<h1 class="mb-3">Legacy Pricing Check - {{count($differences)}} Differences</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Consignment Number</th>
            <th>Carrier Ref</th>
            <th>Company</th>
            <th>Price (NEW)</th>
            <th>Price (LEGACY)</th>
            <th>Difference</th>
            <th>Service</th>
            <th>Pieces</th>
            <th>Chargeable Weight</th>
            <th>Sales Zone</th>
            <th>Sales Packaging</th>
        </tr>
    </thead>
     
    @foreach($differences as $shipment)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td><a href="{{url('/shipments/' . $shipment->shipment_id)}}">{{$shipment->consignment_number}}</a></td>
        <td>{{$shipment->carrier_consignment_number}}</td>
        <td>{{$shipment->company_name}} ({{$shipment->company_id}})</td>
        <td>{{$shipment->price_new}}</td>
        <td>
            @if($shipment->price_legacy)
            {{$shipment->price_legacy}}
            @else
            Unable to price
            @endif            
        </td>
        <td>{{abs($shipment->price_new - $shipment->price_legacy)}}</td>
        <td>{{$shipment->service}}</td>
        <td>{{$shipment->pieces}}</td>
        <td>{{$shipment->chargeable_weight}}</td>
        <td>{{$shipment->sales_zone}}</td>        
        <td>{{$shipment->sales_packaging}}</td> 
    </tr>

    @endforeach
 
</table>

@endsection