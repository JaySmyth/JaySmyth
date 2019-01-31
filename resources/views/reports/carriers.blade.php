@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="text" name="date_from" value="@if(Auth::user()->hasIfsRole() && !Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" name="date_to" value="@if(Auth::user()->hasIfsRole() && !Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <div class="form-group">
            <label for="month">Traffic</label>
            {!! Form::select('traffic', dropDown('traffic', 'All Traffic'), Input::get('traffic'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Shipper</label>
            {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Input::get('company'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Depot</label>
            {!! Form::select('depot', dropDown('associatedDepots', 'All Depots'), Input::get('depot'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Service</label>
            {!! Form::select('service', dropDown('services', 'All Services'), Input::get('service'), array('class' => 'form-control')) !!}
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => $report->name, 'results'=> $shipments])

        <p class="lead text-muted">This report only takes into consideration shipments that have been shipped. Shipments with Saved, Pre-transit and Cancelled statuses are ignored.</p>
        
        @php ($total_chargeable = 0)

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Carrier</th>                    
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
                    <td>{{$shipment->carrier->name}}</td>                    
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
                        <a href="{{url('/shipments/download?filter=&status=S&carrier=' . $shipment->carrier->id . '&' . Request::getQueryString())}}" title="Download Results"><span class="fas fa-cloud-download-alt" aria-hidden="true"></span></a>
                        <a href="{{url('/shipments?filter=&status=S&carrier=' . $shipment->carrier->id . '&' . Request::getQueryString())}}" title="Shipment History"><span class="fas fa-list ml-sm-2" aria-hidden="true"></span></a>
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
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total'))}}</th>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_pieces'))}}</th>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_weight'), 1)}}</th>
                    <th scope="row" class="text-right">{{number_format($shipments->sum('total_volumetric_weight'), 1)}}</th>
                    <th scope="row" class="text-right">{{number_format($total_chargeable, 1)}}</th> 
                    <th>&nbsp;</th>
                </tr>
            </tbody>
        </table>

        @include('partials.no_results', ['title' => 'carriers', 'results'=> $shipments])
        @include('partials.pagination', ['results'=> $shipments])

    </main>
</div>

@endsection