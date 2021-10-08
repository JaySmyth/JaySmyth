@extends('layouts.mail')

@section('content')

    <h1>{{$subject}}</h1>

    <h2 class="error">The following {{ count($shipments) }} shipments were reset or swapped to another service.</h2>

    <table border="0" cellspacing="0" width="100%" class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Sender</th>
            <th>Consignment</th>
            <th>Carrier Tracking</th>
            <th>Carrier</th>
            <th>Service</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shipments as $shipment)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    @if($shipment->sender_company_name)
                        {{$shipment->sender_company_name}}
                    @else
                        {{ $shipment->company->company_name }}
                    @endif
                </td>
                <td>
                    <a href="{{url('/shipments', $shipment->id)}}"
                       title="View Shipment">{{$shipment->consignment_number}}
                    </a>
                </td>
                <td>{{$shipment->carrier_consignment_number}}</td>
                <td>{{$shipment->carrier->name}}</td>
                <td>{{strtoupper($shipment->service->code)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection