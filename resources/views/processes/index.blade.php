@extends('layouts.app')

@section('content')

<div class="row justify-content-between mt-2">
    <div class="col">
        <h3>Process Monitor <small><span id="process-count" class="badge badge-primary ml-2"></span></small></h3>        
    </div>
    <div class="col text-right"></div>
</div>

<table class="table table-striped mt-1">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Process</th>            
        </tr>
    </thead>
    <tbody id="running-processes"></tbody>
</table>

@endsection