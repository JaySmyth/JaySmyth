@extends('layouts.app')

@section('content')

@include('purchase_invoices.partials.title', ['purchaseInvoice'=> $purchaseInvoice, 'small' => 'XML Preview'])

<div class="clearfix"></div>

<div class="card">
    <div class="card-header bg-secondary text-white">
        Multifreight XML Preview
    </div>
    <div class="card-body bg-light">
        <pre>{{htmlentities($xml)}}</pre>
    </div>
</div>


@endsection