@extends('layouts.mail')

@section('content')

<h1>POD Report - {{$name}}</h1>

@if(count($data) > 0)

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th class="text-left">Consignment Number</th>
            <th class="text-left">Shipment Reference</th>
            <th class="text-left">Recipient</th>
            <th>Service</th>
            <th>Transit</th>
            <th>Status</th>
            <th>Signature</th>
            <th>Tracking</th>
        </tr>
    </thead>

    @foreach($data as $value)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$value['Consignment Number']}}</td>
        <td>{{$value['Shipment Reference']}}</td>
        <td>{{$value['Recipient Name']}}, {{$value['Recipient City']}} {{$value['Recipient Country']}}</td>
        <td class="text-center">{{strtoupper($value['Service'])}}</td>
        <td class="text-center">{{$value['Time In Transit']}} hrs</td>        
        <td class="text-center inserted">{{$value['Status']}}</td>  
        <td><b>{{$value['POD Signature']}}</b></td>
        <td class="text-center"><a href="{{$value['Tracking']}}">Track</a></td>     
    </tr>

    @endforeach

</table>

@else

<p>No POD information available today.</p>

@endif



@endsection