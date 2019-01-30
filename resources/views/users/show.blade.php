@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left"><span class="fas fa-fw fa-user mr-sm-3" aria-hidden="true"></span> {{$user->name}}</h2>
    <h2 class="float-right">
        @if(!$user->enabled)                
        <span class="badge badge-disabled float-right">Disabled</span>
        @elseif(!$user->isConfigured())
        <span class="badge badge-warning float-right">Not Configured</span>
        @endif
    </h2>
</div>


<div class="row mb-4">
    <div class="col-sm-7">
        <div class="card h-100">
            <div class="card-header">Account Details</div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-envelope mr-sm-2" aria-hidden="true"></span> <strong>Email</strong></div>
                    <div class="col-sm-7"> <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-phone mr-sm-2" aria-hidden="true"></span> <strong>Telephone</strong></div>
                    <div class="col-sm-7">{{$user->telephone}}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-cog mr-sm-2" aria-hidden="true"></span> <strong>Localisation</strong></div>
                    <div class="col-sm-7">{{$user->localisation->time_zone}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-print fa-fw mr-sm-2" aria-hidden="true"></span> <strong>Label Size</strong></div>
                    <div class="col-sm-7">{{$user->printFormat->name ?? 'A4'}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-copy mr-sm-2" aria-hidden="true"></span> <strong>Extra Label Copies</strong></div>
                    <div class="col-sm-7">{{$user->label_copies}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-calendar mr-sm-2" aria-hidden="true"></span> <strong>Date Created</strong></div>
                    <div class="col-sm-7">{{$user->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-truncate"><span class="fas fa-fw fa-calendar mr-sm-2" aria-hidden="true"></span> <strong>Last Login</strong></div>
                    <div class="col-sm-7">
                        @if($user->last_login)
                        {{$user->last_login->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                        @else
                        Inactive
                        @endif
                    </div>
                </div>
            </div>
        </div>        
    </div>

    <div class="col-sm-5">
        <div class="card mb-3">
            <div class="card-header">Roles

                <div class="dropdown float-right">
                    <button class="btn btn-outline-secondary btn-sm btn-xs dropdown-toggle ml-sm-3 btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                        @can('reset_password')
                        <a class="dropdown-item" href="{{ url('/users/' . $user->id . '/reset-password') }}" title="Reset Password"><span class="fas fa-key mr-sm-2" aria-hidden="true"></span> Reset Password</a>
                        @endcan

                        @can('update_user')
                        <a class="dropdown-item" href="{{ url('/users/' . $user->id . '/edit') }}" title="Edit User"><span class="fas fa-edit mr-sm-2" aria-hidden="true"></span> Edit User</a>
                        @endcan     

                        @can('add_company')
                        <a class="dropdown-item" href="{{ url('/users/' . $user->id . '/add-company') }}" title="Add Company"><span class="fas fa-plus-circle mr-sm-2" aria-hidden="true"></span> Add Company</a>
                        @endcan

                        @can('courier')
                        <a class="dropdown-item" href="{{url('/shipments?user=' . $user->id)}}" title="View Shipping History"><span class="fas fa-history mr-sm-2" aria-hidden="true"></span> Shipment History</a>
                        @endcan

                    </div>
                </div>

            </div>
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
            @can('remove_company')<th></th>@endcan
        </tr>
    </thead>
    <tbody>            
        @foreach ($user->companies as $key => $company)
        <tr>        
            <td>{{$key + 1}}</td>
            <td>@can('view_company')<a href="{{ url('/companies', $company->id) }}">@endcan{{$company->company_name}}@can('show_company')</a>@endcan</td>      
            <td>{{$company->address1}}, {{$company->city}}, {{$company->state}}, {{$company->postcode}}</td>                               
            <td>{{$company->telephone}}</td>
            <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></td>
            <td class="text-center">{{$company->users->count()}}</td>                
            <td class="text-center">@if($company->testing)<span class="text-danger">Testing</span>@else<span class="text-success">Live</span>@endif</td>
            <td class="text-center">@if($company->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>                
            @can('remove_company')<td class="text-center"><a href="{{ url('/users/' . $user->id . '/remove-company/' . $company->id) }}" title="Remove Company" class="delete-company"><span class="fas fa-fw fa-times" aria-hidden="true"></span></a></td>@endcan
        </tr>
        @endforeach
    </tbody>
</table>

@else
<h4 class="mb-2">Companies <span class="badge badge-danger">0</span></h4>
@endif

@endsection