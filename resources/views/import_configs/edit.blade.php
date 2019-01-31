@extends('layouts.app')

@section('content')

<h2>Edit Import Config: {{$importConfig->company_name}}</h2>

<hr>

{!! Form::model($importConfig, ['method' => 'POST', 'url' => ['import-configs', $importConfig->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('import_configs.partials.form', ['submitButtonText' => 'Update Import Config'])

{!! Form::Close() !!}

@endsection