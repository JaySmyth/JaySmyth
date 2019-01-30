@extends('layouts.mail')

@section('content')

<h2>Air Freight Booking Confirmation</h2>

<p>Shipment booked with airline successfully.</p>

<p>To view the full tracking information for this shipment please visit: <a href="https://apps.dbschenkerusa.com/apps/Tracking/SchenkerDetail.aspx?rt=aw&rn={{$shipment->carrier_consignment_number}}">https://apps.dbschenkerusa.com/apps/Tracking/SchenkerDetail.aspx?rt=aw&rn={{$shipment->carrier_consignment_number}}</a></p>

<p>If you require further assistance please contact IFS Air Exports department.</p>

<table border="0" cellspacing="0" width="50%" class="table">
    <tbody>
        <tr>
            <td>IFS Reference</td>
            <td><a href="{{ url('/shipments/' . $shipment->id) }}">{{$shipment->consignment_number}}</a></td>
        </tr>
        <tr>
            <td>Airline Reference</td>
            <td>{{$shipment->carrier_consignment_number}}</td>
        </tr>
        <tr>
            <td>Customer Reference</td>
            <td>{{$shipment->shipment_reference}}</td>
        </tr>
        <tr>
            <td>Sender</td>
            <td>{{$shipment->sender_name}}, {{$shipment->sender_address1}}, {{$shipment->sender_city}} {{$shipment->sender_country_code}}</td>
        </tr> 
        <tr>
            <td>Recipient</td>
            <td>{{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}}</td>
        </tr> 
    </tbody>
</table>

@endsection