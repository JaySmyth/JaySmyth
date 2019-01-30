@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">Shipment - {{$shipment->consignment_number}}

        <small class="text-muted ml-sm-3">
            {{$shipment->service->name}}
            @if(Auth::user()->hasIfsRole())
            ({{strtoupper($shipment->carrier->code)}})
            @endif
        </small>

        @if($shipment->isHighlighted() && Auth::user()->hasIfsRole())            
        <span class="badge badge-warning text-white ml-sm-3"><span class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></span> Operator Attention</span>      
        @endif
    </h2>
    <h2 class="float-right"><span class="badge badge-{{$shipment->status->code}}">{{$shipment->status->name}}</span></h2>
</div>

<div class="row mb-4">
    <div class="col-sm-7">
        <div class="card h-100">

            <div class="card-header">Sender / Recipient
                @if($shipment->legacy)
                <span class="float-right text-muted font-italic">Raised on legacy system - {{$shipment->carrier_consignment_number}}</span>
                @endif
            </div>

            <div class="card-body text-large">
                <div class="row text-truncate">
                    <div class="col-sm-5">

                        <h5>Sender
                            @if($shipment->sender_type == 'c')
                            <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($shipment->sender_type)}}"></span>
                            @else
                            <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($shipment->sender_type)}}"></span>
                            @endif
                        </h5>

                        {{$shipment->sender_name}}<br>
                        {{$shipment->sender_company_name}}<br>
                        {{$shipment->sender_address1}}<br>

                        @if($shipment->sender_address2)
                        {{$shipment->sender_address2}}<br>
                        @endif

                        @if($shipment->sender_address3)
                        {{$shipment->sender_address3}}<br>
                        @endif

                        {{$shipment->sender_city}}<br>
                        {{$shipment->sender_state}} {{$shipment->sender_postcode}}<br>
                        <strong>{{$shipment->sender_country}}</strong><br>

                        @if(!$shipment->sender_address2)<br>@endif

                        @if(!$shipment->sender_address3)<br>@endif

                        @if($shipment->sender_telephone)
                        Tel: {{$shipment->sender_telephone}}<br>
                        @endif

                        @if($shipment->sender_email)
                        <a href="mailto:{{$shipment->sender_email}}">{{$shipment->sender_email}}</a>
                        @endif

                    </div>

                    <div class="col-sm-2 pt-4 text-center">
                        <span class="chevron fa fa-chevron-right pt-4 mt-4" aria-hidden="true"></span>
                    </div>

                    <div class="col-sm-5">
                        <h5>Recipient
                            @if($shipment->recipient_type == 'c')
                            <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($shipment->recipient_type)}}"></span>
                            @else
                            <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($shipment->recipient_type)}}"></span>
                            @endif

                        </h5>

                        @if($shipment->recipient_name)
                        {{$shipment->recipient_name}}<br>
                        @endif

                        @if($shipment->recipient_company_name)
                        {{$shipment->recipient_company_name}}<br>
                        @endif

                        {{$shipment->recipient_address1}}<br>

                        @if($shipment->recipient_address2)
                        {{$shipment->recipient_address2}}<br>
                        @endif

                        @if($shipment->recipient_address3)
                        {{$shipment->recipient_address3}}<br>
                        @endif

                        {{$shipment->recipient_city}}<br>
                        {{$shipment->recipient_state}} {{$shipment->recipient_postcode}}<br>
                        <strong>{{$shipment->recipient_country}}</strong><br>

                        @if(!$shipment->recipient_company_name)<br>@endif

                        @if(!$shipment->recipient_address2)<br>@endif

                        @if(!$shipment->recipient_address3)<br>@endif

                        @if($shipment->recipient_telephone)
                        Tel: {{$shipment->recipient_telephone}}<br>
                        @endif

                        @if($shipment->recipient_email)
                        <a class="text-truncate" href="mailto:{{$shipment->recipient_email}}">{{$shipment->recipient_email}}</a>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-5">
        <div id="panel-show-summary" class="card h-100">
            <div class="card-header">
                Summary
                <div class="float-right">
                    @if(Auth::user()->hasIfsRole())
                    <button class="btn btn-outline-secondary btn-sm button-additional-information btn-xs" type="button">Additional Information</button>
                    @endif
                    @include('shipments.partials.actions', ['shipment' => $shipment])
                </div>
            </div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="far fa-building fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Shipper</strong></div>
                    <div class="col-sm-6 text-truncate">
                        @if(Auth::user()->hasIfsRole())
                        <a href="{{url('/companies', $shipment->company_id)}}" title="View Company">{{$shipment->shipper}}</a>
                        @else
                        {{$shipment->shipper}}
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="fas fa-paperclip fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Reference</strong></div>
                    <div class="col-sm-6">{{$shipment->shipment_reference}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="far fa-calendar-alt fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Ship Date</strong></div>
                    <div class="col-sm-6">{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="fas fa-balance-scale fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Weight</strong></div>
                    <div class="col-sm-6">{{$shipment->weight}} {{$shipment->weight_uom}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="fas fa-dollar-sign fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Value</strong></div>
                    <div class="col-sm-6">{{number_format($shipment->customs_value, 2)}} {{$shipment->customs_value_currency_code}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><span class="fas fa-cube fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Vol. Weight</strong></div>
                    <div class="col-sm-6">{{$shipment->volumetric_weight}} {{$shipment->weight_uom}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="far fa-clock fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Time in Transit</strong></div>
                    <div class="col-sm-6">
                        @if($shipment->timeInTransit == 0 && $shipment->delivered)
                        Unknown
                        @else
                        {{$shipment->timeInTransit}}<span class="hours">Hours</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6"><span class="fas fa-file-signature fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">POD Signature</strong></div>
                    <div class="col-sm-6">
                        @if($shipment->delivered)

                        @if($shipment->pod_signature)

                        @if($shipment->pod_image)
                        <a tabindex="0" class="" role="button" data-toggle="img-popover" data-trigger="focus" title="POD Signature" data-placement="left" data-img="{{$shipment->pod_image}}" data-style="image-fluid pod-image">{{$shipment->pod_signature}}</a>
                        @else
                        {{$shipment->pod_signature}}
                        @endif

                        @else                        
                        Unknown
                        @endif
                        @else
                        Awaiting Delivery
                        @endif


                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6"><span class="far fa-calendar-alt fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Delivery Date</strong></div>
                    <div class="col-sm-6">
                        @if($shipment->delivery_date)                        
                        {{$shipment->delivery_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' g:ia')}}
                        @else                        
                        {{ $shipment->getDeliveryDate() }}                        
                        @endif
                    </div>
                </div>

            </div>
        </div>

        @if(Auth::user()->hasIfsRole())
        <div id="panel-show-additional-information" class="card h-100">
            <div class="card-header">
                Additional Info
                <div class="float-right">
                    <button class="btn btn-outline-secondary btn-sm button-summary btn-xs" type="button">Summary</button>
                    @include('shipments.partials.actions', ['shipment' => $shipment])
                </div>
            </div>
            <div class="card-body text-large">
                <div class="row mb-2 text-truncate">
                    <div class="col-sm-6"></span> <strong>Carrier</strong></div>
                    <div class="col-sm-6">{{$shipment->carrier->name}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Carrier Consignment</strong></div>
                    <div class="col-sm-6">{{$shipment->carrier_consignment_number ?? 'n/a'}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Carrier Service</strong></div>
                    <div class="col-sm-6 text-truncate">{{$shipment->service->carrier_name}} <span class="badge badge-secondary">{{$shipment->service->carrier_code}}</span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Account Numbers</strong></div>
                    <div class="col-sm-6">
                        <span id="summary_bill_shipping_account" title="Bill Shipping To Account">{{$shipment->bill_shipping_account}}</span>

                        <span class="badge badge-secondary mr-sm-3" data-placement="bottom" data-toggle="tooltip" data-original-title="Bill Shipping To {{$shipment->bill_shipping}}">{{strtoupper(substr($shipment->bill_shipping, 0,1))}}</span>

                        @if($shipment->bill_tax_duty_account)
                        <span id="summary_bill_tax_duty_account" title="Bill Tax And Duty To Account">{{$shipment->bill_tax_duty_account}}</span>
                        @else
                        <span class="text-muted"><i>Default</i></span>
                        @endif

                        <span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="Bill Tax And Duty To {{$shipment->bill_tax_duty}}">{{strtoupper(substr($shipment->bill_tax_duty, 0,1))}}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Depot</strong></div>
                    <div class="col-sm-6">{{$shipment->depot->name}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Routing</strong></div>
                    <div class="col-sm-6">{{$shipment->route->name ?? 'Unknown'}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"></span> <strong>Manifest Number</strong></div>
                    <div class="col-sm-6">
                        @if($shipment->manifest)
                        <a href="{{ url('/manifests', $shipment->manifest->id) }}">{{$shipment->manifest->number}}</a>
                        @else
                        Not Manifested
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6"><strong>Manifest Date</strong></div>
                    <div class="col-sm-6">
                        @if($shipment->manifest)
                        {{$shipment->manifest->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                        @else
                        Not Manifested
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6"><strong>SCS Number</strong></div>
                    <div class="col-sm-6">{{$shipment->scs_job_number ?? 'Unknown'}}</div>
                </div>

            </div>
        </div>

        @endif

    </div>
</div>

@include('tracking.partials.events_form', ['shipment' => $shipment])

<h4 class="mb-2">Packages <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->packages->count()}}</span></h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th class="text-right">Length</th>
            <th class="text-right">Width</th>
            <th class="text-right">Height</th>
            <th class="text-right">Weight</th>
            <th class="text-center">Packaging Code</th>
            <th class="text-center">Package Number</th>
            <th class="text-center">Received by IFS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->packages as $package)
        <tr>
            <td>{{$package->index}}</td>
            <td class="text-right">{{$package->length}} {{$shipment->dims_uom}}</td>
            <td class="text-right">{{$package->width}} {{$shipment->dims_uom}}</td>
            <td class="text-right">{{$package->height}} {{$shipment->dims_uom}}</td>
            <td class="text-right">{{$package->weight}} {{$shipment->weight_uom}}</td>
            <td class="text-center">{{$package->packaging_code ?? 'Unknown'}}</td>
            <td class="text-center">{{$package->carrier_tracking_number}}</td>
            <td class="text-center">
                @if($package->received)
                <span class="fas fa-check fa-lg mr-sm-2 text-success"></span>

                @if($package->date_received)
                Received at {{$package->date_received->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                Received <i><span class="text-muted">(receipt time unknown)</span></i>
                @endif

                @else
                <span class="fas fa-times fa-lg text-danger" aria-hidden="true"></span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@if($shipment->contents->count() > 0)

<h4 class="mb-2">Contents <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->contents->count()}}</span></h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Package</th>
            <th>Description</th>
            <th>Manufacturer</th>
            <th>Country of Manufacture</th>
            <th class="text-right">Quantity</th>
            <th class="text-right">Unit Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->contents as $content)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>Package {{$content->package_index}}</td>
            <td>{{$content->description}}</td>
            <td>{{$content->manufacturer}}</td>
            <td>{{getCountry($content->country_of_manufacture)}}</td>
            <td class="text-right">{{$content->quantity}}</td>
            <td class="text-right">{{$content->unit_value}} {{$content->currency_code}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@else

<h4 class="mb-2">Contents <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->packages->count()}}</span></h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Package</th>
            <th>Description</th>
            <th>Manufacturer</th>
            <th>Country of Manufacture</th>
            <th class="text-right">Quantity</th>
            <th class="text-right">Unit Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->packages as $package)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>Package {{$package->index}}</td>
            <td>{{$shipment->goods_description}}{{$shipment->documents_description}}</td>
            <td>n/a</td>
            <td>n/a</td>
            <td class="text-right">n/a</td>
            <td class="text-right">n/a</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

<!-- Documents -->
@include('documents.partials.documents', ['parentModel' => $shipment, 'modelName' => 'shipment'])


@if($shipment->alerts->count() > 0)

<h4 class="mb-2">Alerting <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->alerts->count()}}</span></h4>
<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Email</th>
            <th class="text-center">Despatched</th>
            <th class="text-center text-nowrap">Out For Delivery</th>
            <th class="text-center">Delivered</th>
            <th class="text-center">Cancelled</th>
            <th class="text-center">Other Notifications</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->alerts as $alert)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="mailto:{{$alert->email}}">{{$alert->email}}</a></td>

            <td class="text-center">
                @if($alert->despatched)
                @if($alert->despatched_sent_at)
                <span class="fas fa-check fa-lg mr-sm-2 text-success"></span> Email sent at {{$alert->despatched_sent_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                Email Requested
                @endif
                @else
                <span class="text-muted font-italic">Email not requested</span>
                @endif
            </td>

            <td class="text-center">
                @if($alert->out_for_delivery)
                @if($alert->out_for_delivery_sent_at)
                <span class="fas fa-check fa-lg mr-sm-2 text-success"></span> Email sent at {{$alert->out_for_delivery_sent_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                Email Requested
                @endif
                @else
                <span class="text-muted font-italic">Email not requested</span>
                @endif
            </td>

            <td class="text-center">
                @if($alert->delivered)
                @if($alert->delivered_sent_at)
                <span class="fas fa-check fa-lg mr-sm-2 text-success"></span> Email sent at {{$alert->delivered_sent_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                Email Requested
                @endif
                @else
                <span class="text-muted font-italic">Email not requested</span>
                @endif
            </td>

            <td class="text-center">
                @if($alert->cancelled)
                @if($alert->cancelled_sent_at)
                <span class="fas fa-check fa-lg mr-sm-2 text-success"></span> Email sent at {{$alert->cancelled_sent_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                Email Requested
                @endif
                @else
                <span class="text-muted font-italic">Email not requested</span>
                @endif
            </td>

            <td class="text-center">
                @if($alert->problems)
                @if($alert->problems_sent)
                Email sent: <i>{{$alert->problems_sent}}</i>
                @else
                Email Requested
                @endif
                @else
                <span class="text-muted font-italic">Email not requested</span>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endif


@if(Auth::user()->hasIfsRole() && $shipment->transportJobs->count() > 0)

<h4 class="mb-2">Transport Jobs <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->transportJobs->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Job Number</th>
            <th>Job Type</th>
            <th>Instructions</th>
            <th>Date</th>
            <th class="text-center">Route</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipment->transportJobs as $transportJob)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{url('/transport-jobs', $transportJob->id)}}">{{$transportJob->number}}</a></td>
            <td>{{($transportJob->type == 'c') ? 'Collection' : 'Delivery'}} Request</td>
            <td>{{$transportJob->instructions}}</td>
            <td>
                @if($transportJob->date_requested)
                {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format('jS M - Y')}}
                @endif
            </td>
            <td class="text-center">
                @if($transportJob->sent)
                <span class="badge badge-success">{{$transportJob->transend_route}}</span>
                @else
                <span class="badge badge-primary">{{$transportJob->transend_route}}</span>                                                
                @endif
            </td>
            <td class="text-center"><span class="status {{$transportJob->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$transportJob->status->description}}">{{$transportJob->status->name}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

@if(isset($shipment->quoted_array['costs_zone']))
@include('shipments.partials.cost_breakdown', ['quoted' => $shipment->quoted_array])
@endif

@endsection
