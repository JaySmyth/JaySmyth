@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">Collection Request</h2>
    <div class="float-left pt-3 ml-sm-5">
        <button type="button" id="transport-address-book" class="btn btn-secondary btn-sm btn-xs" data-toggle="modal" data-target="#address_book">Address Book</button>    
        <button type="button" id="save-transport-address" class="btn btn-secondary btn-sm btn-xs ml-sm-2">Save Address</button>
    </div>
</div>

<hr class="mt-1">

{!! Form::Open(['url' => 'transport-jobs', 'class' => 'form-compact']) !!}
{!! Form::hidden('address_id',  old('address_id'), array('id' => 'address_id')) !!}
@include('transport_jobs.partials.form', ['submitButtonText' => 'Create Collection Request', 'address' => $address])
{!! Form::Close() !!}

@include('partials.modals.address_book')

@endsection

