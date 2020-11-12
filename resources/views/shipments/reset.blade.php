@extends('layouts.app')

@section('content')

    <div class="clearfix">
        <h2 class="float-left">Reset Shipment - {{$shipment->consignment_number}}
            <small class="text-muted ml-sm-3">
                {{ $shipment->service->name }} ({{strtoupper($shipment->carrier->code)}})
            </small>
        </h2>
        <h2 class="float-right"><span class="badge badge-{{$shipment->status->code}}">{{$shipment->status->name}}</span>
        </h2>
    </div>

    <hr>

    <form action="{{ url('shipments/' . $shipment->id . '/reset') }}" method="post" autocomplete="off">

        @csrf

        <input type="hidden" name="redirect" value="{{ $referer }}">

        <div class="row justify-content-between">

            <div class="col-5">

                <div class="form-group row{{ $errors->has('service') ? ' has-danger' : '' }}">

                    <label class="col-sm-4  col-form-label">
                        Service: <abbr title="This information is required.">*</abbr>
                    </label>

                    <div class="col-sm-6">
                        {!! Form::select('service',  [27 => 'DHL Worldwide Express (NonDoc)', 76 => 'XDP UK48', 19 => 'FedEx UK'], old('service'), array('class' => 'form-control')) !!}

                        @if ($errors->has('service'))
                            <span class="form-text"><strong>{{ $errors->first('service') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('reprice') ? ' has-danger' : '' }} mb-4">

                    <label class="col-sm-4  col-form-label">
                        Reprice Shipment: <abbr title="This information is required.">*</abbr>
                    </label>

                    <div class="col-sm-6">
                        {!! Form::select('reprice',  ['' => 'Please select', 0 => 'Keep original sales', 1 => 'Recalculate sales'], old('reprice'), array('class' => 'form-control')) !!}

                        @if ($errors->has('reprice'))
                            <span class="form-text"><strong>{{ $errors->first('reprice') }}</strong></span>
                        @endif
                    </div>
                </div>


                <h6 class="mt-4">Package Dimensions and Weights</h6>
                <table class="table table-striped table-bordered table-sm mb-5">
                    <thead>
                    <tr>
                        <th class="text-center">Length(cm)</th>
                        <th class="text-center">Width(cm)</th>
                        <th class="text-center">Height(cm)</th>
                        <th class="text-center">Weight(kg)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="align-middle text-center">
                            @foreach ($shipment->packages as $package)
                                <input type="text" name="packages[{{ $package->index }}][length]"
                                       class="form-control form-control-sm numeric-only-required"
                                       value="{{ $package->length }}" placeholder="{{ $package->index }} Length(cm)">
                            @endforeach
                        </td>
                        <td class="align-middle text-center">
                            @foreach ($shipment->packages as $package)
                                <input type="text" name="packages[{{ $package->index }}][width]"
                                       class="form-control form-control-sm numeric-only-required"
                                       value="{{ $package->width }}" placeholder="{{ $package->index }} Width(cm)">
                            @endforeach
                        </td>
                        <td class="align-middle text-center">
                            @foreach ($shipment->packages as $package)
                                <input type="text" name="packages[{{ $package->index }}][height]"
                                       class="form-control form-control-sm numeric-only-required"
                                       value="{{ $package->height }}" placeholder="{{ $package->index }} Height(cm)">
                            @endforeach
                        </td>
                        <td class="align-middle text-center">
                            @foreach ($shipment->packages as $package)
                                <input type="text" name="packages[{{ $package->index }}][weight]"
                                       class="form-control form-control-sm decimal-only-required"
                                       value="{{ $package->weight }}" placeholder="{{ $package->index }} Weight(kg)">
                            @endforeach
                        </td>

                    </tr>

                    </tbody>
                </table>

                <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary ml-sm-4">Reset Shipment</button>

            </div>

            <div class="col-6">
                <div class="card mb-5">

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
                                        <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right"
                                              data-toggle="tooltip"
                                              data-original-title="{{getAddressType($shipment->sender_type)}}"></span>
                                    @else
                                        <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right"
                                              data-toggle="tooltip"
                                              data-original-title="{{getAddressType($shipment->sender_type)}}"></span>
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
                                        <span class="far fa-building ml-sm-2" aria-hidden="true" data-placement="right"
                                              data-toggle="tooltip"
                                              data-original-title="{{getAddressType($shipment->recipient_type)}}"></span>
                                    @else
                                        <span class="fas fa-user ml-sm-2" aria-hidden="true" data-placement="right"
                                              data-toggle="tooltip"
                                              data-original-title="{{getAddressType($shipment->recipient_type)}}"></span>
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
                                    <a class="text-truncate"
                                       href="mailto:{{$shipment->recipient_email}}">{{$shipment->recipient_email}}</a>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>


                <h4 class="mb-2">Packages
                    <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $shipment->packages->count()}}</span>
                </h4>
                <table class="table table-striped table-bordered mb-5">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-right">Length</th>
                        <th class="text-right">Width</th>
                        <th class="text-right">Height</th>
                        <th class="text-right">Weight</th>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </form>


@endsection
