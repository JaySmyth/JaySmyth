@extends('layouts.mail')

@section('content')

<h1>{{verboseCollectionDelivery($transportJob->type)}} Reinstated - {{$transportJob->number}} @if($transportJob->reference)/ {{$transportJob->reference}}@endif</h1>

<p>Transport Department,</p>

<p>{{verboseCollectionDelivery($transportJob->type)}} Request {{$transportJob->number}} / {{ $transportJob->reference }} has been reinstated (cancellation reversed).</p>
<p>Full details on this request can be <a href="{{ url('/transport-jobs/' . $transportJob->id) }}">viewed here</a>.</p>

@if($transportJob->type == 'c')
<h2>*** {{$transportJob->from_company_name}}, {{$transportJob->from_address1}}, {{$transportJob->from_city}} {{$transportJob->from_postcode}} ***</h2>
@else
<h2>*** {{$transportJob->to_company_name}}, {{$transportJob->to_address1}}, {{$transportJob->to_city}} {{$transportJob->to_postcode}} ***</h2>
@endif

@endsection