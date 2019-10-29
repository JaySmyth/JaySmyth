@extends('layouts.app')

@section('navSearchPlaceholder', 'Customs entry search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Entry number, reference or consignment number:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Input::get('filter')}}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date From:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_from" value="{{Input::get('date_from')}}" class="form-control datepicker" placeholder="Date from">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date To:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_to" value="{{Input::get('date_to')}}" class="form-control datepicker" placeholder="Date To">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        CPC:
    </label>
    <div class="col-sm-8">
        {!! Form::select('cpc', dropDown('cpc', 'All CPCs'), Input::get('cpc'), array('class' => 'form-control')) !!}
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Customer:
    </label>
    <div class="col-sm-8">
        {!! Form::select('company', dropDown('sites', 'All Customers'), Input::get('company'), array('class' => 'form-control')) !!}
    </div>
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@section('toolbar')
<a href="{{ url('/customs-entries/download')}}?{{Request::getQueryString()}}" class="mr-sm-3" title="Download Results"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>
@if($fullDutyAndVat)
<a href="{{ url('/customs-entries/download-commodity')}}?{{Request::getQueryString()}}" title="Download by Commodity"><span class="fas fa-cloud-download-alt fa-lg" aria-hidden="true"></span></a>
@endif
@endsection

@include('partials.title', ['title' => 'customs entries', 'results'=> $customsEntries, 'create' => 'customs_entry'])

{!! Form::Open(['url' => 'customs-entries', 'autocomplete' => 'off']) !!}


<table class="table table-striped">

    <thead>
        <tr>
            <th>Number</th>
            <th>Customer</th>
            <th>Reference</th>
            @can('create_customs_entry')<th>SCS Job</th>@endcan
            <th>Date</th>
            @if($fullDutyAndVat)
                <th class="text-center">Commodities</th>
                <th class="text-right">Pieces</th>
                <th class="text-right">Weight (KG)</th>
                <th class="text-right">Value (GBP)</th>
                <th class="text-right">Duty (GBP)</th>
                <th class="text-right">VAT (GBP)</th>
            @endif
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        @foreach($customsEntries as $entry)
        <tr>
            <td>
                @if($entry->isComplete())
                <a href="{{ url('/customs-entries', $entry->id) }}">{{$entry->number}}</a>
                @else
                <a href="{{ url('/customs-entries', $entry->id) }}" class="text-danger">Incomplete</a>
                @endif
            </td>
            <td>{{$entry->company->company_name}}</td>
            <td>{{$entry->reference}}</td>
                @can('create_customs_entry')<td>{{$entry->scs_job_number}}</td>@endcan
                <td>{{$entry->date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
                @if($fullDutyAndVat)
                    <td class="text-center">
                        @if($entry->customsEntryCommodity->count() != $entry->commodity_count)
                        <span class="text-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Commodity detail required">{{$entry->customsEntryCommodity->count()}} of {{$entry->commodity_count}}</span>
                        @else
                        {{$entry->commodity_count}}
                        @endif
                    </td>

                    <td class="text-right">{{$entry->pieces}}</td>
                    <td class="text-right">{{$entry->weight}}</td>
                    <td class="text-right">{{$entry->customs_value ?? '0.00'}}</td>
                    <td class="text-right">{{$entry->duty ?? '0.00'}}</td>
                    <td class="text-right">{{$entry->vat ?? '0.00'}}</td>
                @endif

            <td class="text-center text-nowrap">
                @can('create_delete_entry')
                <a href="{{ url('/customs-entries/' . $entry->id) }}" title="Delete Entry" class="delete mr-2" data-record-name="entry"><i class="fas fa-times"></i></a>
                @endcan

                <a href="{{ url('/documents/create/customs-entry/' . $entry->id) }}" title="Supporting Documents" class="supporting-docs"><span class="fas fa-file mr-2" aria-hidden="true"></span></a>
                @can('create_customs_entry')<a href="{{ url('/customs-entries/' .  $entry->id . '/edit') }}" title="Edit Entry"><span class="fas fa-edit mr-2" aria-hidden="true"></span></a>@endcan
                @can('create_customs_entry')<a href="{{ url('/customs-entries/' . $entry->id . '/add-commodity') }}" title="Add Commodity" class=""><span class="far fa-plus-square" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

{!! Form::Close() !!}

@include('partials.no_results', ['title' => 'customs entries', 'results'=> $customsEntries])
@include('partials.pagination', ['results'=> $customsEntries])

@endsection
