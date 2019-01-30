@extends('layouts.app')

@section('content')

<h2>
    <div class="pull-left">
        {{$rate->description}}
    </div>
    <div class="badge badge-secondary pull-right">
        @if ($rate->residential_charge > 0)
        <span text-uppercase>Residential Charge : {{$rate->residential_charge}}<br></span>
        @endif
        <span>Model : </span>
        <span class="text-uppercase ">{{$rate->model}}/ Volumetric Divisor : {{$rate->volumetric_divisor}}</span>
    </div>
</h2>
<div class="clearfix"></div>
<hr class="mb-4">

@foreach($table as $residential => $residentialTable)
@foreach($residentialTable as $pieceLimit => $pieceTable)

<h4>
    @if($residential)
    Residential Addresses - Piece Limit : {{$pieceLimit}}
    @else
    Commercial Addresses - Piece Limit : {{$pieceLimit}}
    @endif
</h4>

<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>Package Type</th>
            <th class='text-right'>Break Point</th>
            @foreach($zones as $zone)
            <th class='text-right'>Zone {{$zone}}</th>
            @endforeach
            <th>Units</th>
            <th>From Date</th>
            <th>To Date</th>
        </tr>
    </thead>
    <tbody>

        @foreach($pieceTable as $packageType => $packageTable)
        @foreach($packageTable as $breakPoint => $tableRow)

        <tr>    
            <td>{{$packageType}}</td>
            <td class='text-right'>{{$breakPoint}}</td>

            @foreach($zones as $zone)

            @if(isset($tableRow[$zone]))
            <td class='text-right'>{{$tableRow[$zone]['value']}}</td>
            @else
            <td>&nbsp;</td>
            @endif

            @endforeach

            <td>{{$tableRow[$zones[0]]['weight_units']}}</td>
            <td>{{$tableRow[$zones[0]]['from_date']}}</td>
            <td>{{$tableRow[$zones[0]]['to_date']}}</td>

        </tr>

        @endforeach
        @endforeach

    </tbody>
</table>

@endforeach
@endforeach

@include('rates.partials.surcharges')
@endsection