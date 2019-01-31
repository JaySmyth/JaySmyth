@extends('layouts.app')

@section('content')

<h2>Unknown Jobs <span class="badge badge-secondary float-right">{{$purchaseInvoiceLines->count()}}</span></h2>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice</th>
            <th class="text-center">Type</th>
            <th>Consignment</th>
            <th>Job</th>
            <th>Ship Date</th>
            <th>Shipper / Consignor</th>
            <th>Service</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseInvoiceLines as $line)
        <tr>
            <td><a href="{{ url('/purchase-invoices/' . $line->purchaseInvoice->id . '/detail#line-' . $line->id) }}">{{$loop->iteration}}</a></td>
            <td><a href="{{ url('/purchase-invoices/' . $line->purchaseInvoice->id) }}">{{$line->purchaseInvoice->invoice_number}}</td>
            <td class="text-center"><span class="badge badge-secondary" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="{{verboseInvoiceType($line->purchaseInvoice->type)}} Invoice">{{$line->purchaseInvoice->type}}</span></td>
            <td>
                @if($line->shipment_id)
                <a href="{{ url('/shipments', $line->shipment->id) }}" class="consignment-number">{{$line->carrier_tracking_number}}</a>
                @elseif($line->carrier_tracking_number)
                {{$line->carrier_tracking_number}}
                @else
                Unknown
                @endif
            </td>
            <td>{{$line->type}}</td>
            <td>{{$line->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td>
                @if($line->shipment_id)

                @if($line->shipment->company)
                <a href="{{ url('/companies', $line->shipment->company_id) }}">{{$line->shipment->company->company_name}}</a>
                @else
                {{$line->sender_company_name}}
                @endif

                @else
                @if($line->sender_company_name)
                {{$line->sender_company_name}}
                @elseif($line->carrier_service)
                {{$line->carrier_service}}
                @else
                Unknown
                @endif
                @endif
            </td>
            <td>{{$line->service}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection