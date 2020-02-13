@extends('layouts.app')

@section('content')

<h2 class="float-left">
    Create Sales Invoices <span class="badge badge-secondary">{{$shipments->count()}}</span> 
    @if(Request::get('department'))
    <small class="text-muted ml-sm-4">{{App\Department::find(Request::get('department'))->first()->name}}</small>
    @endif

    @if(Request::get('company'))   
     <small class="text-muted ml-sm-4">{{App\Company::find(Request::get('company'))->first()->company_name}}</small>
    @endif
    
    @if ($shipments->count() > 998)
     <small class="text-muted ml-sm-4"><br>Note: Only the first 998 shipments will be invoiced. Please re-run this program when completed</small>
    @endif
</h2>


@if($shipments->count() > 0)

{!! Form::Open(['url' => 'invoice-runs', 'method' => 'post', 'autocomplete' => 'off']) !!}

<button type="submit" class="btn btn-primary float-right">Create Invoices</button>

<input type="hidden" name="company" value="{{Request::get('company')}}">
<input type="hidden" name="department" value="{{Request::get('department')}}">

@include('invoice_runs.partials.shipments', ['shipments' => $shipments, 'form' => true])

{!! Form::Close() !!}

@else
<div class="no-results">No shipments available for invoicing!</div>
@endif

@endsection