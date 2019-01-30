@extends('layouts.app')

@section('content')

<h2>Edit Message: {{$message->title}}</h2>

<hr>

{!! Form::model($message, ['method' => 'POST', 'url' => ['messages', $message->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('messages.partials.form', ['submitButtonText' => 'Update Message'])

{!! Form::Close() !!}

@endsection