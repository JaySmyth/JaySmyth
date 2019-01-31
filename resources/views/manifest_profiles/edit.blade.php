@extends('layouts.app')

@section('content')

<h2>Edit Manifest Profile: {{$manifestProfile->name}}</h2>

<hr>

{!! Form::model($manifestProfile, ['method' => 'POST', 'url' => ['manifest-profiles', $manifestProfile->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('manifest_profiles.partials.form', ['submitButtonText' => 'Update Manifest Profile'])

{!! Form::Close() !!}

@endsection