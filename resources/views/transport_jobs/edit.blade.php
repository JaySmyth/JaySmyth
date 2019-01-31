@extends('layouts.app')

@section('content')

<h2>Edit {{verboseCollectionDelivery($transportJob->type)}} Request <small>{{$transportJob->number}}</small></h2>

{!! Form::model($transportJob, ['method' => 'POST', 'url' => ['transport-jobs', $transportJob->id], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('transport_jobs.partials.form', ['submitButtonText' => 'Update ' . ucwords($type) . ' Request', 'type' => $type, 'address' => $address])

{!! Form::Close() !!}

@endsection