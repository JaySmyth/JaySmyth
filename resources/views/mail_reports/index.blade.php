@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'mail reports', 'results'=> $mailReports])

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>                
                <th>Enabled</th>                                                                                
                <th class="text-center">Recipients</th>                 
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mailReports as $report)
            <tr>
                <td><a href="{{ url('/mail-reports', $report->id)}}">{{$report->name}}</a></td>                                
                <td>@if($report->enabled)<span class="text-success">Yes</span>@else <span class="text-danger">No</span>@endif</td>
                <td class="text-center">{{$report->recipients->count()}}</td>                
                <td class="text-center">
                    <a href="{{ url('/mail-reports/' . $report->id . '/add-recipient') }}" title="Add Recipient"><span class="far fa-plus-square" aria-hidden="true"></span></a>                              
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('partials.no_results', ['title' => 'mail reports', 'results'=> $mailReports])
@include('partials.pagination', ['results'=> $mailReports])

@endsection