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
<h4>
    Commercial Addresses
</h4>

<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>Service</th>
            <th>Packaging</th>
            <th class="text-right">First</th>
            <th class="text-right">Others</th>
            <th class="text-right">Notional Weight</th>
            <th class="text-right">Notional</th>
            <th>Area</th>
            <th>From</th>
            <th>To</th>
        </tr>
    </thead>
    <tbody>

        @foreach($table as $line)

        <tr>
            <td>{{$line->service}}</td>
            <td>{{$line->packaging_code}}</td>
            <td class='text-right'>{{$line->first}}</td>
            <td class='text-right'>{{$line->others}}</td>
            <td class='text-right'>{{$line->notional_weight}}</td>
            <td class='text-right'>{{$line->notional}}</td>
            <td>{{$line->area}}</td>
            <td>{{$line->from_date}}</td>
            <td>{{$line->to_date}}</td>
        </tr>

        @endforeach

    </tbody>
</table>

@include('rates.partials.surcharges')
@endsection
