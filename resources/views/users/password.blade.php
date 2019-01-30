@extends('layouts.app')

@section('content')

<h2>Reset Password - {{$user->name}}</h2>

<br>

{!! Form::Open(['url' => 'users/' . $user->id . '/reset-password', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                New Password: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Password('password', ['class' => 'form-control', 'maxlength' => '20']) !!}

                @if ($errors->has('password'))
                <span class="form-text">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Confirm New Password: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Password('password_confirmation', ['class' => 'form-control', 'maxlength' => '20']) !!}

                @if ($errors->has('password_confirmation'))
                <span class="form-text">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">   
            <br>
            <div class="col-sm-5  col-form-label">&nbsp;</div>
            <div class="col-sm-7 checkbox-secondary">
                {!! Form::checkbox('send_email', 1, old('send_email', 1)) !!} <span>Send email to user</span>                
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </div>

    </div>
</div>

{!! Form::Close() !!}


@endsection