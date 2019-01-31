@extends('layouts.app')

@section('content')

<h2>{{$company->company_name}}</h2>

<hr class="mt-1">

{!! Form::model($company, ['method' => 'POST', 'url' => ['companies', $company->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('companies.partials.form', ['submitButtonText' => 'Update Company'])

{!! Form::Close() !!}

@endsection