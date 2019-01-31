@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">Change Status: {{$company->company_name}}</h2>
    <h2 class="float-right">
        @if($company->enabled)
        <span class="badge badge-enabled float-right">Enabled</span> 
        @else
        <span class="badge badge-disabled float-right">Disabled</span>
        @endif
    </h2>
</div>

<hr>

{!! Form::model($company, ['method' => 'POST', 'url' => 'companies/' . $company->id . '/status', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">

    <div class="col-sm-5">

        <div class="form-group row">          
            <label class="col-sm-5  col-form-label">
                Account Status: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::select('enabled', dropDown('enabled'), old('enabled'), array('id' => 'enabled', 'class' => 'form-control')) !!}
            </div>
        </div>
        
        <br>

        <div class="form-group row{{ $errors->has('notes') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Notes: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                <textarea name="notes"  rows="2" maxlength="255" class="form-control">{{old('notes')}}</textarea>

                @if ($errors->has('notes'))
                <span class="form-text">
                    <strong>{{ $errors->first('notes') }}</strong>
                </span>
                @endif

            </div>
        </div>


        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Change Status</button>
            </div>
        </div>
    </div>

    <div class="col-sm-2"></div>
    <div class="col-sm-5">
        <div class="card">
            <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong class="ml-sm-3">Info</strong></div>
            <div class="card-body">
                <p>This allows a company to be placed on hold. All associated users will be unable to log in if the account is set to "disabled".</p>
                <p>Please provide some useful notes when changing the company status.</p>
                <p>An auto email is sent to notify relevant departments of the change either way.</p>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}

@endsection