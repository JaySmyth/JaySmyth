@extends('layouts.app')

@section('content')

<h2>Create Quotation</h2>

<hr>

{!! Form::Open(['url' => 'quotations', 'class' => 'form-compact']) !!}

@include('quotations.partials.form', ['submitButtonText' => 'Create Quotation'])

{!! Form::Close() !!}

@endsection