@extends('layouts.app')

@section('content')

<h2>Edit Tracking Event - {{$shipment->consignment_number}}</h2>

<hr>

{!! Form::model($tracking, ['method' => 'POST', 'url' => ['tracking', $tracking->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('tracking.partials.form', ['submitButtonText' => 'Update Tracking Event'])

{!! Form::Close() !!}

<br>

@include('tracking.partials.events', ['shipment' => $shipment])

@endsection 