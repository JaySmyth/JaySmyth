@extends('layouts.app')

@section('content')

<h2><span class="fas fa-fw fa-cog mr-sm-3" aria-hidden="true"></span> Account Settings</h2>

<hr>

{!! Form::model($user, ['method' => 'POST', 'url' => ['account/settings'], 'class' => '', 'autocomplete' => 'off']) !!}

{{ method_field('PATCH') }}

<div class="row">
    <div class="col-sm-5">

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Email: 
            </label>

            <div class="col-sm-7">
                {!! Form::Text('email', old('email'), ['id' => 'email', 'class' => 'form-control', 'maxlength' => '150']) !!}

                @if ($errors->has('email'))
                <span class="form-text">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row{{ $errors->has('telephone') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Telephone: 
            </label>

            <div class="col-sm-7">
                {!! Form::Text('telephone', old('telephone'), ['id' => 'telephone', 'class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('telephone'))
                <span class="form-text">
                    <strong>{{ $errors->first('telephone') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row">          
            <label class="col-sm-5  col-form-label">
                Label Size:
            </label>

            <div class="col-sm-7">
                {!! Form::select('print_format_id', dropDown('printFormats'), old('print_format_id'), array('id' => 'print_format_id', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row">          
            <label class="col-sm-5  col-form-label">
                Customer Label Required:
            </label>

            <div class="col-sm-7">
                {!! Form::select('customer_label', dropDown('boolean'), old('customer_label'), array('id' => 'customer_label', 'class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Account</button>
            </div>
        </div>

    </div>
</div>

{!! Form::Close() !!}


@endsection