@extends('layouts.app')

@section('content')

<h2>Edit Commodity: {{$customsEntryCommodity->commodity_code}}</h2>

<hr>

{!! Form::model($customsEntryCommodity, ['method' => 'POST', 'url' => ['customs-entry-commodity', $customsEntryCommodity->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('customs_entry_commodities.partials.form', ['submitButtonText' => 'Update Commodity'])

{!! Form::Close() !!}

@endsection