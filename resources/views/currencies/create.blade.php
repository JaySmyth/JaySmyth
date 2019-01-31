@extends('layouts.app')

@section('content')

<h2>Create Currency</h2>

<hr>

{!! Form::Open(['url' => 'currencies', 'class' => '', 'autocomplete' => 'off']) !!}

@include('currencies.partials.form', ['submitButtonText' => 'Create Currency'])

{!! Form::Close() !!}

@endsection