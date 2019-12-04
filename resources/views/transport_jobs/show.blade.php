@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">
        {{verboseCollectionDelivery($transportJob->type)}} Request <span class="text-small ml-sm-3">{{$transportJob->number}}</span>              
    </h2>
    <h2 class="float-right">
        <span class="badge badge-{{$transportJob->status->code}}">{{$transportJob->status->name}}</span>
    </h2>
</div>

<div class="row mb-4">

    <div class="col-sm-7">

        <div class="card h-100">
            <div class="card-header">Address Details</div>
            <div class="card-body text-large">
                <div class="row">
                    <div class="col-sm-5">

                        <h5>From Address                            
                            @if($transportJob->from_type == 'c')
                            <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($transportJob->from_type)}}"></span>
                            @else
                            <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($transportJob->from_type)}}"></span>
                            @endif
                        </h5>


                        {{$transportJob->from_name}}<br>
                        {{$transportJob->from_company_name}}<br>
                        {{$transportJob->from_address1}}<br>

                        @if($transportJob->from_address2)
                        {{$transportJob->from_address2}}<br>
                        @endif

                        @if($transportJob->from_address3)
                        {{$transportJob->from_address3}}<br>
                        @endif

                        {{$transportJob->from_city}}<br>
                        {{$transportJob->from_state}} {{$transportJob->from_postcode}}<br>
                        <strong>{{$transportJob->from_country}}</strong><br>

                        @if(!$transportJob->from_address2)<br>@endif

                        @if(!$transportJob->from_address3)<br>@endif

                        @if($transportJob->from_telephone)
                        Tel: {{$transportJob->from_telephone}}<br>
                        @endif

                        @if($transportJob->from_email)
                        <a href="mailto:{{$transportJob->from_email}}">{{$transportJob->from_email}}</a>
                        @endif


                    </div>

                    <div class="col-sm-2 pt-4">                        
                        <span class="chevron fa fa-chevron-right pt-4 mt-4" aria-hidden="true"></span>
                    </div>

                    <div class="col-sm-5">

                        <h5>To Address                            
                            @if($transportJob->to_type == 'c')
                            <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($transportJob->to_type)}}"></span>
                            @else
                            <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{getAddressType($transportJob->to_type)}}"></span>
                            @endif
                        </h5>

                        @if($transportJob->to_name)
                        {{$transportJob->to_name}}<br>
                        @endif

                        {{$transportJob->to_company_name}}<br>

                        @if($transportJob->to_address1)
                        {{$transportJob->to_address1}}<br>
                        @endif

                        @if($transportJob->to_address2)
                        {{$transportJob->to_address2}}<br>
                        @endif

                        @if($transportJob->to_address3)
                        {{$transportJob->to_address3}}<br>
                        @endif

                        {{$transportJob->to_city}}<br>
                        {{$transportJob->to_state}} {{$transportJob->to_postcode}}<br>
                        <strong>{{$transportJob->to_country}}</strong><br>

                        @if(!$transportJob->to_address2)<br>@endif

                        @if(!$transportJob->to_address3)<br>@endif

                        @if($transportJob->to_telephone)
                        Tel: {{$transportJob->to_telephone}}<br>
                        @endif

                        @if($transportJob->to_email)
                        <a href="mailto:{{$transportJob->to_email}}">{{$transportJob->to_email}}</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-5">
        <div class="card h-100">
            <div class="card-header">Details</div>

            <div class="card-body text-large">

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Date / Time Created</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->created_at)
                        {{$transportJob->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Date Requested</strong></div>
                    <div class="col-sm-7">{{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Customer Reference</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->shipment)
                        <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                        @else
                        {{$transportJob->reference}}
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>SCS Job Number</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->shipment)

                        @if($transportJob->shipment->scs_job_number)
                        {{$transportJob->shipment->scs_job_number}}
                        @else
                        <i>Unknown</i>
                        @endif

                        @elseif($transportJob->scs_job_number)
                        {{$transportJob->scs_job_number}}
                        @else
                        <i>Unknown</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>POD Signature</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->completed)
                        @if($transportJob->pod_signature)

                        @if($transportJob->pod_image)
                        <a tabindex="0" class="" role="button" data-toggle="img-popover" data-trigger="focus" title="POD Signature" data-placement="left" data-img="{{$transportJob->pod_image}}" data-style="image-fluid pod-image">{{$transportJob->pod_signature}}</a>
                        @else
                        {{$transportJob->pod_signature}}
                        @endif

                        @else
                        n/a
                        @endif
                        @else

                        <i>Awaiting Completion</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>POD Date / Time</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->date_completed)
                        {{$transportJob->date_completed->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                        @else
                        <i>Awaiting Completion</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Route</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->sent)
                        <span class="badge badge-success">{{$transportJob->transend_route}}</span>
                        @else
                        <span class="badge badge-primary">{{$transportJob->transend_route}}</span>                                                
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>TranSend Status</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->sent)
                            Sent ({{$transportJob->attempts}} additional attempts)
                        @else
                            Not sent
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <div class="card h-100">
            <div class="card-header">Instructions / Additional Information</div>
            <div class="card-body text-large">
                @if($transportJob->instructions)
                <p class="text-large">{{$transportJob->instructions}}</p>
                @endif

                @if($transportJob->shipment)
                <br>
                <p>Courier Shipment ({{strtoupper($transportJob->shipment->service->code)}}): <b><a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a></b></p>
                <p>Final destination: <b>{{$transportJob->shipment->recipient_city}}, {{$transportJob->shipment->recipient_country}}</b></p>

                @else

                @if($transportJob->department)
                <p>Department: <b>{{$transportJob->department->name}}</b></p>
                @endif

                @if($transportJob->final_destination)
                <p>Final destination: <b>{{$transportJob->final_destination}}</b></p>
                @endif
                @endif

                @if($transportJob->type == 'c' && $transportJob->date_requested)
                <p><b>Collection requested for {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format('H:i')}}</b></p>
                @endif

                @if($transportJob->closing_time)
                <p><b>Customer premises closes at {{$transportJob->closing_time}}</b></p>
                @endif

            </div>
        </div>
    </div>
    <div class="col-sm-5">

        <div class="card">
            <div class="card-header">Summary</div>
            <div class="card-body text-large">

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Pieces</strong></div>
                    <div class="col-sm-7">{{$transportJob->pieces}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Weight</strong></div>
                    <div class="col-sm-7">{{$transportJob->weight}} kg</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Volumetric Weight</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->volumetric_weight)
                        {{$transportJob->volumetric_weight}} kg
                        @else
                        <i>Unknown</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Goods Description</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->goods_description)
                        {{$transportJob->goods_description}}
                        @else
                        <i>Unknown</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Dimensions (cm)</strong></div>
                    <div class="col-sm-7">
                        @if($transportJob->dimensions)
                        {{$transportJob->dimensions}}
                        @else
                        <i>Unknown</i>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-5"><strong>Cash On {{verboseCollectionDelivery($transportJob->type)}}</strong></div>
                    <div class="col-sm-7">£{{$transportJob->cash_on_delivery}}</div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('partials.log', ['logs' => $transportJob->logs])

@endsection
