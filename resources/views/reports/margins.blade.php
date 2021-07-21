@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

            {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

            <div class="form-group">
                <label for="date_from">Date From</label>
                <input type="text" name="date_from"
                       value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif"
                       class="form-control datepicker" placeholder="Date from">
            </div>
            <div class="form-group">
                <label for="date_to">Date To</label>
                <input type="text" name="date_to"
                       value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif"
                       class="form-control datepicker" placeholder="Date To">
            </div>

            <div class="form-group">
                <label for="month">Traffic</label>
                {!! Form::select('traffic', dropDown('traffic', 'All Traffic'), Request::get('traffic'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Carrier</label>
                {!! Form::select('carrier', dropDown('carriers', 'All Carriers'), Request::get('carrier'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Depot</label>
                {!! Form::select('depot', dropDown('associatedDepots', 'All Depots'), Request::get('depot'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Service</label>
                {!! Form::select('service', dropDown('services', 'All Services'), Request::get('service'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Shipper</label>
                {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Request::get('company'), array('class' => 'form-control')) !!}
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Report</button>

            {!! Form::Close() !!}

        </div>

        <main class="col-sm-10 ml-sm-auto" role="main">

            @include('partials.title', ['title' => $report->name, 'results'=> $shipments])

            <table class="table table-striped table-bordered table-sm">
                <thead>
                <tr class="success">
                    <th>Consignment</th>
                    <th>Service</th>
                    <th>Shipper</th>
                    <th>Account</th>
                    <th class="text-right">Pieces</th>
                    <th class="text-right">Weight</th>
                    <th class="text-center">Cost Zone</th>
                    <th class="text-center">Sales Zone</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">Sales</th>
                    <th class="text-right">Profit/Loss</th>
                    <th class="text-right">Margin</th>
                </tr>
                </thead>
                <tbody>

                @foreach($shipments as $shipment)

                    @if(stristr($shipment->margin_styling_class, 'danger'))
                        <tr class="text-nowrap table-danger">
                    @else
                        <tr class="text-nowrap">
                            @endif

                            <td>
                                <a href="{{ url('/shipments', $shipment->id) }}"
                                   class="consignment-number">{{$shipment->carrier_consignment_number}}</a>
                            </td>
                            <td class="text-truncate">{{$shipment->service->carrier_name}}</td>
                            <td class="text-truncate">
                                <a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a>
                            </td>
                            <td class="text-uppercase">
                                @if($shipment->bill_shipping_account)
                                    {{$shipment->bill_shipping_account}}
                                @else
                                    <span class="text-muted">null</span>
                                @endif
                            </td>
                            <td class="align-middle text-right">{{$shipment->pieces}}</td>
                            <td class="text-right">{{$shipment->chargeable_weight}} {{$shipment->weight_uom}}</td>
                            <td class="text-center text-uppercase">
                                @if(is_array($shipment->quoted_array) && isset($shipment->quoted_array['costs_zone']))

                                    @if(isset($shipment->quoted_array['costs_model']))
                                        {{$shipment->quoted_array['costs_model']}}
                                    @endif

                                    {{$shipment->quoted_array['costs_zone']}}

                                @else
                                    <span class="text-muted">null</span>
                                @endif

                            </td>

                            <td class="text-center text-uppercase">
                                @if(is_array($shipment->quoted_array) && isset($shipment->quoted_array['sales_zone']))
                                    @if(isset($shipment->quoted_array['sales_model']))
                                        {{$shipment->quoted_array['sales_model']}}
                                    @endif

                                    {{$shipment->quoted_array['sales_zone']}}
                                @else
                                    <span class="text-muted">null</span>
                                @endif
                            </td>

                            @if($shipment->bill_shipping == 'sender')
                                <td class="text-right" title="Cost">
                                    {{number_format($shipment->shipping_cost, 2)}}
                                </td>
                                <td class="text-right" title="Sales">
                                    {{number_format($shipment->shipping_charge, 2)}}
                                </td>
                                <td class="text-right" title="Profit/Loss">
                                    <span class="{{$shipment->margin_styling_class}}">{{$shipment->profit_formatted}}</span>
                                </td>
                                <td class="text-right" title="Margin">
                                    <span class="{{$shipment->margin_styling_class}}">{{$shipment->margin}}</span>
                                </td>
                            @else
                                <td class="text-right" title="Cost">&nbsp;</td>
                                <td class="text-right" title="Sales">&nbsp;</td>
                                <td class="text-right" title="Profit/Loss"><span class="text-primary">0.00</span></td>
                                <td class="text-right" title="Margin"><span class="text-primary">n/a</span>
                                </td>
                            @endif
                        </tr>
                        @endforeach

                </tbody>
            </table>

            @include('partials.no_results', ['title' => 'shippers', 'results'=> $shipments])
            @include('partials.pagination', ['results'=> $shipments])

        </main>
    </div>

@endsection
