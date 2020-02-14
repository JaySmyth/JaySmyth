@extends('layouts.app')

@section('navSearchPlaceholder', 'User search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Name or Email Address:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Company:
    </label>
    <div class="col-sm-8">
        {!! Form::select('company', dropDown('sites', 'All Companies'), Request::get('company'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Role:
    </label>
    <div class="col-sm-8">
        {!! Form::select('role', dropDown('roles', 'All Roles'), Request::get('role'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Status:
    </label>
    <div class="col-sm-8">
        {!! Form::select('enabled', ['' => 'All Statuses', '1' => 'Enabled', '0' => 'Disabled' ], Request::get('enabled'), array('class' => 'form-control')) !!}
    </div>   
</div>    

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'users', 'results'=> $users, 'create' => 'user'])

@include('.users.partials.users', ['users' => $users, 'class' => 'hover', 'iteration' => false])

@include('partials.no_results', ['title' => 'users', 'results'=> $users])

@include('partials.pagination', ['results'=> $users])

@endsection