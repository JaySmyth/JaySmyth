@extends('layouts.app')

@section('content')

<div class="row">

    <div class="col-sm-2 d-none d-sm-block bg-light sidebar">

        {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'autocomplete' => 'off']) !!}

        <div class="form-group">
            <label for="filter">Consignment ?? Reference</label>
            <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control" placeholder="">
        </div>

        <div class="form-group">
            <label for="month">Carrier</label>
            {!! Form::select('carrier', dropDown('carriers', 'All Carriers'), Request::get('carrier'), array('class' => 'form-control')) !!}
        </div>
        <br>

        <div class="form-group">
            <label for="invoice_date_from">Invoice Date From</label>
            <input type="text" name="invoice_date_from" value="@if(Request::get('invoice_date_from')){{Request::get('invoice_date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>

        <div class="form-group">
            <label for="invoice_date_to">Invoice Date To</label>
            <input type="text" name="invoice_date_to" value="@if(Request::get('invoice_date_to')){{Request::get('invoice_date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <br>
        <div class="form-group">
            <label for="date_from">Ship Date From</label>
            <input type="text" name="date_from" value="@if(Request::get('date_from')){{Request::get('date_from')}}@endif" class="form-control datepicker" placeholder="Date from">
        </div>
        <div class="form-group">
            <label for="date_to">Ship Date To</label>
            <input type="text" name="date_to" value="@if(Request::get('date_to')){{Request::get('date_to')}}@endif" class="form-control datepicker" placeholder="Date To">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Report</button>

        {!! Form::Close() !!}

    </div>

    <main class="col-sm-10 ml-sm-auto" role="main">

        @include('partials.title', ['title' => $report->name, 'results'=> $purchaseInvoiceLines])

        <table class="table table-sm table-striped table-bordered mb-5">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Invoice</th>                    
                    <th>Carrier</th>
                    <th>Type</th>
                    <th>Consignment</th>
                    <th>SCS Job Number</th>
                    <th>Consignor</th>                   
                    <th>Ship Date</th>
                    <th>Invoice Date</th>                    
                    <th class="text-right">Charges</th>
                    <th class="text-right">VAT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseInvoiceLines as $line)
                <tr>
                    <td><a href="{{ url('/purchase-invoices/' . $line->purchaseInvoice->id . '/detail#line-' . $line->id) }}">{{$loop->iteration}}</a></td>
                    <td><a href="{{ url('/purchase-invoices/' . $line->purchaseInvoice->id) }}">{{$line->purchaseInvoice->invoice_number}}</td>                    
                    <td>{{$line->purchaseInvoice->carrier->name}}</td>
                    <td class="text-center"><span class="badge {{($line->purchaseInvoice->type == 'F') ? 'badge-primary' : 'badge-secondary'}}" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{verboseInvoiceType($line->purchaseInvoice->type)}} Invoice">{{$line->purchaseInvoice->type}}</span></td>                            
                    <td>
                        @if($line->shipment_id)         
                        <a href="{{ url('/shipments', $line->shipment->id) }}" class="consignment-number">{{$line->carrier_tracking_number}}</a>                                        
                        @elseif($line->carrier_tracking_number)               
                        {{$line->carrier_tracking_number}}                
                        @else
                        Unknown
                        @endif
                    </td>
                    <td>
                        @if($line->scs_job_number)
                        {{$line->scs_job_number}}
                        @else
                        <span class="text-danger">Unknown</span>
                        @endif
                    </td>
                    <td>
                        @if($line->shipment_id)
                        <a href="{{ url('/companies', $line->shipment->company_id) }}">{{$line->shipment->company->company_name}}</a>
                        @else
                        @if($line->sender_company_name)
                        {{$line->sender_company_name}}
                        @else
                        Unknown
                        @endif
                        @endif
                    </td>
                    <td>
                        @if($line->ship_date)
                        {{$line->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        @else
                        Unknown
                        @endif
                    </td>
                    <td>
                        @if($line->purchaseInvoice->date)
                        {{$line->purchaseInvoice->date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        @else
                        Unknown
                        @endif
                    </td>

                    <td class="text-right">{{$line->total}}</td>
                    <td class="text-right">{{$line->total_vat}}</td>

                </tr>
                @endforeach
            </tbody>
        </table>

        @include('partials.no_results', ['title' => 'lines', 'results'=> $purchaseInvoiceLines]) 
        @include('partials.pagination', ['results'=> $purchaseInvoiceLines])

    </main>
</div>

@endsection