@extends('layouts.app')

@section('content')

<h2><span class="fas fa-truck fa-lg mr-sm-1" aria-hidden="true"></span> Track a Shipment</h2>
<hr class="mb-5">

<h4 class="mb-4">Need the status of your shipment or a proof of delivery? Enter your <u>tracking number</u> below.</h4>

<form class="form-inline" role="form" method="POST" action="{{ url('/track') }}">
    {!! csrf_field() !!}
    <input type="text"  name="tracking_number" value="{{ old('tracking_number') }}" class="form-control form-control-lg{{ $errors->has('tracking_number') ? ' has-danger' : '' }} mr-sm-1"  placeholder="Tracking number" required autofocus>
    <button class="btn btn-lg btn-primary" type="submit">Track <span class="fas fa-arrow-right" aria-hidden="true"></span></button>
</form>

@if ($errors->has('tracking_number'))
<h5 class="text-danger mt-4">{{ $errors->first('tracking_number') }}</h5>    
@endif

@endsection 