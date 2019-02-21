@extends('layouts.mail')

@section('content')

<h1>{{$subject}}</h1>

<h2 class="error">Shipping details for the following {{$shipments->count()}} shipments have not been received. Please investigate.</h2>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>            
            <th>IFS Consignment Number</th>
            <th>Customer Ref</th>
            <th>Ship Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($shipments as $shipment)
        <tr>
            <td>{{$loop->iteration}}</td>            
            <td>{{$shipment->consignment_number}}</td>
            <td>{{$shipment->shipment_reference}}</td>
            <td>{{$shipment->ship_date}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection