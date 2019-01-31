@extends('layouts.app')

@section('content')

<h2>Edit Rate Surcharge</h2>

<hr>

{!! Form::model($rateSurcharge, ['method' => 'POST', 'url' => ['rate-surcharges', $rateSurcharge->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('rate_surcharges.partials.form', ['submitButtonText' => 'Update Rate Surcharge'])

{!! Form::Close() !!}

@endsection