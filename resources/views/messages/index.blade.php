@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'messages', 'results'=> $messages, 'create' => 'message'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th class="text-center">Depots</th>
            <th>Valid From</th>
            <th>Valid To</th>
            <th>Type</th>
            <th>Target</th>
            <th class="text-center">Exclusions</th>            
            <th class="text-center">View Count</th>
            <th class="text-center">Status</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($messages as $message)
        <tr>
            <td><a href="{{ url('/messages', $message->id) }}">{{$message->title}}</a></td>
            <td class="text-center">
                @foreach($message->depots as $depot)
                <span class="badge badge-secondary">{{$depot->code}}</span>
                @endforeach
            </td>
            <td>{{$message->valid_from->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td>{{$message->valid_to->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td>
                @if($message->sticky)
                Sticky
                @else
                Display Once
                @endif
            </td>
            <td>
                @if($message->ifs_only)
                IFS Staff Only
                @else
                All Users
                @endif
            </td>
            <td class="text-center"><span class="badge badge-primary">{{$message->companies->count()}}</span></td>            
            <td class="text-center"><span class="badge badge-primary">{{$message->users->count()}}</span></td>
            <td class="text-center">@if($message->enabled)<span class="text-success">Enabled</span>@else<span class="text-danger">Disabled</span>@endif</td>
            <td class="text-center">
                <a href="{{ url('/messages/' . $message->id . '/edit') }}" title="Edit message"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>
                <a href="{{ url('/messages/' . $message->id . '/delete') }}" title="Delete message"><span class="fas fa-times ml-sm-2" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'messages', 'results'=> $messages])
@include('partials.pagination', ['results'=> $messages])

@endsection