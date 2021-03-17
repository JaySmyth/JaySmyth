@extends('layouts.app')

@section('content')

dd("Blade");
    <div class="row">

        <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

            {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

            <div class="form-group">
                <label for="month">Date From</label>
                <input type="text" name="date_from"
                       value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif"
                       class="form-control datepicker" placeholder="Date from">
            </div>

            <div class="form-group">
                <label for="month">Date To</label>
                <input type="text" name="date_to"
                       value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif"
                       class="form-control datepicker" placeholder="Date To">
            </div>

            <div class="form-group">
                <label for="month">Service</label>
                {!! Form::select('service', dropDown('serviceCodes', 'All Services'), Request::get('service'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Carrier</label>
                {!! Form::select('carrier', dropDown('carriers', 'All Carriers'), Request::get('carrier'), array('class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label for="month">Shipper</label>
                {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Request::get('company'), array('class' => 'form-control')) !!}
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Report</button>

            {!! Form::Close() !!}

        </div>

        <main class="col-sm-10 ml-sm-auto" role="main">

            <h2>{{ $report->name }}</h2>

            <hr>

            <h4 class="mb-3">Total Shipments Delivered <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $total }}</span></h4>
            <table class="table table-striped table-bordered table-sm mb-5">
                <thead>
                <tr>
                    <th>Delay</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @foreach(['receiver', 'carrier', 'none'] as $delay)
                    <tr>
                        <td>{{ ucfirst($delay) }}</td>
                        <td>{{ isset($results[$delay]) ? count($results[$delay]) : 0 }}</td>
                        <td>{{ isset($results[$delay]) ? $results['percentages'][$delay] : 0 }}</td>
                        <td>
                            @if(isset($results[$delay]))
                                <a href="#{{ $delay }}">View</a>
                            @else
                                n/a
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

            @foreach([] as $delay)      <!-- ['receiver', 'carrier', 'unknown'] -->

                @if(isset($results[$delay]))
                    <h4 class="mb-3" id="{{ $delay }}">Delay - {{ ucfirst($delay) }} <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ count($results[$delay]) }}</span></h4>

                    <table class="table table-striped table-bordered table-sm mb-5">
                        <thead>
                        <tr>
                            <th>Consignment</th>
                            <th>Ship Date</th>
                            @if(Auth::user()->hasIfsRole())
                                <th>Shipper</th>
                            @else
                                <th>Reference</th>
                            @endif
                            <th>Postcode</th>
                            <th class="text-center">Transit</th>
                            <th class="text-center">SLA</th>
                            <th>Delay</th>
                            <th>Delivery Date</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($results[$delay] as $shipmentId)
                            <?php $shipment = App\Models\Shipment::find($shipmentId); ?>
                            <tr>
                                <td><a href="{{ url('/shipments', $shipmentId) }}"
                                       class="consignment-number">{{$shipment->consignment_number}}</a></td>
                                <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
                                @if(Auth::user()->hasIfsRole())
                                    <td>
                                        <a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a>
                                    </td>
                                @else
                                    <td>{{$shipment->shipment_reference}}</td>
                                @endif
                                <td>{{ $shipment->recipient_postcode }}</td>
                                <td class="text-center">{{$shipment->timeInTransit}}<span class="hours">hrs</span></td>
                                <td class="text-center">{{$shipment->getSla() }}<span class="hours">hrs</span></td>
                                <td class="text-capitalize">{{$shipment->getDelay()}}</td>
                                <td>{{$shipment->getDeliveryDate(Auth::user()->date_format . ' g:ia')}}</td>
                                <td class="text-center"><a href="{{ url('/tracking/' . $shipment->token) }}"
                                                           title="IFS Tracking Link" target="_blank">Track</a></td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                @endif



            @endforeach

        </main>

    </div>

@endsection
