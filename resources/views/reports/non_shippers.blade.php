@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <input type="hidden" name="mode" value="{{Input::get('mode')}}">

        <div class="form-group">
            <label for="date_from">Date From</label>
            <input type="text" name="date_from" value="@if(Auth::user()->hasIfsRole() && !Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="form-group">
            <label for="date_to">Date To</label>
            <input type="text" name="date_to" value="@if(Auth::user()->hasIfsRole() && !Input::get('date_from') && !Input::get('date_to')){{date(Auth::user()->date_format)}}@else{{Input::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>
        <div class="form-group">
            <label for="month">Depot</label>
            {!! Form::select('depot', dropDown('associatedDepots', 'All Depots'), Input::get('depot'), array('class' => 'form-control')) !!}
        </div>
        <div class="form-group">
            <label for="month">Salesperson</label>
            {!! Form::select('salesperson', dropDown('sales', 'All Salespersons'), Input::get('salesperson'), array('class' => 'form-control')) !!}
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => $report->name, 'results'=> $companies])

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company</th> 
                    <th>Address</th>                                                               
                    <th>Telephone</th>                
                    <th>Salesperson</th>
                    <th>Last Ship Date</th>
                    <th class="text-center">Depot</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $company)
                <tr>       
                    <th scope="row">{{$loop->iteration}}</th>
                    <td><a href="{{ url('/companies', $company->id) }}">{{$company->company_name}}</a></td>      
                    <td>{{$company->address1}}, {{$company->city}}</td>                               
                    <td>{{$company->telephone}}</td>                
                    <td>{{$company->sale->name}}</td>
                    <td>{{$company->getLastShipDate()}}</td>
                    <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></td>                  
                    <td class="text-center">                    
                        <a href="{{url('/shipments?company=' . $company->id)}}" title="Shipment History"><span class="fas fa-history ml-sm-2" aria-hidden="true"></span></a>                                      
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        @include('partials.no_results', ['title' => 'companies', 'results'=> $companies]) 

    </main>
</div>

@endsection