@extends('layouts.app')

@section('content')

<h2>Edit Vehicle: {{$vehicle->registration}}</h2>



{!! Form::model($vehicle, ['method' => 'POST', 'url' => ['vehicles', $vehicle->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('vehicles.partials.form', ['submitButtonText' => 'Update Vehicle'])

{!! Form::Close() !!}

@endsection