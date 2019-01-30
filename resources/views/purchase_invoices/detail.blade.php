@extends('layouts.app')

@section('content')

@include('purchase_invoices.partials.title', ['purchaseInvoice'=> $purchaseInvoice, 'small' => 'Detail View'])

<h4>Invoice Lines <span class="badge badge-pill badge-sm badge-secondary">{{$purchaseInvoice->lines->count()}}</span></h4>

<div class="clearfix"></div>

@foreach($purchaseInvoice->lines->sortBy('type') as $line)

<div class="card mb-4">

    <div class="card-header text-uppercase">

        <span id="line-{{$line->id}}">{{$loop->iteration}}.</span> <strong>{{$line->type}}</strong>

        <div class="float-right">
            @if($line->shipment)
            <a href="{{ url('/companies', $line->shipment->company_id) }}">{{$line->shipment->company->company_name}}</a>
            @else
            {{$line->sender_company_name}}
            @endif
        </div>
    </div>

    <div class="card-body pb-0">
        <div class="row mb-4">
            <div class="col-sm-5 text-large margin-bottom-15">
                <div class="row">
                    <div class="col-sm-5"><strong>Consignment</strong></div>
                    <div class="col-sm-7">
                        @if($line->shipment_id)
                        <a href="{{ url('/shipments', $line->shipment->id) }}" class="consignment-number">{{$line->carrier_tracking_number}}</a>
                        @elseif($line->carrier_tracking_number)
                        {{$line->carrier_tracking_number}}
                        @else
                        Unknown
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Reference</strong></div>
                    <div class="col-sm-7">{{$line->shipment_reference}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>SCS Job Number</strong></div>
                    <div class="col-sm-7">{{$line->scs_job_number ?? 'Unknown'}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Pieces</strong></div>
                    <div class="col-sm-7">{{$line->pieces}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Weight</strong></div>
                    <div class="col-sm-7">{{$line->weight}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Billed Weight</strong></div>
                    <div class="col-sm-7">
                        @if($line->billed_weight > $line->weight)
                        <span class="text-danger">{{$line->billed_weight}}</span>
                        @else
                        {{$line->billed_weight}}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Service</strong></div>
                    <div class="col-sm-7">{{$line->service}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Packaging Type</strong></div>
                    <div class="col-sm-7">{{$line->packaging_type}}</div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Ship Date</strong></div>
                    <div class="col-sm-7">
                        @if($line->ship_date)
                        {{$line->ship_date->format(Auth::user()->date_format)}}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5"><strong>Delivery Date</strong></div>
                    <div class="col-sm-7">
                        @if($line->delivery_date)
                        {{$line->delivery_date->format(Auth::user()->date_format)}}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-7 text-large">
                <div class="row">
                    <br>

                    @if($line->sender_name || $line->sender_company_name || $line->sender_city)
                    <div class="col-sm-5">
                        <span class="fas fa-user" aria-hidden="true"></span> <strong class="ml-sm-3 text-large">Sender</strong><br>

                        @if($line->sender_name)
                        {{$line->sender_name}}<br>
                        @endif

                        @if($line->sender_company_name)
                        {{$line->sender_company_name}}<br>
                        @endif

                        @if($line->sender_address1)
                        {{$line->sender_address1}}<br>
                        @endif

                        @if($line->sender_address2)
                        {{$line->sender_address2}}<br>
                        @endif

                        @if($line->sender_address3)
                        {{$line->sender_address3}}<br>
                        @endif

                        @if($line->sender_city)
                        {{$line->sender_city}}<br>
                        @endif

                        @if($line->sender_state || $line->sender_postcode)
                        {{$line->sender_state}} {{$line->sender_postcode}}<br>
                        @endif

                        <strong>{{$line->sender_country_code}}</strong><br>
                    </div>

                    @endif

                    @if($line->recipient_name || $line->recipient_company_name || $line->recipient_city || $line->recipient_country_code)

                    <div class="col-sm-2"><br><span class="fas fa-chevron-right" aria-hidden="true"></span></div>

                    <div class="col-sm-5">
                        <span class="fas fa-user" aria-hidden="true"></span> <strong class="ml-sm-3 text-large">Recipient</strong><br>

                        @if($line->recipient_name)
                        {{$line->recipient_name}}<br>
                        @endif

                        @if($line->recipient_company_name)
                        {{$line->recipient_company_name}}<br>
                        @endif

                        @if($line->recipient_address1)
                        {{$line->recipient_address1}}<br>
                        @endif

                        @if($line->recipient_address2)
                        {{$line->recipient_address2}}<br>
                        @endif

                        @if($line->recipient_address3)
                        {{$line->recipient_address3}}<br>
                        @endif

                        @if($line->recipient_city)
                        {{$line->recipient_city}}<br>
                        @endif

                        {{$line->recipient_state}} {{$line->recipient_postcode}}<br>
                        <strong>{{$line->recipient_country_code}}</strong><br>

                        @if(!$line->recipient_address2)<br>@endif

                        @if(!$line->recipient_address3)<br>@endif

                    </div>

                    @endif

                </div>
            </div>
        </div>


        <table class="table table-striped table-sm">
            <thead>
                <tr class="bg-info">
                    <th>Code</th>
                    <th>Description</th>
                    <th>SCS</th>
                    <th>Exchange Rate</th>
                    <th>Original</th>
                    <th>Billed</th>
                    @if($purchaseInvoice->carrier->code != 'fedex')<th>VAT</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($line->charges as $charge)
                <tr>
                    <td>{{$charge->code}}</td>
                    <td>{{$charge->carrierChargeCode->description ?? $charge->description}}</td>
                    <td>{{$charge->carrierChargeCode->scs_code ?? 'UNKNOWN CHARGE TYPE'}}</td>
                    <td>{{$charge->exchange_rate}}</td>
                    <td>{{$charge->amount}} {{$charge->currency_code}}</td>
                    <td>{{$charge->billed_amount}} {{$charge->billed_amount_currency_code}}</td>
                    @if($purchaseInvoice->carrier->code != 'fedex')<td>{{number_format($charge->vat, 2)}} {{$charge->currency_code}}</td>@endif
                </tr>
                @endforeach
                @if($line->charges->count() > 0)
                <tr class="text-large bg-secondary text-white">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>{{$line->total}} {{$charge->billed_amount_currency_code}}</strong></td>
                    @if($purchaseInvoice->carrier->code != 'fedex')<td><strong>{{number_format($line->total_vat, 2)}} {{$charge->billed_amount_currency_code}}</strong></td>@endif
                </tr>
                @endif
            </tbody>
        </table>

    </div>
</div>

@endforeach


@endsection