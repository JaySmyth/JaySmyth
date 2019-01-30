@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">Quotation: {{$quotation->reference}} / {{$quotation->department->name}}</h2>
    <h2 class="float-right">
        @if($quotation->successful)
        <span class="badge badge-success float-right">Successful</span>
        @else
        <span class="badge badge-danger float-right">Unsuccessful</span>
        @endif
    </h2>
</div>

<hr>

<div class="row">
    <div class="col-8">
        <table class="table table-borderless h5">
            <tr>
                <td width="6%">To:</td>
                <td>{{$quotation->contact}}, {{$quotation->company_name}}</td>
            </tr>
            <tr>
                <td width="6%">Telephone:</td>
                <td>{{$quotation->telephone}}</td>
            </tr>
            <tr>
                <td width="6%">Email:</td>
                <td><a href="mailto:{{$quotation->email}}">{{$quotation->email}}</a></td>
            </tr>
            <tr>
                <td width="6%">From:</td>
                <td>{{$quotation->user->name}}</td>
            </tr>
            <tr>
                <td width="6%">Date:</td>
                <td>{{$quotation->created_at}}</td>
            </tr>
        </table>
    </div>
    <div class="col">
        <div class="card bg-warning text-white shadow">
            <div class="card-header">
                Staff Comments
            </div>
            <div class="card-body">
                @if($quotation->comments)
                {{$quotation->comments}}
                @else
                NONE
                @endif
            </div>
        </div>
    </div>
</div>


<table class="table table-bordered shadow mb-5">
    <tr>
        <td>
            <div class="font-weight-bold text-large">From:</div>
            {{$quotation->from_city}}, {{getCountry($quotation->from_country_code)}}
        </td>
        <td>
            <div class="font-weight-bold text-large">To:</div>
            {{$quotation->to_city}}, {{getCountry($quotation->to_country_code)}}
        </td>
    </tr>
    <tr>
        <td>
            <div class="font-weight-bold text-large">Pieces:</div>
            {{$quotation->pieces}}
        </td>
        <td>
            <div class="font-weight-bold text-large">Dimensions:</div>
            {{$quotation->dimensions}}
        </td>
    </tr>
    <tr>
        <td>
            <div class="font-weight-bold text-large">Weight:</div>
            {{$quotation->weight}} KG
        </td>
        <td>
            <div class="font-weight-bold text-large">Volumetric Weight:</div>
            {{$quotation->volumetric_weight}} KG
        </td>
    </tr>
    <tr>
        <td>
            <div class="font-weight-bold text-large">Goods Description:</div>
            {{$quotation->goods_description}}
        </td>
        <td>
            <div class="font-weight-bold text-large">Rate of Exchange:</div>
            {{$quotation->rate_of_exchange}}
        </td>
    </tr>
    <tr>
        <td>
            <div class="font-weight-bold text-large">Quote (Excluding VAT):</div>
            {{$quotation->quote}} {{$quotation->currency_code}}
        </td>
        <td>
            <div class="font-weight-bold text-large">Subject To:</div>
            {{$quotation->terms}}
        </td>
    </tr>
    <tr>
        <td>
            <div class="font-weight-bold text-large">Special Requirements:</div>
            {{$quotation->special_requirements}}
        </td>
        <td>
            <div class="font-weight-bold text-large">Quote Valid To:</div>
            {{$quotation->valid_to->format('d-m-Y')}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="font-weight-bold text-large">Additional Information:</div>
            {!!nl2br($quotation->information)!!}

        </td>
    </tr>
</table>


@include('partials.log', ['logs' => $quotation->logs])


@endsection