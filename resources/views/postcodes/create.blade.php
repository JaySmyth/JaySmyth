@extends('layouts.app')

@section('content')

<h2>Create Postcode</h2>

<hr>

{!! Form::Open(['url' => 'postcodes', 'class' => '', 'autocomplete' => 'off']) !!}

@include('postcodes.partials.form', ['submitButtonText' => 'Create Postcode'])

{!! Form::Close() !!}

@endsection