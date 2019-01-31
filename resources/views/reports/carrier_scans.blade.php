@extends('layouts.app')

@section('content')

<h2>{{$report->name}}</h2>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr class="success">
            <th>#</th>
            <th>Consignment</th>
            <th>Carrier Consignment</th>                   
            <th>Shipper</th>
            <th class="text-center">Service</th>
            <th class="text-center">Pieces</th>
            <th class="text-center">Carrier Scans</th>           
        </tr>
    </thead>
    <tbody>
        @foreach($tracking as $track)
        <tr>
            <td class="align-middle">{{$loop->iteration}}</td>
            <td class="align-middle"><a href="{{ url('/shipments', $track->shipment->id) }}" class="consignment-number">{{$track->shipment->consignment_number}}</a></td>
            <td class="align-middle">{{$track->shipment->carrier_consignment_number}}</td>                    
            <td class="align-middle">
                @if($track->shipment->company)
                <a href="{{ url('/companies', $track->shipment->company->id) }}">{{$track->shipment->company->company_name}}</a>
                @else
                Unknown
                @endif
            </td>                
            <td class="align-middle text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$track->shipment->service->name ?? 'Unknown'}}">{{$track->shipment->service->code ?? ''}}</span></td>
            <td class="align-middle text-center">{{$track->shipment->pieces}}</td>
            <td class="align-middle text-center">
                {{str_replace('received (carrier scan)', '', $track->message)}} - {{$track->datetime->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'carrier scans', 'results'=> $tracking])
@include('partials.pagination', ['results'=> $tracking])

@endsection