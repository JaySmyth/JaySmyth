@extends('layouts.app')

@section('content')

<h2>Edit Driver: {{$driver->name}}</h2>

<hr>

{!! Form::model($driver, ['method' => 'POST', 'url' => ['drivers', $driver->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('drivers.partials.form', ['submitButtonText' => 'Update Driver'])

{!! Form::Close() !!}

@endsection