@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="filter">Consignment or Reference</label>
            <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control" placeholder="">
        </div>

        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="text" name="date_from" value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" name="date_to" value="@if(Auth::user()->hasIfsRole() && !Request::get('date_from') && !Request::get('date_to')){{date(Auth::user()->date_format)}}@else{{Request::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
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

        <div class="table table-striped-responsive">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Consignment</th>                
                        <th>Ship Date </th>  
                        @if(Auth::user()->hasIfsRole())
                        <th>Shipper</th>
                        @else
                        <th>Reference</th>                
                        @endif                
                        <th class="text-center">Service</th>                              
                        <th class="text-center">Transit</th>
                        <th>Delivery Date</th>
                        <th>Signature</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($shipments as $shipment)

                    <tr>
                        <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>    
                        <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>    
                        @if(Auth::user()->hasIfsRole())
                        <td><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>
                        @else
                        <td>{{$shipment->shipment_reference}}</td>                
                        @endif
                        <td class="text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>                                                            
                        <td class="text-center">{{$shipment->timeInTransit}}<span class="hours">hrs</span></td>                
                        <td>{{$shipment->getDeliveryDate(Auth::user()->date_format . ' g:ia')}}</td>
                        <td>{{$shipment->pod_signature}}</td>    
                        <td class="text-center"><a href="{{ url('/tracking/' . $shipment->token) }}" title="IFS Tracking Link" target="_blank"><span class="fas fa-external-link" aria-hidden="true"></span></a></td>
                    </tr>

                    @endforeach

                </tbody>
            </table>
        </div>

        @include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments]) 
        @include('partials.pagination', ['results'=> $shipments])

    </main>
</div>

@endsection