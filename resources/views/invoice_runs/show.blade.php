@extends('layouts.app')

@section('content')

<h2>Invoice Run: {{$invoiceRun->id}}</h2>

@include('invoice_runs.partials.shipments', ['shipments' => $invoiceRun->shipments()->orderBy('sender_company_name','consignment_number')->get()])

@endsection