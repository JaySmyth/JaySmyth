@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="month">Date From</label>
            <input type="text" name="date_from" value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>

        <div class="form-group">
            <label for="month">Date To</label>
            <input type="text" name="date_to" value="@if(!Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            {!! Form::select('status', dropDown('statuses', 'All Statuses'), Request::get('status'), array('class' => 'form-control')) !!}
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

        @if(Request::get('date_from'))
        @section('toolbar')
        <a href="{{ url('/shipments/download-dims?' . Request::getQueryString()) }}" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>
        @endsection
        @endif

        @include('partials.title', ['title' => $report->name, 'results'=> $shipments])

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Consignment</th>
                    <th>Carrier Ref</th>
                    <th>Shipper</th>
                    <th class="text-center">Dest.</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Pieces</th>
                    <th class="text-right">Weight</th>
                    <th class="text-right">Volumetric</th>
                    <th class="text-center">Packages</th>
                    <th class="text-center">Reset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shipments as $shipment)
                @if($shipment->company)
                <tr>
                    <th scope="row" class="align-middle">{{$loop->iteration}}</th>
                    <td class="align-middle"><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
                    <td class="align-middle">{{$shipment->carrier_consignment_number}}</td>

                    <td class="align-middle"><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>
                    <td class="align-middle text-center">{{$shipment->recipient_country_code}}</td>
                    <td class="align-middle text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
                    <td class="align-middle text-center"><span class="status {{$shipment->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->status->description}}">{{$shipment->status->name}}</span></td>
                    <td class="align-middle text-right">{{$shipment->pieces}}</td>
                    <td class="align-middle text-right">{{$shipment->weight}}</td>
                    <td class="align-middle text-right">{{$shipment->volumetric_weight}}</td>
                    <td class="align-middle text-center">
                        @foreach ($shipment->packages as $package)
                        {{$package->length}}{{$shipment->dims_uom}} x {{$package->width}}{{$shipment->dims_uom}} x {{$package->height}}{{$shipment->dims_uom}}<br>
                        @endforeach
                    </td>
                    <td class="align-middle text-center">
                        @if(Auth::user()->hasIfsRole())
                            <!--
                            @if($shipment->isResetable()) -->
                                <a href="{{ url('/shipments/' . $shipment->id . '/reset') }}" title="Reset Shipment"><span class="fas fa-backspace ml-sm-2" aria-hidden="true"></span></a>
                            <!--
                            @else
                                <span class="fas fa-backspace ml-sm-2 faded" aria-hidden="true" title="Reset Shipment (unavailable)"></span>
                            @endif
                        -->
                        @endif
                    </td>
                </tr>
                @else
                <tr class="bg-danger text-white">
                    <th scope="row">{{$loop->iteration}}</th>
                    <td colspan="8" class="text-center">Company {{$shipment->company_id}} not found!</td>
                </tr>
                @endif
                @endforeach
                <tr class="text-large bg-secondary text-white">
                    <td colspan="7">&nbsp;</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('pieces'))}}</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('weight'), 2)}}</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('volumetric_weight'), 2)}}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>

        @include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments])
        @include('partials.pagination', ['results'=> $shipments])

    </main>

</div>

@endsection
