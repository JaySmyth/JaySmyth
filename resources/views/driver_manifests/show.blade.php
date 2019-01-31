@extends('layouts.app')

@section('content')

<h2>Driver Manifest <small>{{$driverManifest->driver->name}} - {{$driverManifest->date->timezone(Auth::user()->time_zone)->format('l jS F, Y')}}</small>
    <div class="float-right">
        @if($driverManifest->closed)
        <span class="badge badge-cancelled float-right">Closed</span>
        @else
        <span class="badge badge-delivered float-right">Open</span>
        @endif
    </div>
</h2>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>Manifest Number</th>
            <th>Date/Time Opened</th>
            <th>Registration</th>
            <th>Vehicle Type</th>           
            <th>Total Locations</th>
            <th>Total Collections</th>
            <th>Total Deliveries</th>
            <th>Total Jobs</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$driverManifest->number}}</td>
            <td>{{$driverManifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</td>
            <td>{{$driverManifest->vehicle->registration}}</td>
            <td>{{$driverManifest->vehicle->type}}</td>
            <td>{{count($jobsByLocation)}} locations</td>
            <td>{{$driverManifest->collection_count}} jobs - {{$driverManifest->pieces_to_collect}} pieces</td>
            <td>{{$driverManifest->delivery_count}} jobs - {{$driverManifest->pieces_to_deliver}} pieces</td>
            <td>{{$driverManifest->total_count}} Jobs</td>
        </tr>
    </tbody>
</table>

@if($driverManifest->total_count > 0)

<h4 class="mb-2">Locations <span class="badge badge-pill badge-primary badge-sm">{{count($jobsByLocation)}}</span></h4>

<table class="table table-striped table-bordered table-sm mb-4">
    <tbody>

        @foreach($jobsByLocation as $location)

        <tr class="bg-secondary text-white">
            <th width="2%" class="bg-primary text-center">{{$loop->iteration}}</th>
            <th>Reference</th>            
            <th class="text-center">Type</th>
            <th>From</th>
            <th>To</th>            
            <th class="text-right">COD (£)</th>
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight (kg)</th>
            <th class="text-center">Status</th>

            @if(!$driverManifest->closed && Auth::user()->hasPermission('unmanifest_transport_job'))<th>&nbsp;</th>@endif
        </tr>
        @foreach($location['jobs'] as $transportJob)
        <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td>
                <a href="{{url('/transport-jobs', $transportJob->id)}}" class="job-reference">
                    @if($transportJob->shipment)
                    {{$transportJob->shipment->carrier_consignment_number}}
                    @elseif($transportJob->scs_job_number)
                    {{$transportJob->scs_job_number}}
                    @else
                    {{$transportJob->reference}}
                    @endif
                </a>
            </td>            
            <td class="text-center">                
                <span class="badge {{($transportJob->type == 'c') ? 'badge-secondary' : 'badge-primary'}} text-uppercase" data-placement="bottom" data-toggle="tooltip" data-original-title="{{($transportJob->type == 'c') ? 'Collection' : 'Delivery'}}">{{$transportJob->type}}</span>                
            </td>
            <td>{{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}} {{$transportJob->from_postcode}}</td>
            <td>{{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}} {{$transportJob->to_postcode}}</td>                        
            <td class="text-right">{{$transportJob->cash_on_delivery}}</td>
            <td class="text-right">{{$transportJob->pieces}}</td>
            <td class="text-right">{{$transportJob->weight}}</td>
            <td class="text-center"><span class="status {{$transportJob->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$transportJob->status->description}}">{{$transportJob->status->name}}</span></td>

            @if(!$driverManifest->closed && Auth::user()->hasPermission('unmanifest_transport_job') && $transportJob->status->code != 'collected')
            <td class="text-center">                
                @if($transportJob->isActive())                
                <a href="{{ url('/transport-jobs/' . $transportJob->id . '/unmanifest') }}" class="unmanifest-job" title="Unmanifest Job"><span class="fas fa-repeat" aria-hidden="true"></span> </a>                

                @if($transportJob->type == 'c')

                @if($transportJob->shipment)
                <a href="{{ url('/transport-jobs/' . $transportJob->id . '/collect') }}" class="set-collected" title="Set to Collected"><span class="fas fa-thumbs-up ml-sm-2" aria-hidden="true"></span></a>
                @else
                <a href="{{ url('/transport-jobs/' . $transportJob->id . '/collect') }}" class="set-completed" title="Set to Collected"><span class="fas fa-thumbs-up ml-sm-2" aria-hidden="true"></span></a>

                @endif


                @endif

                @endif            
            </td>
            @endif
        </tr>

        @endforeach

        <tr class="table-warning">
            <td>&nbsp;</td>
            <td>&nbsp;</td>                       
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="text-center">&nbsp;</td>
            <td class="text-right"><strong>{{number_format($location['cod'], 2)}}</strong></td>
            <td class="text-right"><strong>{{$location['pieces']}}</strong></td>
            <td class="text-right"><strong>{{number_format($location['weight'], 2)}}</strong></td>
            <td>&nbsp;</td>
            @if(!$driverManifest->closed && Auth::user()->hasPermission('unmanifest_transport_job'))<td>&nbsp;</td>@endif
        </tr>

        <tr><td colspan="10">&nbsp;</td></tr>

        @endforeach

    </tbody>
</table>

@else
<div class="no-results">Sorry, no jobs found!</div>
@endif

@endsection