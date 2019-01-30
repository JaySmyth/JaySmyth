@extends('layouts.app')

@section('content')

<h2>Edit Currency: {{$currency->currency}} ({{$currency->code}})</h2>

<hr>

{!! Form::model($currency, ['method' => 'POST', 'url' => ['currencies', $currency->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('currencies.partials.form', ['submitButtonText' => 'Update Currency'])

{!! Form::Close() !!}

@endsection