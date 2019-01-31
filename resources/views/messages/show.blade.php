@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">{{$message->title}} <small class="text-muted">({{$message->valid_from->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}} to {{$message->valid_to->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}})</small></h2>

    <h2 class="float-right">

        @if($message->sticky)
        <span class="badge badge-info">Sticky</span>
        @else
        <span class="badge badge-info">Display Once</span>
        @endif

        @if($message->ifs_only)
        <span class="badge badge-secondary">IFS Staff Only</span>
        @else
        <span class="badge badge-secondary">All Users</span>
        @endif

        @foreach($message->depots as $depot)
        <span class="badge badge-primary">{{$depot->code}}</span>
        @endforeach

        @if($message->enabled)
        <span class="badge badge-success">Enabled</span>  
        @else
        <span class="badge badge-danger">Disabled</span>
        @endif
    </h2>
</div>

<hr>

<h4 class="mb-2">Message Views <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$message->users->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">        
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th> 
            <th>Email</th>
            <th>Companies</th>
            <th>Role</th>                                                                      
        </tr>
    </thead>
    <tbody>
        @foreach($message->users as $user)
        <tr>         
            <td>{{$loop->iteration}}</td>
            <td>@can('view_user')<a href="{{ url('/users', $user->id) }}">{{$user->name}}</a> @else {{$user->name}}@endcan</td>      
            <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>               
            <td>
                @foreach($user->companies as $company)
                {{$company->company_name}}<br>
                @endforeach
            </td>
            <td>{{$user->primary_role_label}}</td>                                                                
        </tr>
        @endforeach
    </tbody>
</table>  


<h4 class="mb-2">Company Exclusions <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $message->companies->count() }}</span></h4>

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

        </tr>
    </thead>
    <tbody>            
        @foreach ($message->companies as $key => $company)
        <tr>        
            <td>{{$key + 1}}</td>
            <td>@can('view_company')<a href="{{ url('/companies', $company->id) }}">@endcan{{$company->company_name}}@can('show_company')</a>@endcan</td>      
            <td>{{$company->address1}}, {{$company->city}}, {{$company->state}}, {{$company->postcode}}</td>                               
            <td>{{$company->telephone}}</td>
            <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></td>
            <td class="text-center">{{$company->users->count()}}</td>                
            <td class="text-center">@if($company->testing)<span class="text-danger">Testing</span>@else<span class="text-success">Live</span>@endif</td>          
        </tr>
        @endforeach
    </tbody>
</table>

@endsection