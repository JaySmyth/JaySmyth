@extends('layouts.app')

@section('content')

<h2>Add Tracking Event - {{$shipment->consignment_number}}</h2>

<hr>

{!! Form::Open(['id' => 'create-tracking', 'url' => 'tracking', 'class' => '', 'autocomplete' => 'off']) !!}
@include('tracking.partials.form', ['submitButtonText' => 'Add Tracking Event'])
{!! Form::Close() !!}

@include('tracking.partials.events', ['shipment' => $shipment])

@endsection 