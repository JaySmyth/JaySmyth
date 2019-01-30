@extends('layouts.app')

@section('content')

<h2>Add Container: {{$shipment->number}} / {{$shipment->reference}} <small>({{$shipment->containers->count() + 1}} of {{$shipment->number_of_containers}})</small></h2>

<hr>

{!! Form::open(['method' => 'POST', 'url' => 'sea-freight/' . $shipment->id . '/add-container', 'class' => '', 'autocomplete' => 'off']) !!}

@include('sea_freight_shipments.partials.container_form', ['submitButtonText' => 'Add Container'])

{!! Form::Close() !!}


@endsection