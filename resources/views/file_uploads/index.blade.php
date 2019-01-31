@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'file uploads (to customer)', 'results'=> $fileUploads])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Type</th>
            <th>Shipper</th>                        
            <th class="text-center">Enabled</th>                
            <th class="text-center">Frequency</th>
            <th class="text-center">Time</th>
            <th class="text-center">Last Upload</th>
            <th class="text-center">Last Status</th>
            <th class="text-center">Next Upload</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fileUploads as $upload)
        <tr>
            <td><a href="{{ url('/file-uploads', $upload->id)}}">{{strtoupper($upload->type)}} Upload</a></td>                                
            <td>{{$upload->company->company_name}}</td>                  
            <td class="text-center align-middle">@if($upload->enabled)<span class="text-success">Yes</span>@else <span class="text-danger">No</span> @endif</td>
            <td class="text-center align-middle">{{ucwords($upload->frequency)}}</td>
            <td class="text-center align-middle">{{$upload->time}}</td>
            <td class="text-center align-middle">
                @if($upload->last_upload)
                {{$upload->last_upload->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                @else
                <i>Never</i>
                @endif
            </td>
            <td class="text-center align-middle">@if($upload->last_status)<span class="text-success">Success</span>@else <span class="text-danger">Upload Failed</span> @endif</td>  
            <td class="text-center align-middle">
                @if($upload->next_upload)
                {{$upload->next_upload->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                @else
                <i>Unknown</i>
                @endif
            </td>                        
            <td class="text-center">
                <a href="{{ url('/file-uploads/' . $upload->id . '/retry') }}" title="Retry Upload"><span class="fas fa-sync" aria-hidden="true"></span></a>                              
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'file uploads', 'results'=> $fileUploads])
@include('partials.pagination', ['results'=> $fileUploads])

@endsection