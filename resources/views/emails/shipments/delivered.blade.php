@extends('layouts.mail')

@section('content')

<p>Dear customer,</p>

<p>Your shipment <b>{{$shipment->consignment_number}}</b> with reference <b>{{$shipment->shipment_reference}}</b> has been delivered.</p>

<p>*** {{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}} ***</p>

<p><b>POD Signature:</b> {{$shipment->pod_signature}}</p>

<p><b>Delivery Date/Time:</b> {{$shipment->getDeliveryDate()}}</p>

<p>To view the full tracking information for this shipment please visit <a href="{{ url('/tracking/' . $shipment->token) }}">{{ url('/tracking/' . $shipment->token) }}</a></p>

<p><b><i>Thank you for using <span class="company-name"><span class="blue">IFS Courier</span> <span class="red">Express</span></span> for your delivery.</i></b></p>

@endsection