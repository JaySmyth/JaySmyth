@extends('layouts.app')

@section('content')

<h2>Edit Postcode: <span class="badge badge-secondary">{{$postcode->postcode}}</span> <span class="ml-3 text-muted">{{getCountry($postcode->country_code)}}</span></h2>

<hr>

{!! Form::model($postcode, ['method' => 'POST', 'url' => ['postcodes', $postcode->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('postcodes.partials.form', ['submitButtonText' => 'Update Postcode'])

{!! Form::Close() !!}

@endsection