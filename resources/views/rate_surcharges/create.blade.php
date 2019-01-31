@extends('layouts.app')

@section('content')

<h2>Create Fuel Surcharge</h2>

<hr>

{!! Form::Open(['url' => 'fuel-surcharges', 'class' => '', 'autocomplete' => 'off']) !!}

@include('fuel_surcharges.partials.form', ['submitButtonText' => 'Create Fuel Surcharge'])

{!! Form::Close() !!}


@endsection