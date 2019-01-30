@extends('layouts.app')

@section('content')

<h2>Add Company - {{$user->name}}</h2>

<hr>

{!! Form::model($user, ['method' => 'POST', 'url' => 'users/' . $user->id . '/add-company', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">
    <div class="col-sm-5">       
        <div class="form-group row">          
            <label class="col-sm-3  col-form-label">Company:</label>
            <div class="col-sm-9">
                {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), '', array('id' => 'company_id', 'class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group row buttons-main">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-9">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Company</button>
            </div>
        </div>
    </div>
</div>

{!! Form::Close() !!}


@if($user->companies->count() > 0)

<h4 class="mb-2">Companies <span class="badge badge-pill badge-secondary">{{ count($user->companies)}}</span></h4>

<div class="table table-striped-responsive">
    <table class="table table-striped table-bordered mb-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Company</th> 
                <th>Address</th>                                                               
                <th>Telephone</th>
                <th class="text-center">Depot</th>
                <th class="text-center">Users</th>                                                
                <th class="text-center">Mode</th>
                <th class="text-center">Status</th> 
            </tr>
        </thead>
        <tbody>            
            @foreach ($user->companies as $key => $company)
            <tr>        
                <td>{{$key + 1}}</td>
                <td><a href="{{ url('/companies', $company->id) }}">{{$company->company_name}}</a></td>      
                <td>{{$company->address1}}, {{$company->city}}, {{$company->state}}, {{$company->postcode}}</td>                               
                <td>{{$company->telephone}}</td>
                <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></td>
                <td class="text-center">{{$company->users->count()}}</td>                
                <td class="text-center">@if($company->testing)<span class="text-danger">Testing</span>@else<span class="text-success">Live</span>@endif</td>
                <td class="text-center">@if($company->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

@endsection