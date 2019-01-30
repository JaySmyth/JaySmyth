@extends('layouts.mail')

@section('content')

<p>Dear supplier,</p>

<p>Can you please send us copy docs for the following invoices:</p>

<ol>
    @foreach($invoices as $invoice)
        <li>{{$invoice->invoice_number}}</li>
    @endforeach
</ol>

@endsection