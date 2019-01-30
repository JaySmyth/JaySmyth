@extends('layouts.app')

@section('content')

<h2>Create Customs Entry</h2>

<hr>

{!! Form::Open(['id' => 'create-customs-entry', 'url' => 'customs-entries', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

@include('customs_entries.partials.form', ['primary_role' => '', 'submitButtonText' => 'Create Customs Entry'])

{!! Form::Close() !!}


@endsection