@extends('layouts.mail')

@section('content')

<h3>Collection / Delivery Settings Changed</h3>

{{$company->company_name}}<br>
{{$company->address1}}<br>
@if($company->address2){{$company->address2}}<br>@endif
@if($company->address3){{$company->address3}}<br>@endif
{{$company->city}}<br>
{{$company->state}} {{$company->postcode}}<br>
{{$company->country}}<br><br><br>

@endsection