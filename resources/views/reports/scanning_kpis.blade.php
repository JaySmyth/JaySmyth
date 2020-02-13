@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="month">Month</label>
            {!! Form::select('month', dropDown('monthsPrevious'), Request::get('month'), array('class' => 'form-control')) !!}
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        <h2>{{$report->name}} -
            @if(Request::get('month'))
            {{Request::get('month')}}
            @else
            {{ date("F Y", time())}}
            @endif
        </h2>

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Collection</th>
                    <th class="text-right">Receipt</th>
                    <th class="text-right">Route</th>
                    <th class="text-right">Receipt Missed</th>
                    <th class="text-right">Route Missed</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kpis as $kpi)

                <tr>
                    <td><strong>{{$kpi->date->format('l jS F, Y')}}</strong></td>
                    <td class="text-right">{{$kpi->collection_percentage}}%</td>
                    <td class="text-right">{{$kpi->receipt_percentage}}%</td>
                    <td class="text-right">{{$kpi->route_percentage}}%</td>
                    <td class="text-right">
                        @if($kpi->receipt_missed > 0)
                        <span class="text-danger">{{$kpi->receipt_missed}}</span>
                        @else
                        <span class="text-success">{{$kpi->receipt_missed}}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($kpi->route_missed > 0)
                        <span class="text-danger">{{$kpi->route_missed}}</span>
                        @else
                        <span class="text-success">{{$kpi->route_missed}}</span>
                        @endif
                    </td>
                    <td class="text-center"><a href="{{url('reports/scanning/3?date='. $kpi->date->format('d-m-Y'))}}" title="Scanning Report for {{ $kpi->date->format('d-m-Y') }}"><span class="fas fa-list" aria-hidden="true"></span></a></td>
                </tr>

                @endforeach

                <tr class="text-large bg-primary text-white">
                    <td>&nbsp;</td>
                    <td class="text-right"><strong>{{ $collectionPercentageForMonth }}%</strong></td>
                    <td class="text-right"><strong>{{ $receiptPercentageForMonth }}%</strong></td>
                    <td class="text-right"><strong>{{ $routePercentageForMonth }}%</strong></td>                    
                    <td class="text-right"><strong>Av. {{ $averageReceiptMissed }} / Tot. {{ $totalReceiptMissed }}</strong></td>
                    <td class="text-right"><strong>Av. {{ $averageRouteMissed }} / Tot. {{ $totalRouteMissed }}</strong></td>
                    <td class="text-center">&nbsp;</td>
                </tr>
            </tbody>
        </table>

    </main>

</div>

@endsection