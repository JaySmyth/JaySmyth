@extends('layouts.mail')

@section('content')

<h2>Air Freight Booked</h2>

<h3 class="error">IMPORTANT SCS JOB - Please ensure that you update the CARGO DESCRIPTION field with {AWB:{{$shipment->consignment_number}}}</h3>

<p>To view the full shipment details please visit <a href="{{ url('/shipments/' . $shipment->id) }}">{{ url('/shipments/' . $shipment->id) }}</a></p>

<table border="0" cellspacing="0" width="50%" class="table">
    <tbody>
        <tr>
            <td>Consignment</td>
            <td><a href="{{ url('/shipments/' . $shipment->id) }}">{{$shipment->consignment_number}}</a></td>
        </tr>
        <tr>
            <td>Sender</td>
            <td>{{$shipment->sender_name}}, {{$shipment->sender_address1}}, {{$shipment->sender_city}} {{$shipment->sender_postcode}}</td>
        </tr>        
        <tr>
            <td>Recipient</td>
            <td>{{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}}</td>
        </tr> 
    </tbody>
</table>

@endsection