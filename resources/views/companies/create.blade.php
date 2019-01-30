@extends('layouts.app')

@section('content')

<h2>Create Company</h2>

<hr class="mt-1">

{!! Form::Open(['id' => 'create-company', 'url' => 'companies', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

@include('companies.partials.form', ['submitButtonText' => 'Create Company'])

{!! Form::Close() !!}


@endsection