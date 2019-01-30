@extends('layouts.app')

<!-- Main Content -->
@section('content')

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<form class="form-signin" role="form" method="POST" action="{{ url('/password/email') }}">

    {!! csrf_field() !!}

    <div class="text-center mb-5">
        <img alt="IFS Global Logistics" src="/images/ifs_logo.png">
    </div>

    <h2 class="form-signin-heading">Reset Password</h2>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input  name="email" value="{{ old('email') }}" type="email" class="form-control{{ $errors->has('email') ? ' has-danger' : '' }}" placeholder="Email address" required autofocus>

    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fas fa-btn fa-envelope"></i> Send Password Reset Link</button>

    @if ($errors->has('email'))
    <div class="login-error">
        {{ $errors->first('email') }}
    </div>
    @endif

</form>

@endsection
