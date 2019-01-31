@extends('layouts.app')

@section('content')


<form class="form-track" role="form" method="POST" action="{{ url('/track') }}">

    {!! csrf_field() !!}

    <div class="text-center mb-5">
        <img alt="IFS Global Logistics" src="/images/ifs_logo.png">
    </div>

    <h2 class="mb-5"><span class="fas fa-truck fa-lg mr-sm-1" aria-hidden="true"></span> Track your shipment</h2>

    <label for="inputTrackingNumber" class="sr-only">Tracking number</label>
    <input  name="tracking_number" value="{{ old('tracking_number') }}" type="text" class="form-control form-control-lg mb-2" placeholder="Tracking number" required autofocus>


    <button class="btn btn-lg btn-primary btn-block" type="submit">Track <i class="fas fa-btn fa-arrow-right"></i></button>

    @if ($errors->has('tracking_number'))
    <div class="login-error">
        {{ $errors->first('tracking_number') }}
    </div>
    @endif

</form>

@endsection 