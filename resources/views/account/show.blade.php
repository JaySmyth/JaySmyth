@extends('layouts.app')

@section('content')

<h2>
    <span class="fas fa-fw fa-user mr-sm-3" aria-hidden="true"></span> {{$user->name}}
</h2>

<div class="row mb-4">
    <div class="col-sm-7">
        <div class="card h-100">
            <div class="card-header">Account Details</div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-envelope" aria-hidden="true"></span> <strong class="ml-sm-3">Email</strong></div>
                    <div class="col-sm-7"><a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-phone" aria-hidden="true"></span> <strong class="ml-sm-3">Telephone</strong></div>
                    <div class="col-sm-7">{{$user->telephone}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-cog" aria-hidden="true"></span> <strong class="ml-sm-3">Localisation</strong></div>
                    <div class="col-sm-7">{{$user->localisation->time_zone}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-print fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Label Size</strong></div>
                    <div class="col-sm-7">{{$user->printFormat->name ?? 'A4'}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-copy" aria-hidden="true"></span> <strong class="ml-sm-3">Extra Label Copies</strong></div>
                    <div class="col-sm-7">{{$user->label_copies}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-calendar" aria-hidden="true"></span> <strong class="ml-sm-3">Date Created</strong></div>
                    <div class="col-sm-7">{{$user->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-5">
        <div class="card mb-3">
            <div class="card-header">Roles</div>
            <div class="card-body text-large">
                <ul>
                    @foreach ($user->roles as $role)               
                    <span class="badge badge-primary mr-sm-5">{{$role->label}}</span>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">User Agent</div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-edge mr-sm-2" aria-hidden="true"></span> <strong>Browser</strong></div>
                    <div class="col-sm-7">{{$user->browser}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-desktop mr-sm-2" aria-hidden="true"></span> <strong>Platform</strong></div>
                    <div class="col-sm-7">{{$user->platform}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-window-restore mr-sm-2" aria-hidden="true"></span> <strong>Screen Resolution</strong></div>
                    <div class="col-sm-7">{{$user->screen_resolution}}</div>
                </div>
            </div>
        </div>        
    </div>
</div>

<h4 class="mb-2">Companies <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ count($user->companies)}}</span></h4>

@if($user->companies->count() > 0)

<div class="table table-striped-responsive">
    <table class="table table-striped table-bordered mb-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Company</th>
                <th>Address</th>
                <th>Telephone</th>
                <th class="text-center">Depot</th>
                <th class="text-center">Mode</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->companies as $key => $company)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$company->company_name}}</td>
                <td>{{$company->address1}}, {{$company->city}}, {{$company->state}}, {{$company->postcode}}</td>
                <td>{{$company->telephone}}</td>
                <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></td>
                <td class="text-center">@if($company->testing)<span class="text-danger">Testing</span>@else<span class="text-success">Live</span>@endif</td>
                <td class="text-center">@if($company->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif


@endsection