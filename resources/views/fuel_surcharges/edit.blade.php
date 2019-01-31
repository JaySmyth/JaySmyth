@extends('layouts.app')

@section('content')

<h2>Edit Fuel Surcharge</h2>

<hr>

{!! Form::model($fuelSurcharge, ['method' => 'POST', 'url' => ['fuel-surcharges', $fuelSurcharge->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('fuel_surcharges.partials.form', ['submitButtonText' => 'Update Fuel Surcharge'])

{!! Form::Close() !!}

@endsection