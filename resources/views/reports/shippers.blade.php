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

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => $report->name, 'results'=> $shipments])

        @php ($total_chargeable = 0)

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company</th>
                    <th class="text-center">Depot</th>
                    <th class="text-right">Total Shipments</th>
                    <th class="text-right">Total Pieces</th>
                    <th class="text-right">Weight</th>
                    <th class="text-right">Volumetric Weight</th>
                    <th class="text-right">Chargeable Weight</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shipments as $shipment)
                @if($shipment->company)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>
                    <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->company->depot->name}}">{{$shipment->company->depot->code}}</span></td>
                    <td class="text-right">{{number_format($shipment->total)}}</td>
                    <td class="text-right">{{number_format($shipment->total_pieces)}}</td>
                    <td class="text-right">{{number_format($shipment->total_weight, 1)}}</td>
                    <td class="text-right">{{number_format($shipment->total_volumetric_weight, 1)}}</td>
                    <td class="text-right">
                        @if($shipment->total_weight > $shipment->total_volumetric_weight)
                        {{number_format($shipment->total_weight, 1)}}
                        @php ($total_chargeable += $shipment->total_weight)
                        @else
                        {{$shipment->total_volumetric_weight}}
                        @php ($total_chargeable += $shipment->total_volumetric_weight)
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{url('/shipments?filter=&company=' . $shipment->company->id . '&' . Request::getQueryString())}}" title="Shipment History"><span class="fas fa-history" aria-hidden="true"></span></a>

                        @if(!Request::get('date_from'))
                        <a href="{{url('/shipments/batched-shipping-docs/master?filter=&company=' . $shipment->company->id . '&date_from=' . date(Auth::user()->date_format) . '&date_to=' . date(Auth::user()->date_format))}}" title="Download Master Labels"><span class="fas fa-print ml-sm-2" aria-hidden="true"></span></a>                        
                        <a href="{{url('/shipments/batched-shipping-docs/invoice?filter=&company=' . $shipment->company->id . '&date_from=' . date(Auth::user()->date_format) . '&date_to=' . date(Auth::user()->date_format))}}" title="Download Commercial Invoices"><span class="fas fa-list-alt ml-sm-2" aria-hidden="true"></span></a>                        
                        <a href="{{url('/shipments/batched-shipping-docs/despatch?filter=&company=' . $shipment->company->id . '&date_from=' . date(Auth::user()->date_format) . '&date_to=' . date(Auth::user()->date_format))}}" title="Download Despatch Notes"><span class="fas fa-file ml-sm-2" aria-hidden="true"></span></a> 
                        <a href="{{url('/shipments/batched-shipping-docs/all?filter=&company=' . $shipment->company->id . '&date_from=' . date(Auth::user()->date_format) . '&date_to=' . date(Auth::user()->date_format))}}" title="Download All Shipping Documents"><span class="far fa-copy ml-sm-2" aria-hidden="true"></span></a>
                        @else
                        <a href="{{url('/shipments/batched-shipping-docs/master?filter=&company=' . $shipment->company->id . '&' . Request::getQueryString())}}" title="Download Master Labels"><span class="fas fa-print ml-sm-2" aria-hidden="true"></span></a>                        
                        <a href="{{url('/shipments/batched-shipping-docs/invoice?filter=&company=' . $shipment->company->id . '&' . Request::getQueryString())}}" title="Download Commercial Invoices"><span class="fas fa-list-alt ml-sm-2" aria-hidden="true"></span></a>                        
                        <a href="{{url('/shipments/batched-shipping-docs/despatch?filter=&company=' . $shipment->company->id . '&' . Request::getQueryString())}}" title="Download Despatch Notes"><span class="fas fa-file ml-sm-2" aria-hidden="true"></span></a>                        
                        <a href="{{url('/shipments/batched-shipping-docs/all?filter=&company=' . $shipment->company->id . '&' . Request::getQueryString())}}" title="Download All Shipping Documents"><span class="far fa-copy ml-sm-2" aria-hidden="true"></span></a>

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
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total'))}}</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_pieces'))}}</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_weight'), 1)}}</td>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_volumetric_weight'), 1)}}</td>
                    <th scope="row" class="text-right">{{number_format($total_chargeable, 1)}}</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>


        @include('partials.no_results', ['title' => 'shippers', 'results'=> $shipments])
        @include('partials.pagination', ['results'=> $shipments])


    </main>
</div>

@endsection