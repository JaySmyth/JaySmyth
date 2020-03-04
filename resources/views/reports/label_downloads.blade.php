@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="month">Date From</label>
            <input type="text" name="date_from" value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>

        <div class="form-group">
            <label for="month">Date To</label>
            <input type="text" name="date_to" value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => 'Label Downloads', 'results'=> $logs])

        <table id="log-table" class="table table-striped table-sm">
            <thead>
            <tr>
                <th><i class="far fa-file mr-2"></i>Record</th>
                <th><i class="fas fa-user mr-2" aria-hidden="true"></i> User</th>
                <th><i class="far fa-calendar-alt mr-2" aria-hidden="true"></i> Date / Time</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td><a href="{{$log->parent_url}}">{{$log->logable_type}}</a></td>
                    <td>@can('view_user')<a href="{{ url('/users', $log->user->id) }}">{{$log->user->name}}</a> @else {{$log->user->name}}@endcan</td>
                    <td>{{$log->created_at->timezone(Auth::user()->time_zone)->format('d-m-Y H:i:s')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('partials.no_results', ['title' => 'logs', 'results'=> $logs])
        @include('partials.pagination', ['results'=> $logs])
    </main>

</div>

@endsection