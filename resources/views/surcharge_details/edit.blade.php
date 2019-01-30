@extends('layouts.app')

@section('content')

<h2>Edit Surcharge: {{$surcharge->name}} - {{$surcharge->code}} </h2>

<hr>

{!! Form::model($surcharge, ['method' => 'POST', 'url' => ['surchargedetails', $surcharge->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('surcharge_details.partials.form', ['submitButtonText' => 'Update Surcharge'])

{!! Form::Close() !!}

@endsection