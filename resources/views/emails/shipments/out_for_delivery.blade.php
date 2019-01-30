@extends('layouts.mail')

@section('content')

<p>Dear Recipient,</p>

<p>Your shipment <b>{{$shipment->consignment_number}}</b> with reference <b>{{$shipment->shipment_reference}}</b> from {{$shipment->company->company_name}} is currently out for delivery.</p>

<p>*** {{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}} ***</p>

<p>To view the full tracking information for this shipment please visit <a href="{{ url('/tracking/' . $shipment->token) }}">{{ url('/tracking/' . $shipment->token) }}</a></p>

@endsection