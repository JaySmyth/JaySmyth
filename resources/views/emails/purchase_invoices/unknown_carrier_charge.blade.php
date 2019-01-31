@extends('layouts.mail')

@section('content')

<p>We have received an unknown charge type of <strong>{{$carrierChargeCode->code}}</strong>.</p>

<p>This was identified on {{$invoice->carrier->name}} invoice <strong>{{$invoice->invoice_number}}</strong>.</p>

<p>A record has been created within the charge codes table. It needs the description updated and the correct SCS code applied (defaulted to MIS).</p>

<p>Please select the Carrier Charge Code option from the Accounts Menu and update the SCS code and description accordingly.</p>

@endsection