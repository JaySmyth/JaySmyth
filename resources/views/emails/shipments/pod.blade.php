@extends('layouts.mail')

@section('content')

<h1 class="error">Action Required - a high number of jobs need POD</h1>

<h2>Please close these requests at the soonest opportunity. This can be done from the <a href="{{ url('/transport-jobs/close')}}">POD/Close Jobs</a> option under the <u>Transport</u> menu.</h2>

<h1>Awaiting Proof Of Delivery ({{$shipments->count()}})</h1>

<table border="0" cellspacing="0" width="100%" class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Consignment</th>
            <th>Reference</th>
            <th>Service</th>
            <th>From</th>
            <th>To</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $shipment)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{url('/shipments', $shipment->id)}}" title="View Shipment">{{$shipment->carrier_consignment_number}}</a></td>
            <td>{{$shipment->shipment_reference}}</td>
            <td>{{$shipment->service->code}}</td>
            <td>{{$shipment->sender_company_name ?: $shipment->sender_name}}, {{$shipment->sender_city}}</td>
            <td>{{$shipment->recipient_company_name ?: $shipment->recipient_name}}, {{$shipment->recipient_city}}</td>
            <td>{{$shipment->ship_date->format('l jS F')}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection