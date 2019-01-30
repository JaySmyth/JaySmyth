@extends('layouts.app')

@section('content')

<input id="hide_nav" type="hidden" value="1">

<div class="negative-marging-top text-center mb-5"><img alt="IFS Global Logistics" src="/images/ifs_logo.png"></div>

<div class="tracker-progress-bar">
    <div class="progress">
        <div class="progress-bar progress-bar-{{getProgressBarColour($shipment->status->code)}}" style="width: {{$shipment->progress}}%;">
        </div>
    </div>

    <div class="{{getProgressBarColour($shipment->status->code)}}">

        <span class="dot state-0"></span>

        <span class="state-label state-0 {{getTrackingState($shipment->progress, 0)}}">
            @if($shipment->progress == 0)
            {{$shipment->status->name}}
            @else
            Pre-Transit
            @endif
        </span>

        @if($shipment->progress >= 25)
        <span class="dot state-1"></span>
        @endif

        <span class="state-label state-1 {{getTrackingState($shipment->progress, 50)}}">In Transit</span>

        @if($shipment->progress > 50)
        <span class="dot state-2"></span>
        @endif


        <span class="state-label state-2 {{getTrackingState($shipment->progress, 75)}}">Out for Delivery</span>

        @if($shipment->progress > 75)
        <span class="dot state-3"></span>
        @endif

        <span class="state-label state-3 {{getTrackingState($shipment->progress, 100)}}">
            @if($shipment->progress == 100 && $shipment->status->code != 'delivered')
            {{$shipment->status->name}}
            @else
            Delivered
            @endif
        </span>

    </div>
</div>

<div class="clearfix"><br></div>


<div class="row">
    <div class="col-sm-4 text-muted">
        Tracking Number
    </div>

    <div class="col-sm-4 text-muted">
        @if($shipment->carrier_id != 1)
        Carrier Reference
        @endif
    </div>

    <div class="col-sm-4 text-muted">

        @if($shipment->delivered)
            Signed For
        @else
            Estimated Delivery Date
        @endif

    </div>

    <div class="col-sm-4">
        <strong class="text-primary text-large">{{$shipment->consignment_number}}</strong>
    </div>

    <div class="col-sm-4">
        @if($shipment->carrier_id != 1)
        <strong class="text-primary text-large">{{$shipment->carrier_tracking_number}}</strong>
        @endif
    </div>

    <div class="col-sm-4">

        <strong class="text-primary text-large">
            
        @if($shipment->delivered)
        {{ $shipment->pod_signature }}
        @else
        {{$shipment->getEstimatedDeliveryDate('jS F, Y')}}
        @endif

        </strong>
        
    </div>

</div>

<br>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th><span class="far fa-calendar-alt" aria-hidden="true"></span> Date</th>
            <th><span class="far fa-clock" aria-hidden="true"></span> Local Time</th>
            <th><span class="fas fa-exchange-alt" aria-hidden="true"></span> Status</th>
            <th><span class="fas fa-map-marker" aria-hidden="true"></span> Location</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->tracking as $tracking)
        <tr>
            <td>
                {{$tracking->datetime->timezone('Europe/London')->format('jS M - Y')}}
            </td>
            <td>
                {{$tracking->datetime->timezone('Europe/London')->format('g:ia')}}
            </td>
            <td>
                {{$tracking->message}}
            </td>
            <td>
                @if($tracking->city || $tracking->country_code)
                {{$tracking->city}}

                @if($tracking->state)
                , {{$tracking->state}}
                @endif

                @if($tracking->country_code)
                - {{getCountry($tracking->country_code)}}
                @endif

                @else
                <i>n/a</i>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection