@extends('layouts.app')

@section('content')

<h2>{{$mailReport->name}}: Add Recipient</h2>



{!! Form::open(['method' => 'POST', 'url' => 'mail-reports/' . $mailReport->id . '/add-recipient', 'class' => '', 'autocomplete' => 'off']) !!}

@include('mail_reports.partials.recipient_form', ['submitButtonText' => 'Add Recipient'])

{!! Form::Close() !!}


@endsection