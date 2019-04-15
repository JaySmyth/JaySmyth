@extends('layouts.app')

@section('content')

    <div class="clearfix">
        <h2 class="float-left">Sea Freight Shipment - {{$shipment->number}}</h2>
        <h2 class="float-right">
            <span class="badge badge-{{$shipment->seaFreightStatus->code}}">{{$shipment->seaFreightStatus->name}}</span>
        </h2>
    </div>

    <div class="row mb-4">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">Shipping Instruction</div>
                <div class="card-body text-large">
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Ship Reference</strong></div>
                        <div class="col-sm-7">{{$shipment->reference}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Final Destination</strong></div>
                        <div class="col-sm-7">{{$shipment->final_destination}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Final Destination Country</strong></div>
                        <div class="col-sm-7">{{getCountry($shipment->final_destination_country_code)}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Required On Dock Date</strong></div>
                        <div class="col-sm-7">{{$shipment->required_on_dock_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Gross Weight (KG)</strong></div>
                        <div class="col-sm-7">{{$shipment->weight}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Number of Containers</strong></div>
                        <div class="col-sm-7">{{$shipment->number_of_containers}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Shipment Value</strong></div>
                        <div class="col-sm-7">{{$shipment->value}} {{$shipment->value_currency_code}}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Special Instructions</strong></div>
                        <div class="col-sm-7">
                            @if(strlen($shipment->special_instructions))
                                {{$shipment->special_instructions}}
                            @else
                                No special instructions
                            @endif

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><strong>Date / Time Created</strong></div>
                        <div class="col-sm-7">{{$shipment->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div id="panel-show-summary" class="card h-100">
                <div class="card-header">
                    Summary
                    @if(Auth::user()->hasIfsRole())
                        <div class="float-right">
                            {{ $shipment->scs_job_number }}
                        </div>
                    @endif
                </div>
                <div class="card-body text-large">

                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-industry" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Shipping Line</strong></div>
                        <div class="col-sm-7">
                            @if($shipment->processed)
                                {{$shipment->shippingLine->name}}
                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-file" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Bill of Lading</strong></div>
                        <div class="col-sm-7">
                            @if($shipment->processed)
                                {{$shipment->bill_of_lading}}
                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-calendar" aria-hidden="true"></span>
                            <strong class="ml-sm-3">@if(!$shipment->departure_date)Estimated @endif Departure Date</strong>
                        </div>
                        <div class="col-sm-7">
                            @if($shipment->processed)

                                @if($shipment->departure_date)
                                    {{$shipment->departure_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                                @else
                                    {{$shipment->estimated_departure_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                                @endif

                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-calendar" aria-hidden="true"></span>
                            <strong class="ml-sm-3">@if(!$shipment->arrival_date)Estimated @endif Arrival Date</strong>
                        </div>
                        <div class="col-sm-7">
                            @if($shipment->processed)

                                @if($shipment->arrival_date)
                                    {{$shipment->arrival_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                                @else
                                    {{$shipment->estimated_arrival_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                                @endif

                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-map-marker" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Port of Loading</strong></div>
                        <div class="col-sm-7">
                            @if($shipment->processed)
                                {{$shipment->port_of_loading}}
                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-map-marker" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Port of Discharge</strong></div>
                        <div class="col-sm-7">
                            @if($shipment->processed)
                                {{$shipment->port_of_discharge}}
                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="fas fa-fw fa-ship" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Vessel</strong></div>

                        <div class="col-sm-7">
                            @if($shipment->processed)
                                {{$shipment->vessel}}
                            @else
                                <em><span class="faded">Awaiting booking</span></em>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="far fa-clock fa-fw" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Transit</strong></div>
                        <div class="col-sm-7">{{$shipment->timeInTransit}} Days In Transit</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-5"><span class="far fa-clock fa-fw" aria-hidden="true"></span>
                            <strong class="ml-sm-3">Remaining</strong></div>
                        <div class="col-sm-7">

                            @if($shipment->timeRemaining < 0)
                                <span class="text-danger">{{$shipment->timeRemaining}} Days Behind Schedule</span>
                            @else
                                {{$shipment->timeRemaining}} Days
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Containers -->
    @include('sea_freight_shipments.partials.containers', ['shipment' => $shipment])

    <!-- Tracking -->
    @include('sea_freight_shipments.partials.tracking', ['shipment' => $shipment])

    <!-- Documents -->
    @include('documents.partials.documents', ['parentModel' => $shipment, 'modelName' => 'sea-freight-shipment'])

@endsection