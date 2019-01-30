@extends('layouts.app')

@section('content')

<h2>Edit Container: {{$container->number}}</h2>

<hr>

{!! Form::model($container, ['method' => 'POST', 'url' => 'sea-freight/' . $shipment->id . '/edit-container/' . $container->id, 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('sea_freight_shipments.partials.container_form', ['submitButtonText' => 'Update Container'])

{!! Form::Close() !!}

@endsection