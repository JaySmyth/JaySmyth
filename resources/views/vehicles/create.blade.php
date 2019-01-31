@extends('layouts.app')

@section('content')

<h2>Create Vehicle</h2>



{!! Form::Open(['url' => 'vehicles', 'class' => '', 'autocomplete' => 'off']) !!}

@include('vehicles.partials.form', ['submitButtonText' => 'Create Vehicle'])

{!! Form::Close() !!}

@endsection