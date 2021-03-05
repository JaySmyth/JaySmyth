@extends('layouts.app')

@section('navSearchPlaceholder', 'Consignment search...')

@section('advanced_search_form')

    <input type="hidden" name="mode" value="{{Request::get('mode')}}">

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Consignment or Reference:
        </label>
        <div class="col-sm-8">
            {!! Form::Text('filter', Request::get('filter'), ['class' => 'form-control', 'maxlength' => '50', 'placeholder' => 'e.g. "10005149611" or "myref123"']) !!}
        </div>
    </div>

    @if(Auth::user()->hasIfsRole())
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                SCS Job Number:
            </label>
            <div class="col-sm-8">
                {!! Form::Text('scs_job_number', Request::get('scs_job_number'), ['class' => 'form-control', 'maxlength' => '14', 'placeholder' => 'e.g. "IFCUKJ00783733"']) !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Manifest Number:
            </label>
            <div class="col-sm-8">
                {!! Form::Text('manifest_number', Request::get('manifest_number'), ['class' => 'form-control', 'maxlength' => '14', 'placeholder' => 'e.g. "FDXI0003192"']) !!}
            </div>
        </div>
    @endif

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Date From / To:
        </label>

        @if(Auth::user()->hasIfsRole())
            <div class="col-sm-4">
                <input type="text" name="date_from" value="@if(!Request::get('date_from')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif" class="form-control datepicker" maxlength="10">
            </div>
            <div class="col-sm-4">
                <input type="text" name="date_to" value="@if(!Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif" class="form-control datepicker" maxlength="10">
            </div>
        @else
            <div class="col-sm-4">
                <input type="text" name="date_from" value="{{Request::get('date_from')}}" class="form-control datepicker" maxlength="10" placeholder="Date From">
            </div>
            <div class="col-sm-4">
                <input type="text" name="date_to" value="{{Request::get('date_to')}}" class="form-control datepicker" maxlength="10" placeholder="Date To">
            </div>
        @endif

    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Shipper:
        </label>
        <div class="col-sm-8">
            {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Request::get('company'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Status:
        </label>
        <div class="col-sm-8">
            {!! Form::select('status', dropDown('statuses', 'All Statuses'), Request::get('status'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Service:
        </label>
        <div class="col-sm-8">
            {!! Form::select('service',  dropDown('uniqueServices', 'All Services'), Request::get('service'), array('class' => 'form-control')) !!}
        </div>
    </div>

    @if(Auth::user()->hasIfsRole())
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Carrier:
            </label>
            <div class="col-sm-8">
                {!! Form::select('carrier',  dropDown('carriers', 'All Carriers'), Request::get('carrier'), array('class' => 'form-control')) !!}
            </div>
        </div>
    @endif

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Source / Destination:
        </label>
        <div class="col-sm-4">
            {!! Form::select('source',  dropDown('countries', 'All Sender Countries'), Request::get('source'), array('class' => 'form-control')) !!}
        </div>
        <div class="col-sm-4">
            {!! Form::select('destination',  dropDown('countries', 'All Recipient Countries'), Request::get('destination'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Recip. Name or Address:
        </label>
        <div class="col-sm-8">
            {!! Form::Text('recipient_filter', Request::get('recipient_filter'), ['class' => 'form-control', 'maxlength' => '80', 'placeholder' => 'e.g. "Mark Smith" or "BT4"']) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Recipient Address Type:
        </label>
        <div class="col-sm-8">
            {!! Form::select('recipient_type',  dropDown('type', 'Commercial and Residential Addresses'), Request::get('recipient_type'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Pieces:
        </label>
        <div class="col-sm-8">
            {!! Form::select('pieces', ['' => 'Single and Multi-Piece', 1 => 'Single Piece', '2' => 'Multi-Piece'], Request::get('pieces'), array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Traffic:
        </label>
        <div class="col-sm-8">
            {!! Form::select('traffic', dropDown('traffic', 'All Traffic'), Request::get('traffic'), array('class' => 'form-control')) !!}
        </div>
    </div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@section('toolbar')
    @can('download_shipments')
        <a href="shipments/download?{{Request::getQueryString()}}" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>@endcan
    <a href="shipments/collection-manifest?{{Request::getQueryString()}}" title="Download Collection Manifest"><span class="fas fa-list-ol fa-lg ml-sm-3" aria-hidden="true"></span></a>
    <a href="shipments/batched-shipping-docs/invoice?{{Request::getQueryString()}}" title="Download Commercial Invoices"><span class="fas fa-list-alt fa-lg ml-sm-3" aria-hidden="true"></span></a>
    @if(Auth::user()->hasIfsRole())
        <a href="shipments/batched-shipping-docs/customs?{{Request::getQueryString()}}" title="Download Customs Document Set"><span class="far fa-file-alt fa-lg ml-sm-3" aria-hidden="true"></span></a>
    @endif
    <a href="shipments/batched-shipping-docs/master?{{Request::getQueryString()}}" title="Download Master Labels"><span class="fas fa-print fa-lg ml-sm-3" aria-hidden="true"></span></a>
    <a href="shipments/batched-shipping-docs/package?{{Request::getQueryString()}}" title="Download Package Labels"><span class="fas fa-cube fa-lg ml-sm-3" aria-hidden="true"></span></a>

    @if(Auth::user()->hasIfsRole())
        <a href="shipments/batched-shipping-docs/all?{{Request::getQueryString()}}" title="Download All Shipping Documents"><span class="far fa-copy fa-lg ml-sm-3" aria-hidden="true"></span></a>
    @endif

@endsection

@include('partials.title', ['title' => 'shipment history', 'results'=> $shipments])

<table class="table table-striped">
    <thead>
    <tr>
        <th>Consignment</th>
        <th>Carrier Ref</th>
        @if(Auth::user()->hasRole('ifsf'))
            <th>SCS Job</th>@endif
        @if(Auth::user()->hasIfsRole() && Auth::user()->hasMultipleDepots())
            <th class="text-center">Depot</th>@endif

        @if(Auth::user()->hasIfsRole())
            <th>Destination</th>
            <th>Shipper</th>

        @else
            <th>Recipient</th>
            <th>Reference</th>
        @endif

        <th>Ship Date</th>
        <th class="text-center">Service</th>
        <th class="text-center">Pieces</th>
        <th class="text-center">Weight
            <span class="fas fa-info-circle" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Chargeable weight - largest value from weight or volumetric weight"></span>
        </th>
        <th class="text-center">Transit</th>
        <th class="text-center">Status</th>
        <th class="text-center">&nbsp;</th>
    </tr>
    </thead>
    <tbody>

    @foreach($shipments as $shipment)

        @if($shipment->isHighlighted() && Auth::user()->hasIfsRole())
            <tr class="table-warning">
        @else
            <tr>
                @endif

                <td>
                    @if(isset($shipment->status->code) && $shipment->status->code == 'saved')
                        <a href="{{url('/shipments/' . $shipment->id . '/edit') }}" class="consignment-number">{{$shipment->consignment_number}}</a>
                    @else
                        <a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a>
                    @endif
                </td>

                <td>
                    @if(isset($shipment->status->code) && $shipment->status->code == 'saved')
                        <span class="text-muted"><i>Unknown</i></span>
                    @else
                        {{$shipment->carrier_consignment_number ?? ''}}
                    @endif
                </td>

                @if(Auth::user()->hasRole('ifsf'))
                    <td>
                        @if($shipment->scs_job_number)
                            {{$shipment->scs_job_number}}
                        @else
                            <span class="text-muted"><i>Unknown</i></span>
                        @endif
                    </td>
                @endif

                @if(Auth::user()->hasIfsRole() && Auth::user()->hasMultipleDepots())
                    <td class="text-center">
                        <span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->depot->name ?? 'Unknown'}}">{{$shipment->depot->code ?? ''}}</span>
                    </td>@endif

                @if(Auth::user()->hasIfsRole())
                    <td class="text-truncate">{{$shipment->recipient_city}}
                        <span class="ml-sm-2">{{$shipment->recipient_country_code}}</span></td>
                    <td class="text-truncate">
                        <a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a>
                    </td>
                @else
                    <td class="text-truncate">
                        @if($shipment->recipient_company_name || $shipment->recipient_name)
                            {{$shipment->recipient_company_name ?: $shipment->recipient_name}},
                        @else
                            <span class="text-muted"><i>Unknown</i></span>
                        @endif

                        @if($shipment->recipient_city || $shipment->recipient_country_code)
                            {{$shipment->recipient_city}}, {{$shipment->recipient_country_code}}
                        @endif
                    </td>
                    <td>
                        @if($shipment->shipment_reference)
                            {{$shipment->shipment_reference}}
                        @else
                            <span class="text-muted"><i>Unknown</i></span>
                        @endif
                    </td>
                @endif

                <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
                <td class="text-center">
                    @if(isset($shipment->status->code) && $shipment->status->code == 'saved')
                        <span class="text-muted"><i>Unknown</i></span>
                    @else
                        <span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span>
                    @endif
                </td>
                <td class="text-center">{{$shipment->pieces}}</td>
                <td class="text-center">{{$shipment->chargeable_weight}} {{$shipment->weight_uom}}</td>
                <td class="text-center">
                    @if($shipment->timeInTransit == 0 && $shipment->delivered)
                        <span class="text-muted">n/a</span>
                    @else
                        {{$shipment->timeInTransit}}<span class="hours">hrs</span>
                    @endif
                </td>
                <td class="text-center text-nowrap">
                    <span class="status {{$shipment->status->code ?? ''}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->status->description}}">{{$shipment->status->name}}</span>
                </td>
                <td class="text-center text-nowrap">
                    <!-- Cancel shipment - only enable if active shipment and not yet received -->
                    @if($shipment->isCancellable() || Auth::user()->hasIfsRole())
                        <a href="{{ url('/shipments/' . $shipment->id . '/cancel') }}" title="Cancel Shipment" class="cancel-shipment"><span class="fas fa-times ml-sm-2" aria-hidden="true"></span></a>
                    @else
                        <span class="fas fa-times ml-sm-2 faded" aria-hidden="true" title="Cancel Shipment (unavailable)"></span>
                    @endif

                    @if(Auth::user()->hasIfsRole())
                        @if($shipment->isResetable())
                            <a href="{{ url('/shipments/' . $shipment->id . '/reset') }}" title="Reset Shipment"><span class="fas fa-backspace ml-sm-2" aria-hidden="true"></span></a>
                        @else
                            <span class="fas fa-backspace ml-sm-2 faded" aria-hidden="true" title="Reset Shipment (unavailable)"></span>
                        @endif
                    @endif

                <!-- Supporting documents - only enable if active shipment -->
                    @if($shipment->formViewAvailable())
                        <a href="{{ url('/shipments/' . $shipment->id . '/form-view') }}" title="Form View"><span class="fas fa-window-restore ml-sm-2" aria-hidden="true"></span></a>
                    @else
                        <span class="fas fa-window-restore ml-sm-2 faded" aria-hidden="true" title="Form View (unavailable)"></span>
                    @endif

                <!-- Commercial invoice download - only enable if courier or air export -->
                    @if($shipment->hasCommercialInvoice() && !$shipment->legacy)
                        <a href="{{ url('/commercial-invoice', $shipment->token) }}" title="Print Commercial Invoices" class="commercial-invoice"><span class="fas fa-list-alt ml-sm-2" aria-hidden="true"></span></a>
                    @else
                        <span class="fas fa-list-alt ml-sm-2 faded" aria-hidden="true" title="Commercial Invoice (unavailable)"></span>
                    @endif

                <!-- Only enable the print labels icon if the shipment has not yet been collected OR an IFS user -->
                    @if(isset($shipment->status->code))
                        @if($shipment->status->code == 'pre_transit' || Auth::user()->hasIfsRole() && $shipment->status->code != 'saved')
                            <a href="{{$shipment->print_url}}" title="Print Label" class="print"><span class="fas fa-print ml-sm-2" aria-hidden="true"></span></a>
                        @else
                            <span class="fas fa-print ml-sm-2 faded" aria-hidden="true" title="Print Label (unavailable)"></span>
                        @endif
                    @endif
                </td>

            </tr>

            @endforeach

    </tbody>
</table>

@include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments])
@include('partials.pagination', ['results'=> $shipments])

@endsection
