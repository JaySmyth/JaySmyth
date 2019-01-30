@extends('layouts.app')

@section('content')

<h2>Edit Commodity: {{$commodity->description}}</h2>

<hr class="mt-1">

{!! Form::model($commodity, ['method' => 'POST', 'url' => ['commodities', $commodity->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('commodities.partials.form', ['submitButtonText' => 'Update Commodity'])

{!! Form::Close() !!}

@endsection