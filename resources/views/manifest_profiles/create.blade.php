@extends('layouts.app')

@section('content')

<h2>Create Manifest Profile</h2>

<hr>

{!! Form::Open(['id' => 'create-manifest-profile', 'url' => 'manifest-profiles', 'class' => '', 'autocomplete' => 'off']) !!}

@include('manifest_profiles.partials.form', ['submitButtonText' => 'Create Manifest Profile'])

{!! Form::Close() !!}


@endsection