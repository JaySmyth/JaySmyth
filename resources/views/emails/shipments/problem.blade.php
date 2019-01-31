@extends('layouts.mail')

@section('content')

<p>Important information regarding shipment <b>{{$shipment->consignment_number}}</b> with reference <b>{{$shipment->shipment_reference}}</b>:</p>
       
<p>*** {{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}} ***</p>

<p><b>*** {{$problem}} ***</b></p>

<p>To view the full tracking information for this shipment please visit <a href="{{ url('/tracking/' . $shipment->token) }}">{{ url('/tracking/' . $shipment->token) }}</a></p>

@endsection