@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'vehicles', 'results'=> $vehicles, 'create' => 'vehicle'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Registration</th>
            <th>Type</th>                                        
            <th class="text-center">Depot</th>                
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $vehicle)
        <tr>
            <td><a href="{{ url('/vehicles/' . $vehicle->id . '/edit') }}">{{$vehicle->registration}}</a></td>
            <td>{{$vehicle->type}}</td>
            <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$vehicle->depot->name}}">{{$vehicle->depot->code}}</span></td>
            <td class="text-center">@if($vehicle->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>      
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'vehicles', 'results'=> $vehicles])
@include('partials.pagination', ['results'=> $vehicles])

@endsection