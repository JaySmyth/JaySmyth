@extends('layouts.app')

@section('content')

<form class="form-signin" role="form" method="POST" action="{{ url('/login') }}">
    
    {!! csrf_field() !!}
    
    <input type="hidden" name="screen_resolution" id="screen_resolution" value="">

    <div class="text-center mb-5">
        <img alt="IFS Global Logistics" src="/images/ifs_logo.png">
    </div>

    <h2>Login</h2>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input  name="email" value="{{ old('email') }}" type="email" class="form-control{{ $errors->has('email') ? ' has-danger' : '' }} mb-2" placeholder="Email address" required autofocus>

    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' has-danger' : '' }} mb-3" placeholder="Password" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fas fa-btn fa-sign-in"></i> Login</button>

    <div class="row mt-4">
        
        <div class="col-sm-6">
            <label class="font-weight-normal">
                <input type="checkbox" name="remember" class="mr-sm-1"> Remember me
            </label>             
        </div>
        
        <div class="col-sm-6 text-right">
            <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
    </div>

    @if ($errors->has('email'))
    <div class="login-error">
        {{ $errors->first('email') }}
    </div>
    @endif

    @if ($errors->has('password'))
    <div class="login-error">
        {{ $errors->first('password') }}
    </div>
    @endif

    @if ($errors->has('config'))
    <div class="login-error">
        {{ $errors->first('config') }}
    </div>
    @endif

</form>

@endsection