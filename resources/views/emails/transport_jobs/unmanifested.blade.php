@extends('layouts.mail')

@section('content')

<h1 class="error">Action Required - {{$transportJobs->count()}} Jobs Need Manifested</h1>

<h2>Please allocate the <a href="{{ url('/transport-jobs/unmanifested')}}">unmanifested jobs</a> to a driver's manifest. This can be done from the <u>Unmanifested Jobs</u> option under the <u>Transport</u> menu.</h2>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Number</th>
            <th>Type</th>                
            <th>Reference</th>
            <th>Service</th>
            <th>From</th>
            <th>To</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($transportJobs as $transportJob)
        <tr>
            <td>{{$loop->iteration}}</td>
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
            <td>{{$transportJob->status->name}}</td>
        </tr>
        @endforeach
    </tbody>

</table>
<br>

@endsection