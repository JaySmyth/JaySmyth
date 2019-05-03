@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="filter">Date</label>
            <input type="text" name="date" value="@if(!Input::get('date')){{date(Auth::user()->date_format)}}@else{{Input::get('date')}}@endif" class="form-control datepicker" placeholder="Date">
        </div>

        <div class="form-group">
            <label for="month">Shipper</label>
            {!! Form::select('company', dropDown('enabledSites', 'All Shippers'), Input::get('company'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Received</label>
            {!! Form::select('received', dropDown('boolean', 'All'), Input::get('received'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Routed</label>
            {!! Form::select('routed', dropDown('boolean', 'All'), Input::get('routed'), array('class' => 'form-control')) !!}
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        <h2>{{$report->name}}</h2>

        <table class="table table-striped table-bordered table-sm mb-5">
            <thead>
                <tr class="active">
                    <th>Route</th>
                    <th class="text-right">Total Expected</th>
                    <th class="text-right">Collection Scan</th>
                    <th class="text-right">Receipt Scan</th>
                    <th class="text-right">Route Scan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $key => $route)
                <tr>
                    <td class="font-weight-bold"><a href="#{{$key}}">{{$key}}</a></td>
                    <td class="text-right">{{count($route['packages'])}}</td>
                    <td class="text-right">{{$route['collected'] ?? 0}}</td>
                    <td class="text-right">{{$route['received'] ?? 0}}</td>
                    <td class="text-right">{{$route['loaded'] ?? 0}}</td>
                </tr>
                @endforeach

                <tr class="text-large bg-secondary text-white font-weight-bold">
                    <td>&nbsp;</td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Expected">{{$totals['expected'] ?? 0}}</span></td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Collected">{{$totals['collected'] ?? 0}}</span></td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Received">{{$totals['received'] ?? 0}}</span></td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Loaded">{{$totals['loaded'] ?? 0}}</span></td>
                </tr>
                <tr class="text-large bg-primary text-white font-weight-bold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Collected">{{$percentages['collected']}}%</span></td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Received">{{$percentages['received']}}%</span></td>
                    <td class="text-right"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Loaded">{{$percentages['loaded']}}%</span></td>
                </tr>
            </tbody>
        </table>



        @foreach($routes as $key => $route)

        <h3  id="{{$key}}" class="mb-2">Route: {{$key}}</h3>

        <table class="table table-striped table-bordered table-sm mb-4">
            <thead>
                <tr class="success">
                    <th>#</th>
                    <th>Consignment</th>
                    <th>Shipper</th>
                    <th>Collection Route</th>
                    <th class="text-center">Package</th>
                    <th class="text-center">Collection</th>
                    <th class="text-center">Receipt</th>
                    <th class="text-center">Route</th>
                </tr>
            </thead>
            <tbody>

                @foreach($route['packages'] as $i => $package)

                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><a href="{{ url('/shipments', $package->shipment->id) }}" class="consignment-number">{{$package->shipment->carrier_consignment_number}}</a></td>
                    <td>
                        @if($package->shipment->company)
                        <a href="{{ url('/companies', $package->shipment->company->id) }}">{{$package->shipment->company->company_name}}</a>
                        @else
                        Unknown
                        @endif
                    </td>
                    <td>{{$package->shipment->transportJobs->where('type', 'c')->first()->transend_route ?? null}}</td>
                    <td class="text-center">{{$package->index}} of {{$package->shipment->pieces}}</td>
                    <td class="text-center">

                        @if($package->shipment->company->bulk_collections)
                        
                        <span class="fas fa-times fa-lg text-primary ml-sm-2" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Bulk collection"></span>
                        
                        @else
                        
                        @if($package->collected)
                        <span class="fas fa-check fa-lg text-success ml-sm-2" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Scanned at {{$package->date_collected->timezone(Auth::user()->time_zone)->format('d-m-y H:i')}}"></span>
                        @else
                        <span class="fas fa-times fa-lg text-danger ml-sm-2"></span>
                        @endif

                        @endif


                    </td>
                    <td class="text-center">
                        @if($package->true_receipt_scan)
                        <span class="fas fa-check fa-lg text-success ml-sm-2" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Scanned at {{$package->date_received->timezone(Auth::user()->time_zone)->format('d-m-y H:i')}}"></span>
                        @else
                        <span class="fas fa-times fa-lg text-danger ml-sm-2"></span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($package->loaded)
                        <span class="fas fa-check fa-lg text-success ml-sm-2" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Scanned at {{$package->date_loaded->timezone(Auth::user()->time_zone)->format('d-m-y H:i')}}"></span>
                        @else
                        <span class="fas fa-times fa-lg text-danger ml-sm-2"></span>
                        @endif
                    </td>
                </tr>
                @endforeach

                <tr class="text-large bg-secondary text-white font-weight-bold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-center"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Expected">{{ count($route['packages']) }}</span></td>
                    <td class="text-center"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Collected">{{$route['collected'] ?? 0}}</span></td>
                    <td class="text-center"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Received">{{$route['received'] ?? 0}}</span></td>
                    <td class="text-center"><span  aria-hidden="true" data-placement="bottom" data-toggle="tooltip" data-original-title="Total Loaded">{{$route['loaded'] ?? 0}}</span></td>
                </tr>
            </tbody>
        </table>

        @endforeach

    </main>
</div>

@endsection