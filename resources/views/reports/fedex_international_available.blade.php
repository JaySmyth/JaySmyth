@extends('layouts.app')

@section('content')

<h2 class="mb-3">FedEx International - Available For Manifesting <span class="badge badge-primary">{{$total}}</span></h2>

<h3>Route IFS Antrim Depot (ANT)<span class="badge badge-secondary float-right">{{$ant->count()}}</span></h3>

<table class="table table-striped table-bordered table-sm mb-4">
    <thead>
        <tr>
            <th>#</th>
            <th>Consignment</th>
            <th>Carrier Ref</th>             
            <th>Ship Date</th>
            <th>Shipper</th>          
            <th>Destination</th>
            <th class="text-center">Service</th>
            <th class="text-right">Pieces</th> 
            <th class="text-right">Weight</th>            
        </tr>
    </thead>

    <tbody>
        @foreach($ant as $shipment)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            <td>{{$shipment->carrier_consignment_number}}</td>
            <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-truncate">{{$shipment->sender_company_name}}</td>
            <td class="text-truncate">{{$shipment->recipient_city}} <span class="ml-sm-2">{{$shipment->recipient_country_code}}</span></td>            
            <td class="text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
            <td class="text-right">{{$shipment->pieces}}</td>            
            <td class="text-right">{{$shipment->weight}}</td>         
        </tr>
        @endforeach

        <tr class="text-large bg-secondary text-white">
            <td colspan="7">&nbsp;</td>
            <th scope="row" class="text-right">{{number_format($ant->sum('pieces'))}}</td>
            <th scope="row" class="text-right">{{number_format($ant->sum('weight'), 1)}}</td>            
        </tr>
    </tbody>
</table>

<h3>Route Belfast International Airport (BFS) <span class="badge badge-secondary float-right">{{$bfs->count()}}</span></h3>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Consignment</th>
            <th>Carrier Ref</th>             
            <th>Ship Date</th>
            <th>Shipper</th>          
            <th>Destination</th>
            <th class="text-center">Service</th>
            <th class="text-right">Pieces</th> 
            <th class="text-right">Weight</th>            
        </tr>
    </thead>

    <tbody>
        @foreach($bfs as $shipment)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            <td>{{$shipment->carrier_consignment_number}}</td>
            <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-truncate">{{$shipment->sender_company_name}}</td>
            <td class="text-truncate">{{$shipment->recipient_city}} <span class="ml-sm-2">{{$shipment->recipient_country_code}}</span></td>            
            <td class="text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
            <td class="text-right">{{$shipment->pieces}}</td>            
            <td class="text-right">{{$shipment->weight}}</td>         
        </tr>
        @endforeach
        <tr class="text-large bg-secondary text-white">
            <td colspan="7">&nbsp;</td>
            <th scope="row" class="text-right">{{number_format($bfs->sum('pieces'))}}</td>
            <th scope="row" class="text-right">{{number_format($bfs->sum('weight'), 1)}}</td>            
        </tr>
    </tbody>
</table>

@endsection