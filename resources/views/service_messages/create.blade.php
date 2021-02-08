@extends('layouts.app')

@section('content')

<h2>Create Message</h2>

<hr>

{!! Form::Open(['url' => 'service-messages', 'class' => '', 'autocomplete' => 'off']) !!}

@include('service_messages.partials.form', ['submitButtonText' => 'Create Message'])

{!! Form::Close() !!}

@endsection
