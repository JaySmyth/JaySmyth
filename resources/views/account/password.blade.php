@extends('layouts.app')

@section('content')

<h2><span class="fas fa-fw fa-key mr-sm-3" aria-hidden="true"></span> Change Password</h2>

<hr>

{!! Form::Open(['url' => '/account/password', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('old_password') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                <span class="bg-info text-white">Old Password:</span> <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Password('old_password', ['class' => 'form-control', 'maxlength' => '20']) !!}                

                @if ($errors->has('old_password'))
                <span class="form-text">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
                @endif

            </div>
        </div>
        
        <br>

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

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </div>

    </div>
</div>

{!! Form::Close() !!}


@endsection