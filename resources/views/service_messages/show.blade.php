@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">{{$message->title}} <small class="text-muted">({{$message->valid_from}} to {{$message->valid_to}})</small></h2>

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

        <span class="badge badge-primary">{{$message->service->depot->code}}</span>

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

@endsection
