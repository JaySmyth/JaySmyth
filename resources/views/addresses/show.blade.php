@extends('layouts.app')

@section('content')

<h2><span class="text-capitalize">{{$address->definition}}</span> <small>{{$address->company->site_name}}</small></h2>

<br>

<div class="col-sm-4">
    <div class="card  text-large">
        <div class="card-header">
            <h4 class="mb-3" class="card-title"> <span class="fas fa-user" aria-hidden="true"></span> <strong>{{$address->name}}</strong> <abbr title="{{getAddressType($address->type)}}">({{$address->type}})</abbr></h4>
        </div>
        <div class="card-body">
            @if($address->company_name){{$address->company_name}}<br>@endif
            {{$address->address1}}<br>
            {{$address->address2}}<br>
            {{$address->address3}}<br>
            {{$address->city}}<br>
            {{$address->state}} {{$address->postcode}}<br>
            {{$address->country}}<br><br>
            {{$address->telephone}}<br>
            <a href="mailto:{{$address->email}}">{{$address->email}}</a>
        </div>
    </div>
</div>


@endsection