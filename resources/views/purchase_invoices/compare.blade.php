@extends('layouts.app')

@section('content')

@include('purchase_invoices.partials.title', ['purchaseInvoice'=> $purchaseInvoice, 'small' => 'Cost Comparison'])

@if(count($costComparisions['negative']) > 0)
@include('purchase_invoices.partials.compare', ['title' => 'Negative Variances', 'invoice' => $purchaseInvoice, 'lines' => $costComparisions['negative']])
@endif

@if(count($costComparisions['positive']) > 0)
@include('purchase_invoices.partials.compare', ['title' => 'Neutral / Positive Variances', 'invoice' => $purchaseInvoice, 'lines' => $costComparisions['positive']])
@endif

@endsection