@extends('layouts.mail')

@section('content')

<h1 class="error">Action Required - a high number of jobs need proof of delivery/collection</h1>

<h2>Please close these requests at the soonest opportunity. This can be done from the <a href="{{ url('/transport-jobs/close')}}">POD/Close Jobs</a> option under the <u>Transport</u> menu.</h2>

<h1>Deliveries - Awaiting Proof Of Delivery ({{$deliveries->count()}})</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Number</th>           
            <th>Reference</th>
            <th>Service</th>
            <th>From</th>
            <th>To</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($deliveries as $transportJob)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{ url('/transport-jobs', $transportJob->id) }}" class="job-number">{{$transportJob->number}}</a></td>

            <td>
                @if($transportJob->shipment)
                <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                @elseif($transportJob->scs_job_number)
                {{$transportJob->scs_job_number}}
                @else
                {{$transportJob->reference}}
                @endif
            </td>
            <td>
                @if($transportJob->shipment)
                {{$transportJob->shipment->service->code}}
                @else
                n/a
                @endif
            </td>
            <td>{{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}}</td>
            <td>{{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}}</td>
            <td>   
                @if(!$transportJob->date_requested->isToday())
                <span class="error">{{$transportJob->date_requested->format('l jS F')}}</span>
                @else
                {{$transportJob->date_requested->format('l jS F')}}         
                @endif
            </td> 
        </tr>
        @endforeach
    </tbody>
</table>

@if($collections->count() > 0)

    <h1>Collections - Awaiting Proof Of Collection ({{$collections->count()}})</h1>

    <table border="0" cellspacing="0" width="100%" class="table">

        <thead>
            <tr>
                <th>#</th>
                <th>Number</th>           
                <th>Reference</th>
                <th>From</th>
                <th>To</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @foreach($collections as $transportJob)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td><a href="{{ url('/transport-jobs', $transportJob->id) }}" class="job-number">{{$transportJob->number}}</a></td>
                <td>
                    @if($transportJob->shipment)
                    <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                    @elseif($transportJob->scs_job_number)
                    {{$transportJob->scs_job_number}}
                    @else
                    {{$transportJob->reference}}
                    @endif
                </td>
                <td>{{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}}</td>
                <td>{{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}}</td>
                <td>   
                    @if(!$transportJob->date_requested->isToday())
                    <span class="error">{{$transportJob->date_requested->format('l jS F')}}</span>
                    @else
                    {{$transportJob->date_requested->format('l jS F')}}         
                    @endif
                </td> 
            </tr>
            @endforeach
        </tbody>
    </table>

@endif

@endsection