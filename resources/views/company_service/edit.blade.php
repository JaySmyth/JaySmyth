@extends('layouts.app')

@section('content')

<h2>Company Service filter</h2>

<hr class="mt-1">

{!! Form::model($companyService, ['method' => 'POST', 'url' => ['company-services', $companyService->company_id, $companyService->service_id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('company_service.partials.form', ['submitButtonText' => 'Update Company Service'])

{!! Form::Close() !!}

@endsection
