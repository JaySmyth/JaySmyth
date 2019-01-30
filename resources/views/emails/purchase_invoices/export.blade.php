@extends('layouts.mail')

@section('content')

<h2>Purchase Invoice Export</h2>

<p>The following invoices have been exported. See attached XML.</p>

<ol>
@foreach($invoices as $invoice)
<li>{{$invoice->carrier->name}} - {{$invoice->invoice_number}}</li>
@endforeach
</ol>

@if(count($files) <= 0)
<p class="error">Error: no XML files were generated.</p>
@endif

@endsection