@extends('layouts.app')

@section('content')

<h2>{{$report->name}}</h2>

<table class="table table-striped table-bordered table-sm mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th> 
            <th>Email</th> 
            <th>Last Login</th>
            <th>Browser</th>                            
            <th>Platform</th>
            <th>Screen Resolution</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>@can('view_user')<a href="{{ url('/users', $user->id) }}">{{$user->name}}</a> @else {{$user->name}}@endcan</td>      
            <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>  
            <td>{{$user->getLastLogin()}}</td>
            <td>
                {{$user->browser}}
                @if(stristr($user->browser, 'IE '))
                <span class="fas fa-exclamation-triangle text-warning ml-sm-2" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Dated browser detected"></span>                          
                @endif
            </td>                      
            <td>{{$user->platform}}</td>                      
            <td>{{$user->screen_resolution}}</td>  
            <td class="text-center">
                @can('courier')<a href="{{url('/shipments?user=' . $user->id)}}" title="Shipment History"><span class="fas fa-history" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection