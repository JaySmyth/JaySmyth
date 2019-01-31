@extends('layouts.app')

@section('content')

<h2>Create Sea Freight Shipment</h2>

<hr>

{!! Form::Open(['id' => 'create-sea-freight-shipment', 'url' => 'sea-freight', 'class' => '', 'autocomplete' => 'off']) !!}

@include('sea_freight_shipments.partials.form', ['primary_role' => '', 'submitButtonText' => 'Create Shipment'])

{!! Form::Close() !!}


@endsection