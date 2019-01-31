@extends('layouts.mail')

@section('content')

@if($company->enabled)
<h1>Company Account Enabled</h1>
@else
<h1>Company Account Disabled</h1>
@endif

<h2>{{$company->notes}}</h2>

<p>Change made by {{$user->name}}.</p><br>

<h3>{{$company->company_name}}</h3>
{{$company->address1}}<br>
@if($company->address2){{$company->address2}}<br>@endif
@if($company->address3){{$company->address3}}<br>@endif
{{$company->city}}<br>
{{$company->state}} {{$company->postcode}}<br>
{{$company->country}}<br><br><br>

@endsection