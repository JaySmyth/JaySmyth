@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'manifesting', 'results'=> $manifestProfiles])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>                                
            <th>Carrier</th>                                                                                 
            <th>Route</th>                
            <th>Auto</th>
            <th>Run Time</th>
            <th>Last Run</th>                 
            @if(Auth::user()->hasRole('ifsa'))<th>&nbsp;</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach($manifestProfiles as $profile)
        <tr>
            <td><a href="{{ url('/manifest-profiles/run?id=' .  $profile->id) }}">{{$profile->name}}</a></td>                                
            <td>{{$profile->carrier->name ?? 'Not defined'}}</td>                                                
            <td>{{$profile->route->name ?? 'Not defined'}}</td>                
            <td>@if($profile->auto)<span class="text-success">Yes</span>@else No @endif</td>
            <td>@if($profile->auto) {{$profile->time}} @else n/a @endif</td>
            <td>{{$profile->getLastRunTime(Auth::user()->time_zone, Auth::user()->date_format)}}</td>                   

            @if(Auth::user()->hasRole('ifsa'))
            <td class="text-center">
                <a href="{{ url('/manifest-profiles/' . $profile->id . '/edit') }}" title="Edit Profile Settings"><span class="fas fa-cog" aria-hidden="true"></span></a>                              
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'manifestProfiles', 'results'=> $manifestProfiles])
@include('partials.pagination', ['results'=> $manifestProfiles])

@endsection