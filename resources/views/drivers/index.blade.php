@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'drivers', 'results'=> $drivers, 'create' => 'driver'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Driver</th>                                                      
            <th>Telephone</th>
            <th>Default Vehicle</th>
            <th class="text-center">Open Manifests</th>
            <th class="text-center">Depot</th>             
            <th class="text-center">Status</th>  
        </tr>
    </thead>
    <tbody>
        @foreach($drivers as $driver)
        <tr>
            <td><a href="{{ url('/drivers/' . $driver->id . '/edit') }}">{{$driver->name}}</a></td>            
            <td>{{$driver->telephone}}</td>
            <td>
                @if($driver->vehicle_id)
                    {{$driver->vehicle->registration}} - {{$driver->vehicle->type}}
                @else
                    Not defined
                @endif                
            </td>
            <td class="text-center">{{$driver->open_manifest_count}}</td>
            <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$driver->depot->name}}">{{$driver->depot->code}}</span></td>
            <td class="text-center">@if($driver->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>      
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'drivers', 'results'=> $drivers])
@include('partials.pagination', ['results'=> $drivers])

@endsection