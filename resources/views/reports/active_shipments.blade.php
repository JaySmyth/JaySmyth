@extends('layouts.app')

@section('content')

<h2>{{$report->name}}</h2>

<table class="table table-striped table-bordered table-sm mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Service</th>
            <th>Dating Back To</th>                            
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight</th>                   
            <th class="text-right">Shipments</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($services as $key => $service)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="#{{$key}}">{{$service}}</a></td>
            <td>
                @if($shipmentsByService[$key]['shipments'][0]->ship_date->diffInDays() > 14)
                <span class="text-danger"><b>{{$shipmentsByService[$key]['shipments'][0]->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</b> <span class="fas fa-fw fa-exclamation-triangle text-danger ml-sm-1" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="High transit times"></span></span>
                @else
                {{$shipmentsByService[$key]['shipments'][0]->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                @endif
            </td>                      
            <td class="text-right">{{$shipmentsByService[$key]['pieces']}}</td>
            <td class="text-right">{{number_format($shipmentsByService[$key]['weight'], 2)}}</td>            
            <td class="text-right">{{count($shipmentsByService[$key]['shipments'])}}</td>  
        </tr>
        @endforeach

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="text-right"><span class="text-large"><b>{{number_format($shipments->sum('pieces'))}}</b></span></td>
            <td class="text-right"><span class="text-large"><b>{{number_format($shipments->sum('weight'), 2)}}</b></span></td>
            <td class="text-right"><span class="text-large"><b>{{number_format($shipments->count())}}</b></span></td>
        </tr>

    </tbody>
</table>


@foreach($shipmentsByService as $key => $service)

@if(isset($services[$key]))

<h4 class="mb-3" id="{{$key}}">{{$services[$key]}} <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{count($shipmentsByService[$key]['shipments'])}}</span></h4>

<table class="table table-striped table-bordered table-sm mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Consignment</th>
            <th>Carrier Consignment</th>
            <th>Ship Date</th>
            <th>Destination</th>
            @if(Auth::user()->hasIfsRole())<th>Shipper</th>@endif            
            <th>Transit</th>
            <th class="text-center">Service</th>
            <th class="text-center">Status</th>
            @if(Auth::user()->hasIfsRole())
            <th class="text-center">Carrier</th>                
            <th class="text-center">IFS</th>
            @else
            <th class="text-center">Tracking</th>
            @endif
        </tr>
    </thead>
    <tbody>

        @foreach($service['shipments'] as $i => $shipment)

        <tr>
            <td>{{$i + 1}}</td>
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            <td>{{$shipment->carrier_consignment_number}}</td>
            <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>                
            <td>{{$shipment->recipient_city}} <span class="ml-sm-2">{{$shipment->recipient_country_code}}</span></td>
            @if(Auth::user()->hasIfsRole())<td><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>@endif            
            <td>                
                {{$shipment->timeInTransit}}<span class="hours">hrs</span>         

                @if($shipment->timeInTransit > 200)
                <span class="fas fa-fw fa-exclamation-triangle text-danger ml-sm-1" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="High transit time"></span> 
                @elseif($shipment->timeInTransit > 100)
                <span class="fas fa-fw fa-exclamation-triangle text-warning ml-sm-1" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="High transit time"></span> 
                @endif
            </td>       
            <td class="text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name or 'Unknown'}}">{{$shipment->service->code or ''}}</span></td>
            <td class="text-center"><span class="status {{$shipment->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->status->description}}">{{$shipment->status->name}}</span></td>                

            @if(Auth::user()->hasIfsRole())
            <td class="text-center">
                @if($shipment->carrier_tracking_url)
                <a href="{{$shipment->carrier_tracking_url}}" title="Carrier Tracking Link" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                @else
                <i class="fas fa-external-link-alt faded"></i>
                @endif
            </td>
            @endif

            <td class="text-center">
                <a href="{{ url('/tracking/' . $shipment->token) }}" title="IFS Tracking Link" target="_blank"><i class="fas fa-external-link-alt"></i></a>
            </td>

        </tr>

        @endforeach

    </tbody>
</table>

@endif

@endforeach

@if(count($shipmentsByService) <= 0)
<div class="no-results">Sorry, no shipments found!</div>
@endif

@endsection