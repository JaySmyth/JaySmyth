@extends('layouts.app')

@section('content')

<h2>Edit Shipment: {{$shipment->number}} / {{$shipment->reference}}</h2>

<hr>

{!! Form::model($shipment, ['method' => 'POST', 'url' => ['sea-freight', $shipment->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('sea_freight_shipments.partials.form', ['submitButtonText' => 'Update Shipment'])

{!! Form::Close() !!}

@endsection