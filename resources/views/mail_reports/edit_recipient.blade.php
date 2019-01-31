@extends('layouts.app')

@section('content')

<h2>Edit Recipient: {{$recipient->name}}</h2>



{!! Form::model($recipient, ['method' => 'POST', 'url' => 'mail-reports/' . $mailReport->id . '/edit-recipient/' . $recipient->id, 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

@include('mail_reports.partials.recipient_form', ['submitButtonText' => 'Update Recipient'])

{!! Form::Close() !!}

@endsection