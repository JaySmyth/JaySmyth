@extends('layouts.app')

@section('content')

<div class="row justify-content-between mt-2">
    <div class="col">
        <h3>Job Queue <small><span id="job-count" class="badge badge-primary ml-2"></span><span id="job-queue-last-updated" class="badge badge-dark ml-4 text-small shadow"></span></small></h3>        
    </div>
    <div class="col text-right">
        
    </div>
</div>



<table class="table table-striped mt-1">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Queue</th>
            <th scope="col">Job</th>            
            <th scope="col" class="text-center">Attempts</th>
            <th scope="col" class="text-center">Created At</th>
            <th scope="col" class="text-center">Started At</th>
            <th scope="col" class="text-right">Duration</th>
        </tr>
    </thead>
    <tbody id="job-queue"></tbody>
</table>


<div id="failed">

    <div class="row justify-content-between mt-5">
        <div class="col">
            <h3>Failed Jobs <small><span id="failed-job-count" class="badge badge-danger ml-2"></span><span id="failed-jobs-last-updated" class="badge badge-dark ml-4 text-small shadow"></span></small></h3>
        </div>
        <div class="col text-right">
            <button id="retry-all" type="button" class="btn btn-primary" role="button"><i class="fas fa-redo mr-2"></i> Retry All</button>
        </div>
    </div>

    <table class="table table-striped mt-1">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Queue</th>
                <th scope="col">Job</th>
                <th scope="col">Exception</th>
                <th scope="col" class="text-center">Failed At</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody id="failed-jobs"></tbody>
    </table>
</div>


@endsection