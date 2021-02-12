@extends('layouts.app')

@section('content')

<h2>Edit Message: {{$message->title}}</h2>

<hr>

{!! Form::model($message, ['method' => 'POST', 'url' => ['service-messages', $message->id], 'class' => '', 'autocomplete' => 'off']) !!}
{{ method_field('PATCH') }}

@include('service_messages.partials.form', ['submitButtonText' => 'Update Message'])

{!! Form::Close() !!}

@endsection
