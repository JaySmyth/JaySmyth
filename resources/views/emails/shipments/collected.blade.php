@extends('layouts.mail')

@section('content')

<p>Dear customer,</p>

<p>Your shipment <b>{{$shipment->consignment_number}}</b> with reference <b>{{$shipment->shipment_reference}}</b> has been collected.</p>

<p>*** {{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}} ***</p>

<p>To view the full tracking information for this shipment please visit <a href="{{ url('/tracking/' . $shipment->token) }}">{{ url('/tracking/' . $shipment->token) }}</a></p>

<p>If you require further assistance please contact the {{ $shipment->mode->label }} department.</p>

@endsection