@extends('layouts.mail')

@section('content')

<h1>General Pricing Zones Import</h1>

<p><b>Records Inserted:</b> {{$results['summary']['inserted']}}</p>
<p><b>Records Failed:</b> {{$results['summary']['failed']}}</p>

@if($results['summary']['failed'] > 0)
<p>Please correct the errors highlighted below and try again. No rates have been changed/ imported.</p>
@endif

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th colspan="3"></th>
            <th colspan="3">Sender</th>
            <th colspan="4">Recipient</th>
            <th>&nbsp;</th>
            <th colspan="2">Zones</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Company Id</th>
            <th>Model</th>
            <th>Country</th>
            <th>From Postcode</th>
            <th>To Postcode</th>
            <th>Country</th>
            <th>Name</th>
            <th>From Postcode</th>
            <th>To Postcode</th>
            <th>Service</th>
            <th>Cost</th>
            <th>Sales</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Status</th>
            <th>Comment</th>
        </tr>
    </thead>

    @foreach($results['rows'] as $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        @if(isset($result['data']))
        <td @if(isset($result['fields_in_error']['company_id'])) class="field-error" @endif>{{$result['data']['company_id']}}</td>
        <td @if(isset($result['fields_in_error']['model_id'])) class="field-error" @endif>{{$result['data']['model_id']}}</td>
        <td @if(isset($result['fields_in_error']['sender_country_code'])) class="field-error" @endif>{{$result['data']['sender_country_code']}}</td>
        <td @if(isset($result['fields_in_error']['from_sender_postcode'])) class="field-error" @endif>{{$result['data']['from_sender_postcode']}}</td>
        <td @if(isset($result['fields_in_error']['to_sender_postcode'])) class="field-error" @endif>{{$result['data']['to_sender_postcode']}}</td>
        <td @if(isset($result['fields_in_error']['recipient_country_code'])) class="field-error" @endif>{{$result['data']['recipient_country_code']}}</td>
        <td @if(isset($result['fields_in_error']['recipient_name'])) class="field-error" @endif>{{$result['data']['recipient_name']}}</td>
        <td @if(isset($result['fields_in_error']['from_recipient_postcode'])) class="field-error" @endif>{{$result['data']['from_recipient_postcode']}}</td>
        <td @if(isset($result['fields_in_error']['to_recipient_postcode'])) class="field-error" @endif>{{$result['data']['to_recipient_postcode']}}</td>
        <td @if(isset($result['fields_in_error']['service_code'])) class="field-error" @endif>{{$result['data']['service_code']}}</td>
        <td @if(isset($result['fields_in_error']['cost_zone'])) class="field-error" @endif>{{$result['data']['cost_zone']}}</td>
        <td @if(isset($result['fields_in_error']['sale_zone'])) class="field-error" @endif>{{$result['data']['sale_zone']}}</td>
        <td @if(isset($result['fields_in_error']['from_date'])) class="field-error" @endif>{{$result['data']['from_date']}}</td>
        <td @if(isset($result['fields_in_error']['to_date'])) class="field-error" @endif>{{$result['data']['to_date']}}</td>
        <td>
            @if(isset($result['errors']))
            <span class="error">Failed</span>
            @else
            <span class="inserted">Inserted</span>
            @endif
        </td>
        <td class="error-summary">
            @if(isset($result['errors']))
            @foreach($result['errors'] as $error)
            {{$error}}<br>
            @endforeach
            @endif
        </td>
    @elseif(isset($result['errors']))
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="fade">n/a</td>
        <td class="error">Failed</td>
        <td class="error-summary">
            @if(isset($result['errors']))
            @foreach($result['errors'] as $error)
            {{$error}}<br>
            @endforeach
            @endif
        </td>
    @endif
</tr>

@endforeach

</table>

@endsection
