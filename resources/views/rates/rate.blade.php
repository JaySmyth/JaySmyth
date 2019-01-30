@extends('layouts.app')

@section('content')

<h2>Create Rate</h2>



{!! Form::model($rate, ['method' => 'POST', 'url' => 'company-service-rate/' . $company->id . '/' . $service->id, 'class' => '', 'autocomplete' => 'off']) !!}

@include('rates.partials.form', ['submitButtonText' => 'Save Rate'])

{!! Form::Close() !!}


@endsection