@extends('layouts.mail')

@section('content')

<h3>Sender address does not have a BT postcode. Arrangements may be required.</h3>

<p>To view the full shipment details please visit <a href="{{ url('/shipments/' . $shipment->id) }}">{{ url('/shipments/' . $shipment->id) }}</a></p>

<h2>Collection Date Requested: {{$shipment->ship_date->format('d-m-Y')}}</h2>

<table border="0" cellspacing="0" width="50%" class="table">
    <tbody>
        <tr>
            <td>Consignment</td>
            <td><a href="{{ url('/shipments/' . $shipment->id) }}">{{$shipment->consignment_number}}</a></td>
        </tr>
        <tr>
            <td>Carrier</td>
            <td>{{$shipment->carrier->name}}</td>
        </tr>
        <tr>
            <td>Carrier Reference</td>
            <td>{{$shipment->carrier_consignment_number}}</td>
        </tr>
        <tr>
            <td>Sender</td>
            <td>{{$shipment->sender_name}}, {{$shipment->sender_address1}}, {{$shipment->sender_city}} {{$shipment->sender_postcode}}</td>
        </tr>        
    </tbody>
</table>

<p class="error">Please add a <b class="error"><i>Pickup arranged</i></b> tracking event to this shipment once pickup has been organised.</p>

@endsection