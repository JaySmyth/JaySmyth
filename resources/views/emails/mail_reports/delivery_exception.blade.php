@extends('layouts.mail')

@section('content')


@if(count($data) > 0)

<h1>Delivery Exception Report - {{$name}} ({{count($data)}} shipments)</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th class="text-left">Consignment</th>
            <th class="text-left">Carrier Reference</th>
            <th class="text-left">Shipment Reference</th>
            <th class="text-left">Recipient</th>
            <th>Service</th>
            <th>Transit</th>
            <th>Status</th>
            <th>Tracking</th>
        </tr>
    </thead>

    @foreach($data as $value)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$value['Consignment Number']}}</td>
        <td>{{$value['Carrier Consignment Number']}}</td>
        <td>{{$value['Shipment Reference']}}</td>
        <td>{{$value['Recipient Name']}}, {{$value['Recipient City']}} {{$value['Recipient Country']}}</td>
        <td class="text-center">{{strtoupper($value['Service'])}}</td>
        <td class="text-center">{{$value['Time In Transit']}} hrs</td>        
        <td class="text-center">{{$value['Status']}}</td>     
        <td class="text-center"><a href="{{$value['Tracking']}}">Track</a></td>     
    </tr>

    @endforeach

</table>

@else

<h1>Delivery Exception Report</h1>

<p>No outstanding shipments today.</p>

@endif

@endsection