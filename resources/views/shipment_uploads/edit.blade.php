@extends('layouts.app')

@section('content')

<h2>Edit Shipment Upload (SFTP): {{$shipmentUpload->reference}}</h2>

<hr>

{!! Form::model($shipmentUpload, ['method' => 'POST', 'url' => ['shipment-uploads', $shipmentUpload->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('shipment_uploads.partials.form', ['submitButtonText' => 'Update Shipment Upload'])

{!! Form::Close() !!}

@endsection