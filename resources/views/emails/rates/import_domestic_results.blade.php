@extends('layouts.mail')

@section('content')

<h1>Domestic Master Rate Import</h1>

<p><b>Records Inserted:</b> {{$results['summary']['inserted']}}</p>
<p><b>Records Failed:</b> {{$results['summary']['failed']}}</p>

@if($results['summary']['failed'] > 0)
<p>Please correct the errors highlighted below and try again. No rates have been changed/ imported.</p>
@endif

<table border="0" cellspacing="0" width="100%" class="table">
'rate_id', 'service', 'packaging_code', 'first', 'others', 'notional_weight', 'notional', 'area', 'from_date', 'to_date'

    <thead>
        <tr>
            <th>#</th>
            <th>Rate Id</th>
            <th>Service</th>
            <th>Packaging Code</th>
            <th>First</th>
            <th>Others</th>
            <th>Notional Weight</th>
            <th>Notional</th>
            <th>Area</th>
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
        <td @if(isset($result['fields_in_error']['rate_id'])) class="field-error" @endif>{{$result['data']['rate_id']}}</td>
        <td @if(isset($result['fields_in_error']['service'])) class="field-error" @endif>{{$result['data']['service']}}</td>
        <td @if(isset($result['fields_in_error']['packaging_code'])) class="field-error" @endif>{{$result['data']['packaging_code']}}</td>
        <td @if(isset($result['fields_in_error']['first'])) class="field-error" @endif>{{$result['data']['first']}}</td>
        <td @if(isset($result['fields_in_error']['others'])) class="field-error" @endif>{{$result['data']['others']}}</td>
        <td @if(isset($result['fields_in_error']['notional_weight'])) class="field-error" @endif>{{$result['data']['notional_weight']}}</td>
        <td @if(isset($result['fields_in_error']['notional'])) class="field-error" @endif>{{$result['data']['notional']}}</td>
        <td @if(isset($result['fields_in_error']['area'])) class="field-error" @endif>{{$result['data']['area']}}</td>
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
