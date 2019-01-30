@extends('layouts.app')

@section('content')

<h2>Edit Quotation: {{$quotation->reference}}</h2>

<hr>

{!! Form::model($quotation, ['method' => 'POST', 'url' => ['quotations', $quotation->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('quotations.partials.form', ['submitButtonText' => 'Update Quotation'])

{!! Form::Close() !!}

@endsection