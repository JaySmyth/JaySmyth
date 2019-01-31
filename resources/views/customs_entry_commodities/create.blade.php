@extends('layouts.app')

@section('content')

<h2>Add Commodity: {{$customsEntry->reference}}</h2>

<hr>

{!! Form::open(['method' => 'POST', 'url' => 'customs-entries/' . $customsEntry->id . '/add-commodity', 'class' => '']) !!}

@include('customs_entry_commodities.partials.form', ['submitButtonText' => 'Add Commodity'])

{!! Form::Close() !!}

@include('customs_entry_commodities.partials.commodities', ['customsEntry' => $customsEntry])

@endsection