@extends('layouts.app')

@section('content')

<h2>Create Driver</h2>

<hr>

{!! Form::Open(['url' => 'drivers', 'class' => '', 'autocomplete' => 'off']) !!}

@include('drivers.partials.form', ['submitButtonText' => 'Create Driver'])

{!! Form::Close() !!}

@endsection