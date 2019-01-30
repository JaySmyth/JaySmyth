@extends('layouts.mail')

@section('content')

<h1>{{$subject}}</h1>

@if($receiptScans->count() > 0)

<h2 class="error">The following {{$receiptScans->count()}} packages were not scanned off the vehicle into the warehouse.</h2>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>            
            <th>Sender</th>
            <th>Consignment</th>
            <th>Carrier</th>
            <th>Package</th>           
            <th>Date/Time</th>
            <th>Carrier</th>
            <th>Service</th>             
            <th>Collection Route</th>
        </tr>
    </thead>

    <tbody>
        @foreach($receiptScans as $package)
        <tr>
            <td>{{$loop->iteration}}</td>            
            <td>{{$package->shipment->sender_company_name}}</td>
            <td><a href="{{url('/shipments', $package->shipment->id)}}" title="View Shipment">{{$package->shipment->consignment_number}}</a></td>
            <td>{{$package->shipment->carrier_consignment_number}}</td>
            <td>Package {{$package->index}} of {{$package->shipment->pieces}}</td>
            <td>{{$package->date_received->timezone('Europe/London')->format('d-m-y H:i')}}</td>
            <td>{{$package->shipment->carrier->name}}</td>
            <td>{{strtoupper($package->shipment->service->code)}}</td>               
            <td>{{$package->shipment->transportJobs->where('type', 'c')->first()->transend_route}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif


@if($routeScans->count() > 0)

<h2 class="error">The following {{$routeScans->count()}} packages were not scanned to route.</h2>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>            
            <th>Sender</th>
            <th>Consignment</th>
            <th>Carrier</th>
            <th>Package</th>           
            <th>Date/Time</th>
            <th>Carrier</th>
            <th>Service</th>             
            <th>Outbound Route</th>
        </tr>
    </thead>

    <tbody>
        @foreach($routeScans as $package)
        <tr>
            <td>{{$loop->iteration}}</td>            
            <td>{{$package->shipment->sender_company_name}}</td>
            <td><a href="{{url('/shipments', $package->shipment->id)}}" title="View Shipment">{{$package->shipment->consignment_number}}</a></td>
            <td>{{$package->shipment->carrier_consignment_number}}</td>
            <td>Package {{$package->index}} of {{$package->shipment->pieces}}</td>
            <td>{{$package->date_received->timezone('Europe/London')->format('d-m-y H:i')}}</td>
            <td>{{$package->shipment->carrier->name}}</td>
            <td>{{strtoupper($package->shipment->service->code)}}</td>               
            <td>{{$package->route}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

@endsection