@extends('layouts.app')

@section('content')

<h2>Create Import Config</h2>

<hr>

{!! Form::model($importConfig, ['method' => 'POST', 'url' => 'import-configs', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

@include('import_configs.partials.form', ['submitButtonText' => 'Create Configuration'])

{!! Form::Close() !!}

@endsection