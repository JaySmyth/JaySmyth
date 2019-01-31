@extends('layouts.app')

@section('content')

<h2>Edit User: {{$user->name}}</h2>

<hr>

{!! Form::model($user, ['method' => 'POST', 'url' => ['users', $user->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

{!! Form::hidden('id', $user->id) !!}

@include('users.partials.form', ['primary_role' => $user->primary_role, 'submitButtonText' => 'Update User'])

{!! Form::Close() !!}

@endsection