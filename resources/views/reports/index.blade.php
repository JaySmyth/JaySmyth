@extends('layouts.app')

@section('content')

<h2>Reports</h2>

<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>Name</th>                
            <th>Description</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        @can($report->permission)
        <tr>
            <td class="text-nowrap"><a href="{{ url('/reports/' . $report->route . '/' . $report->id) }}">{{$report->name}}</a></td>                
            <td>{{$report->description ?? ''}}</td>      
        </tr>
        @endcan
        @endforeach            
    </tbody>
</table>

@include('partials.no_results', ['title' => 'reports', 'results'=> $reports])

@endsection