@extends('layouts.app')

@section('content')

<h2>Create Shipment Upload</h2>

<hr>

{!! Form::Open(['url' => 'shipment-uploads', 'class' => 'form-compact']) !!}

@include('shipment_uploads.partials.form', ['submitButtonText' => 'Create Upload'])

{!! Form::Close() !!}

@endsection