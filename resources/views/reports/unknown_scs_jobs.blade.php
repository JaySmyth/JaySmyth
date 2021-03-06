@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="text" name="date_from" value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" name="date_to" value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
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

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Consignment</th>
                    <th>Carrier Ref</th>

                    @if(Auth::user()->hasIfsRole() && Auth::user()->hasMultipleDepots())<th class="text-center">Depot</th>@endif

                    @if(Auth::user()->hasIfsRole())
                    <th>Destination</th>    
                    <th>Shipper</th>

                    @else
                    <th>Recipient</th>
                    <th>Reference</th>
                    @endif

                    <th>Ship Date </th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Pieces</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>

                @foreach($shipments as $shipment)

                <tr>
                    <td>
                        @if($shipment->status->code == 'saved')
                        <a href="{{url('/shipments/' . $shipment->id . '/edit') }}" class="consignment-number">{{$shipment->consignment_number}}</a>
                        @else
                        <a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a>
                        @endif
                    </td>
                    <td>
                        @if($shipment->status->code == 'saved')
                        <span class="text-muted"><i>Unknown</i></span>
                        @else
                        {{$shipment->carrier_consignment_number}}
                        @endif
                    </td>

                    @if(Auth::user()->hasIfsRole() && Auth::user()->hasMultipleDepots())<td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->depot->name ?? 'Unknown'}}">{{$shipment->depot->code ?? ''}}</span></td>@endif

                    @if(Auth::user()->hasIfsRole())
                    <td>{{$shipment->recipient_city}} <span class="ml-sm-2">{{$shipment->recipient_country_code}}</span></td>
                    <td><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>
                    @else
                    <td>
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
                        @if($shipment->status->code == 'saved')                        
                        <span class="text-muted"><i>Unknown</i></span>
                        @else
                        <span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span>
                        @endif                    
                    </td>
                    <td class="text-center">{{$shipment->pieces}}</td>
                    <td class="text-center"><span class="status {{$shipment->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->status->description}}">{{$shipment->status->name}}</span></td>
                </tr>

                @endforeach

            </tbody>
        </table>

        @include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments]) 
        @include('partials.pagination', ['results'=> $shipments])

    </main>
</div>

@endsection