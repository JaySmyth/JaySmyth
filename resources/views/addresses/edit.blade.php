@extends('layouts.app')

@section('content')

<h2>Edit <span class="text-capitalize">{{$address->definition}}</span>: {{$address->name}}</h2>

<hr class="mt-1">

{!! Form::model($address, ['method' => 'POST', 'url' => ['addresses', $address->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('addresses.partials.form', ['submitButtonText' => 'Update Address'])

{!! Form::Close() !!}

@endsection