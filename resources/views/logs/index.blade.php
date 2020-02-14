@extends('layouts.app')

@section('navSearchPlaceholder', 'Log search - try "transport"...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Record Type:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date From:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_from" value="{{Request::get('date_from')}}" class="form-control datepicker" placeholder="Date from">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date To:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_to" value="{{Request::get('date_to')}}" class="form-control datepicker" placeholder="Date To">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Information:
    </label>
    <div class="col-sm-8">
        <input type="text" name="information" id="information" value="{{Request::get('information')}}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Comments:
    </label>
    <div class="col-sm-8">
        <input type="text" name="comments" id="comments" value="{{Request::get('comments')}}" class="form-control">
    </div>
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'logs', 'results'=> $logs])


<table id="log-table" class="table table-striped table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th><i class="far fa-calendar-alt mr-2" aria-hidden="true"></i> Date / Time</th>
            <th><i class="far fa-file mr-2"></i>Record</th>
            <th><i class="fas fa-info-circle mr-2"></i> Information</th>
            <th class="text-center"><i class="fas fa-table mr-2"></i> Data</th>
            <th><i class="far fa-comments mr-2"></i> Comments</th>
            <th><i class="fas fa-user mr-2" aria-hidden="true"></i> User</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>            
            <td>{{$log->created_at->timezone(Auth::user()->time_zone)->format('d-m-Y H:i:s')}}</td>
            <td><a href="{{$log->parent_url}}">{{$log->logable_type}}</a></td>
            <td>{{$log->information}}</td>
            <td class="text-center">
                @if($log->data)
                <a href="{{ url('/logs/' . $log->id . '/get-data') }}" class="btn btn-outline-primary btn-sm ml-3 get-log-data" role="button">See data</a>
                @else
                <div class="text-center"><i class="fas fa-ellipsis-h faded"></i></div>
                @endif
            </td>
            <td>
                @if($log->comments)
                {{$log->comments}}
                @else
                <div class="text-center"><i class="fas fa-ellipsis-h faded"></i></div>
                @endif
            </td>
            <td>@can('view_user')<a href="{{ url('/users', $log->user->id) }}">{{$log->user->name}}</a> @else {{$log->user->name}}@endcan</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="log-data" tabindex="-1" role="dialog" aria-labelledby="logChanges" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 id="logChanges" class="mb-0"><i class="fas fa-table"></i> Data</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="log-data-body"></div>
        </div>
    </div>
</div>

@include('partials.no_results', ['title' => 'logs', 'results'=> $logs])
@include('partials.pagination', ['results'=> $logs])

@endsection