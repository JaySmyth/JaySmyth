@extends('layouts.app')

@section('content')

<h2>Create Additional Charge</h2>

<hr>

{!! Form::Model(['url' => ['surchargedetails', $surcharge->id], 'class' => '', 'autocomplete' => 'off']) !!}

@include('surcharge_details.partials.form', ['submitButtonText' => 'Create Charge'])

{!! Form::Close() !!}

@endsection