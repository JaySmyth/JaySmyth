@extends('layouts.mail')

@section('content')

<h2>Shipment not scanned by IFS ({{$shipment->carrier->name}} - {{$shipment->carrier_consignment_number}})</h2>

<p>{{ $shipment->mode->label }} Shipment <b>{{$shipment->consignment_number}}</b> has been marked as received and scanned from a carrier scan.</p>

<p>This means that the shipment was not scanned by IFS and may need added to a manifest.</p>

<p>*** {{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}} {{$shipment->recipient_country_code}} ***</p>

@endsection