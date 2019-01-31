@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">
        Customs Entry:        
        @if($customsEntry->number)
        {{$customsEntry->number}}
        @else
        {{$customsEntry->reference}}
        @endif   
    </h2>
    <h2 class="float-right">
        @if(!$customsEntry->isComplete())                
        <span class="badge badge-warning float-right">Incomplete</span>
        @endif
    </h2>
</div>

<hr>

<div class="row mb-4">

    <div class="col-sm-8">
        <div class="text-large">
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Reference</strong></div>
                <div class="col-sm-8">{{$customsEntry->reference}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Date</strong></div>
                <div class="col-sm-8">{{$customsEntry->date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Commercial Invoice Value</strong></div>
                <div class="col-sm-8">{{$customsEntry->commercial_invoice_value}} {{$customsEntry->commercial_invoice_value_currency_code}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Pieces</strong></div>
                <div class="col-sm-8">{{$customsEntry->pieces}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Weight</strong></div>
                <div class="col-sm-8">{{$customsEntry->weight}} KG</div>
            </div>
            @if($customsEntry->country_of_origin)
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Country Of Origin</strong></div>
                <div class="col-sm-8">
                    {{getCountry($customsEntry->country_of_origin)}}
                </div>
            </div>
            @endif
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Commodity Count</strong></div>
                <div class="col-sm-8">{{$customsEntry->commodity_count}}</div>
            </div>
            @can('create_customs_entry')
            <div class="row mb-2">
                <div class="col-sm-4"><strong>SCS Job Number</strong></div>
                <div class="col-sm-8">{{$customsEntry->scs_job_number ?? 'Incomplete'}}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4"><strong>Created By</strong></div>
                <div class="col-sm-8">{{$customsEntry->user->name ?? 'Unknown'}}</div>
            </div>
            @endcan
        </div>

    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">Duty / VAT Summary</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="mb-2">Value</h4>
                        <h4 class="mb-2">Duty</h4>
                        <h4 class="mb-2">VAT</h4>
                    </div>
                    <div class="col-sm-7 text-right">
                        <h4 class="mb-2">{{$customsEntry->customs_value ?? '0.00'}} <small>GBP</small></h4>
                        <h4 class="mb-2">{{$customsEntry->duty ?? '0.00'}} <small>GBP</small></h4>
                        <h4 class="mb-2">{{$customsEntry->vat ?? '0.00'}} <small>GBP</small></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Commodity Lines -->
@include('customs_entry_commodities.partials.commodities', ['customsEntry' => $customsEntry])

<!-- Documents -->
@include('documents.partials.documents', ['parentModel' => $customsEntry, 'modelName' => 'customs-entry'])

@endsection