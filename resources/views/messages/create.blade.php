@extends('layouts.app')

@section('content')

<h2>Create Message</h2>

<hr>

{!! Form::Open(['url' => 'messages', 'class' => '', 'autocomplete' => 'off']) !!}

@include('messages.partials.form', ['submitButtonText' => 'Create Message'])

{!! Form::Close() !!}

@endsection