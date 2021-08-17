@extends('layouts.app')

@section('content')

<h2>Create Service Record</h2>

<hr>

{!! Form::Open(['url' => 'services', 'class' => 'form-compact']) !!}

@include('services.partials.form', ['submitButtonText' => 'Create Service Record'])

{!! Form::Close() !!}

@endsection
