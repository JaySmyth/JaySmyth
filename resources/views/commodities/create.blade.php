@extends('layouts.app')

@section('content')

<h2>Create Commodity</h2>

<hr class="mt-1">

{!! Form::Open(['id' => 'create-commodity', 'url' => 'commodities', 'class' => '', 'autocomplete' => 'off']) !!}

@include('commodities.partials.form', ['submitButtonText' => 'Create Commodity'])

{!! Form::Close() !!}


@endsection