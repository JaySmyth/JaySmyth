@extends('layouts.app')

@section('content')

<h2>Create {{ucwords(Request::get('definition'))}} Address</h2>

<hr class="mt-1">

{!! Form::Open(['id' => 'create-address', 'url' => 'addresses', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

@include('addresses.partials.form', ['submitButtonText' => 'Create Address'])

{!! Form::Close() !!}

@endsection