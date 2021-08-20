@extends('layouts.app')

@section('content')

<h2>Edit Service: {{$service->carrier->name}} ({{$service->code}})</h2>

<hr>

{!! Form::model($service, ['method' => 'POST', 'url' => ['services', $service->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('services.partials.form', ['submitButtonText' => 'Update Service'])

{!! Form::Close() !!}

@endsection
