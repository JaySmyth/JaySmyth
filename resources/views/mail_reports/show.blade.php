@extends('layouts.app')

@section('content')

<h2>Mail Report - {{$mailReport->name}}</h2>

<h4 class="mb-2">Recipients
    <span class="badge badge-secondary">{{$mailReport->recipients->count()}}</span> 
    <a href="{{ url('/mail-reports/' . $mailReport->id . '/add-recipient') }}" class="btn btn-secondary btn-sm ml-sm-4" title="Add Recipient"  role="button">Add</a>
</h4>


<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>To.</th>
            <th>Bcc.</th>            
            <th>Criteria</th>
            <th class="text-center">Format</th>
            <th class="text-center">Enabled</th>
            <th class="text-center">Frequency</th>
            <th class="text-center">Time</th>
            <th class="text-center">Last Run</th>
            <th class="text-center">Next Run</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mailReport->recipients as $recipient)
        <tr>
            <td class="align-middle">{{$loop->iteration}}</td>
            <td class="align-middle">{{$recipient->name}}</td>
            <td class="align-middle">
                @if(is_array($recipient->to_array))                
                @foreach($recipient->to_array as $to)                
                <a href="mailto:{{$to}}">{{$to}}</a><br>
                @endforeach                    
                @else
                <a href="mailto:{{$recipient->to}}">{{$recipient->to}}</a>
                @endif                
            </td>
            <td class="align-middle">
                @if(is_array($recipient->bcc_array))                
                @foreach($recipient->bcc_array as $bcc)
                <a href="mailto:{{$bcc}}">{{$bcc}}</a><br>
                @endforeach                    
                @else
                @if($recipient->bcc)                
                <a href="mailto:{{$recipient->bcc}}">{{$recipient->bcc}}</a>                
                @else
                <i>null</i>
                @endif
                @endif  
            </td>            
            <td class="align-middle">                            
                @if(is_array($recipient->criteria_array))  
                @foreach($recipient->criteria_array as $key => $value)            
                @if(is_array($value))
                @foreach($value as $v)
                <strong>{{snakeCaseToWords($key)}}</strong>: <span class="ml-sm-3">{{$v}}</span><br>
                @endforeach
                @else
                <strong>{{snakeCaseToWords($key)}}</strong>: <span class="ml-sm-3">{{$value}}</span><br>
                @endif
                @endforeach 
                @endif
            </td>
            <td class="text-center text-uppercase align-middle">{{$recipient->format}}</td>
            <td class="text-center align-middle">@if($recipient->enabled)<span class="text-success">Yes</span>@else <span class="text-danger">No</span> @endif</td>
            <td class="text-center align-middle">{{ucwords($recipient->frequency)}}</td>
            <td class="text-center align-middle">{{$recipient->time}}</td>
            <td class="text-center align-middle">
                @if($recipient->last_run)
                {{$recipient->last_run->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                @else
                <i>Never</i>
                @endif
            </td>
            <td class="text-center align-middle">
                @if($recipient->next_run)
                {{$recipient->next_run->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                @else
                <i>Unknown</i>
                @endif
            </td>
            <td class="text-center align-middle"><a href="{{ url('/mail-reports/' . $mailReport->id . '/edit-recipient/' . $recipient->id) }}" title="Edit Recipient"><span class="fas fa-pencil" aria-hidden="true"></span></a></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection