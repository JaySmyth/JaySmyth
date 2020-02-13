@extends('layouts.app')

@section('navSearchPlaceholder', 'Job search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Job Number, Reference or SCS Job Number:
    </label>
    <div class="col-sm-6">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control"> 
    </div>   
</div>
<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Job Type:
    </label>
    <div class="col-sm-6">
        {!! Form::select('type', dropDown('jobType', 'Collections and Deliveries'), Request::get('type'), array('class' => 'form-control')) !!}
    </div>   
</div>
<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Department:
    </label>
    <div class="col-sm-6">
        {!! Form::select('department', dropDown('departments', 'All Departments'), Request::get('department'), array('class' => 'form-control')) !!}
    </div>   
</div>
<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Status:
    </label>
    <div class="col-sm-6">
        {!! Form::select('status', dropDown('jobStatuses', 'All Statuses'), Request::get('status'), array('class' => 'form-control')) !!}
    </div>   
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@if(Auth::user()->hasPermission('manifest_transport_jobs'))

@section('toolbar')
<a href="{{ url('/transport-jobs/email-dockets')}}" class="mr-sm-3" title="Email dockets"><span class="fas fa-envelope fa-lg" aria-hidden="true"></span></a>
@endsection

@endif



@include('partials.title', ['title' => 'transport jobs', 'results'=> $transportJobs])


<table class="table table-striped">
    <thead>
        <tr>
            <th>Number</th>
            <th>Type</th>                
            <th>Reference</th>
            <th class="text-center">Service</th>
            <th>From</th>
            <th>To</th>
            <th>Date</th>
            <th>Route</th>
            <th class="text-center">Status</th>
            @can('cancel_transport_job')<th>&nbsp;</th>@endcan
        </tr>
    </thead>
    <tbody>
        @foreach($transportJobs as $transportJob)
        <tr>
            <td><a href="{{ url('/transport-jobs', $transportJob->id) }}" class="job-number">{{$transportJob->number}}</a></td>
            <td>{{($transportJob->type == 'c') ? 'Collection' : 'Delivery'}}</td>
            <td>
                @if($transportJob->shipment)
                <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                @elseif($transportJob->scs_job_number)
                {{$transportJob->scs_job_number}}
                @else
                {{$transportJob->reference}}
                @endif
            </td>
            <td class="text-center">
                @if($transportJob->shipment)
                <span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$transportJob->shipment->service->name ?? 'Unknown'}}">{{$transportJob->shipment->service->code ?? ''}}</span>
                @else
                n/a
                @endif
            </td>
            <td>{{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}}</td>
            <td>{{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}}</td>

            <td>
                @if($transportJob->date_requested)
                {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                @endif
            </td>
            <td><span class="badge @if($transportJob->sent) badge-success @else badge-primary @endif">{{$transportJob->transend_route}}</span></td>

            <td class="text-center"><span class="status {{$transportJob->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$transportJob->status->description}}">{{$transportJob->status->name}}</span></td>

            @can('cancel_transport_job')
            <td class="text-center">

                <!-- Cancel shipment - only enable if active shipment and not yet received -->
                @if($transportJob->isCancellable() && Auth::user()->hasPermission('cancel_transport_job'))
                <a href="{{ url('/transport-jobs/' . $transportJob->id . '/cancel') }}" title="Cancel Job" class="cancel-transport-job"><span class="fas fa-times" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-times ml-sm-2 faded" aria-hidden="true" title="Cancel Job (unavailable)"></span>
                @endif

                @if($transportJob->isCancellable() && Auth::user()->hasPermission('edit_transport_job'))
                <a href="{{ url('/transport-jobs/' .  $transportJob->id . '/edit') }}" title="Edit Job" class="edit-transport-job"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-edit ml-sm-2 faded" aria-hidden="true" title="Edit Job (unavailable)"></span>
                @endif

                @if($transportJob->type == 'd' || ($transportJob->type == 'c' && !is_numeric($transportJob->shipment_id)))
                <a href="{{ url('/transport-jobs/' . $transportJob->id . '/docket') }}" title="Download POD Docket"><span class="fas fa-pencil ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-pencil ml-sm-2 faded" aria-hidden="true" title="Download POD Docket (unavailable)"></span>
                @endif

            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'transport jobs', 'results'=> $transportJobs])
@include('partials.pagination', ['results'=> $transportJobs])

@endsection