@extends('layouts.mail')

@section('content')

<h2>Negative Variances - {{$invoice->carrier->name}} Invoice {{$invoice->invoice_number}}</h2>

<p>Please investigate the following negative variances and action accordingly:</p>

@include('purchase_invoices.partials.compare', ['title' => 'Negative Variances', 'invoice' => $invoice, 'lines' => $lines, 'email' => true])

@endsection