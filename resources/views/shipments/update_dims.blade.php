@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="filter">Consignment or Reference</label>
            <input type="text" name="filter" id="filter" value="{{Input::get('filter')}}" class="form-control" placeholder="">
        </div>

        <div class="form-group">
            <label for="month">Date From</label>
            <input type="text" name="date_from" value="@if(!Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>

        <div class="form-group">
            <label for="month">Date To</label>
            <input type="text" name="date_to" value="@if(!Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <div class="form-group">
            <label for="month">Carrier</label>
            {!! Form::select('carrier', dropDown('carriers', 'All Carriers'), Input::get('carrier'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Service</label>
            {!! Form::select('service', dropDown('services', 'All Services'), Input::get('service'), array('class' => 'form-control')) !!}   
        </div>

        <div class="form-group">
            <label for="month">Shipper</label>
            {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Input::get('company'), array('class' => 'form-control')) !!}  
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => 'Update DIMS', 'results'=> $shipments])

        {!! Form::Open(['url' => 'shipments', 'autocomplete' => 'off']) !!}

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Consignment</th>
                    <th>Shipper</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Pieces</th>            
                    <th class="text-center">Length(cm)</th>
                    <th class="text-center">Width(cm)</th>
                    <th class="text-center">Height(cm)</th>
                    <th class="text-center">Weight(kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shipments as $shipment)
                <tr>
                    <td class="align-middle"><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->carrier_consignment_number}}</a></td>
                    <td class="align-middle"><a href="{{ url('/companies', $shipment->company->id) }}">{{$shipment->company->company_name}}</a></td>
                    <td class="align-middle text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
                    <td class="align-middle text-center">{{$shipment->pieces}}</td>
                    <td  class="align-middle text-center">
                        @foreach ($shipment->packages as $package)
                        {!! Form::Text('packages['.$package->index.'][length]', null, ['class' => 'form-control form-control-sm numeric-only-required', 'placeholder' => $package->index . '. Length(cm)']) !!}
                        @endforeach
                    </td>
                    <td  class="align-middle text-center">
                        @foreach ($shipment->packages as $package)
                        {!! Form::Text('packages['.$package->index.'][width]', null, ['class' => 'form-control form-control-sm numeric-only-required', 'placeholder' => $package->index . '. Width(cm)']) !!}
                        @endforeach
                    </td>
                    <td  class="align-middle text-center">
                        @foreach ($shipment->packages as $package)
                        {!! Form::Text('packages['.$package->index.'][height]', null, ['class' => 'form-control form-control-sm numeric-only-required', 'placeholder' => $package->index . '. Height(cm)']) !!}
                        @endforeach
                    </td>
                    <td  class="align-middle text-center">
                        @foreach ($shipment->packages as $package)
                        {!! Form::Text('packages['.$package->index.'][weight]', null, ['class' => 'form-control form-control-sm decimal-only-required', 'placeholder' => $package->index . '. Weight(kg)']) !!}
                        @endforeach
                    </td>
                    <td  class="align-middle text-center">
                        <a href="#" id="{{$shipment->id}}" class="update-dims" title="Update DIMS" tabindex="-1"><span class="far fa-check-square" aria-hidden="true"></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {!! Form::Close() !!}

        @include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments])
        @include('partials.pagination', ['results'=> $shipments])

    </main>

</div>
@endsection