@extends('layouts.app')

@section('content')

<h2>Create User</h2>

<hr>

{!! Form::Open(['id' => 'create-user', 'url' => 'users', 'class' => '', 'autocomplete' => 'off']) !!}

@include('users.partials.form', ['primary_role' => '', 'submitButtonText' => 'Create User'])

{!! Form::Close() !!}


@endsection