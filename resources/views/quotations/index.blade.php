@extends('layouts.app')

@section('navSearchPlaceholder', 'Quotation search...')

@section('advanced_search_form')

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Reference or Company Name:
    </label>
    <div class="col-sm-8">
        <input type="text" name="filter" id="filter" value="{{Request::get('filter')}}" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date From:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_from" value="{{Request::get('date_from')}}" class="form-control datepicker" placeholder="Date from">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Date To:
    </label>
    <div class="col-sm-8">
        <input type="text" name="date_to" value="{{Request::get('date_to')}}" class="form-control datepicker" placeholder="Date To">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Department:
    </label>
    <div class="col-sm-8">
        {!! Form::select('department', dropDown('departments', 'All Departments'), Request::get('department'), array('class' => 'form-control')) !!}
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Successful:
    </label>
    <div class="col-sm-8">
        {!! Form::select('successful', dropDown('boolean', 'All Statuses'), Request::get('successful'), array('class' => 'form-control')) !!}
    </div>
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'quotations', 'results'=> $quotations, 'create' => 'quotation'])

{!! Form::Open(['url' => 'quotations', 'autocomplete' => 'off']) !!}

<table class="table table-striped">

    <thead>
        <tr>
            <th>Reference</th>
            <th class="text-center">Dept.</th>
            <th>Company</th>
            <th>From</th>
            <th>To</th>
            <th class="text-right">Pieces</th>
            <th class="text-right">Weight</th>
            <th class="text-center">Created</th>
            <th class="text-center">Valid To</th>
            <th class="text-right">Quote</th>
            <th class="text-center">Success</th>
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        @foreach($quotations as $quotation)
        <tr>
            <td><a href="{{ url('/quotations', $quotation->id) }}" class="quotation-reference">{{$quotation->reference}}</a></td>
            <td class="text-center"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$quotation->department->name}}">{{$quotation->department->code}}</span></td>
            <td class="quotation-company">{{$quotation->company_name}}</td>
            <td>{{$quotation->from_city}}, {{$quotation->from_country_code}}</td>
            <td>{{$quotation->to_city}}, {{$quotation->to_country_code}}</td>
            <td class="text-right">{{$quotation->pieces}}</td>
            <td class="text-right">{{$quotation->weight}}</td>
            <td class="text-center">{{$quotation->created_at->format('d-m-y')}}</td>
            <td class="text-center">{{$quotation->valid_to->format('d-m-y')}}</td>
            <td class="text-right">{{$quotation->quote}} {{$quotation->currency_code}}</td>
            <td class="text-center">
                @if($quotation->successful)
                <span class="status badge badge-success">Yes</span>
                @else
                <span class="status badge badge-danger">No</span>
                @endif
            </td>
            <td class="text-center">                
                
                @can('delete_quotation')
                    <a href="{{ url('/quotations/' . $quotation->id) }}" title="Delete Quotation {{$quotation->reference}}" class="mr-2 delete" data-record-name="quotation"><i class="fas fa-times"></i></a>
                @elsecan
                    <i class="fas fa-times faded mr-2"></i>
                @endcan
                
                <a href="{{ url('/quotations/' . $quotation->id . '/edit') }}" title="Edit Quote" class="mr-2"><i class="far fa-edit"></i></a>
                <a href="{{ url('/quotations/' . $quotation->id . '/status') }}" title="Update Status" class="toggle-quotation-status mr-2"><i class="far fa-check-circle"></i></a>
                <a href="{{ url('/quotations/' . $quotation->id . '/pdf') }}" title="Download PDF" class=""><i class="fas fa-print" aria-hidden="true"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

{!! Form::Close() !!}

@include('partials.no_results', ['title' => 'quotations', 'results'=> $quotations])
@include('partials.pagination', ['results'=> $quotations])

@endsection