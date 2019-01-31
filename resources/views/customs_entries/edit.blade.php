@extends('layouts.app')

@section('content')

<h2>Edit Customs Entry: @if($customsEntry->number){{$customsEntry->number}}@else{{$customsEntry->reference}}@endif</h2>

<hr>

{!! Form::model($customsEntry, ['method' => 'POST', 'url' => ['customs-entries', $customsEntry->id], 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('customs_entries.partials.form', ['submitButtonText' => 'Update Customs Entry'])

{!! Form::Close() !!}

@endsection