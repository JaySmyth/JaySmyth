@extends('layouts.app')

@section('content')

@include('purchase_invoices.partials.title', ['purchaseInvoice'=> $purchaseInvoice, 'small' => 'Overview'])

<h4>Invoice Lines <span class="badge badge-pill badge-sm badge-secondary">{{$purchaseInvoice->lines->count()}}</span></h4>

<table class="table table-sm table-striped table-bordered mb-5">
    <thead>
        <tr class="active">
            <th>#</th>
            <th>Consignment</th>
            <th>SCS Job Number</th>
            <th>Ship Date</th>
            <th>Consignor</th>
            <th class="text-center">Service</th>
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight</th>
            <th class="text-right">Billed Weight</th>
            <th class="text-right">Charges</th>
            <th class="text-right">VAT</th>
            <th class="text-center">VAT Code</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseInvoice->lines as $line)
        <tr>
            <td><a href="{{ url('/purchase-invoices/' . $purchaseInvoice->id . '/detail#line-' . $line->id) }}">{{$loop->iteration}}</a></td>
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
                @if($line->ship_date)
                {{$line->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                @else
                Unknown
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
            <td class="text-center">{{($line->carrier_service) ? $line->carrier_service : 'Unknown'}}</td>
            <td class="text-right">{{$line->pieces}}</td>
            <td class="text-right">{{$line->weight}}</td>
            <td class="text-right">
                @if($line->billed_weight > $line->weight)
                <span class="text-danger">{{$line->billed_weight}}</span>
                @else
                {{$line->billed_weight}}
                @endif
            </td>
            <td class="text-right">{{$line->total}}</td>
            <td class="text-right">{{$line->total_vat}}</td>
            <td class="text-center">{{$line->scs_vat_code}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection