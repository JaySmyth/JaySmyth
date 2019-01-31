@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="month">Month</label>
            {!! Form::select('month', dropDown('monthsPrevious'), Input::get('month'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Traffic</label>
            {!! Form::select('traffic', dropDown('traffic', 'All Traffic'), Input::get('traffic'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Carrier</label>
            {!! Form::select('carrier', dropDown('carriers', 'All Carriers'), Input::get('carrier'), array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <label for="month">Depot</label>
            {!! Form::select('depot', dropDown('associatedDepots', 'All Depots'), Input::get('depot'), array('class' => 'form-control')) !!}
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

        <h2>{{$report->name}} -
            @if(Input::get('month'))
            {{Input::get('month')}}
            @else
            {{ date("F Y", time())}}
            @endif
        </h2>

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Total Shippers</th>
                    <th class="text-right">Total Shipments</th>
                    <th class="text-right">Total Pieces</th>
                    <th class="text-right">Volumetric Weight</th>
                    <th class="text-right">Weight</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                @if($result['total_shipments'] > 0)
                @if(stristr($result['date'], 'saturday') || stristr($result['date'], 'sunday'))
                <tr class="table-warning">
                    @else
                <tr>
                    @endif

                    <td><strong>{{$result['date']}}</strong></td>
                    <td class="text-right">{{number_format($result['total_shippers'])}}</td>
                    <td class="text-right">{{number_format($result['total_shipments'])}}</td>
                    <td class="text-right">{{number_format($result['total_pieces'])}}</td>
                    <td class="text-right">{{number_format($result['total_volumetric_weight'],2)}}</td>
                    <td class="text-right">{{number_format($result['total_weight'],2)}}</td>
                    <td class="text-center"><a href="{{url('reports/shippers/1?date_from='.$result['date_short'].'&date_to=' . $result['date_short'])}}" title="Shippers Report for {{$result['date']}}"><span class="fas fa-list" aria-hidden="true"></span></a></td>
                </tr>
                @endif
                @endforeach

                <tr class="text-large bg-secondary text-white">
                    <td>&nbsp;</td>
                    <td class="text-right"><strong>Av. per day: {{$results['average_shippers_per_day']}}</strong></td>
                    <td class="text-right"><strong>{{number_format($results['total_shipments'])}}</strong></td>
                    <td class="text-right"><strong>{{number_format($results['total_pieces'])}}</strong></td>
                    <td class="text-right"><strong>{{number_format($results['total_volumetric_weight'],2)}}</strong></td>
                    <td class="text-right"><strong>{{number_format($results['total_weight'],2)}}</strong></td>
                    <td class="text-center">&nbsp;</td>
                </tr>

            </tbody>
        </table>

    </main>

</div>

@endsection