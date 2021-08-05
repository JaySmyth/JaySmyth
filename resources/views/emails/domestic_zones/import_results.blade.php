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
            <th>Postcode</th>
            <th>Zone</th>
            <th>Model</th>
            <th>SLA</th>
            <th>Status</th>
            <th>Comment</th>
        </tr>
    </thead>

    @foreach($results['rows'] as $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        @if(isset($result['data']))
        <td @if(isset($result['fields_in_error']['postcode'])) class="field-error" @endif>{{$result['data']['postcode']}}</td>
        <td @if(isset($result['fields_in_error']['zone'])) class="field-error" @endif>{{$result['data']['zone']}}</td>
        <td @if(isset($result['fields_in_error']['model'])) class="field-error" @endif>{{$result['data']['model']}}</td>
        <td @if(isset($result['fields_in_error']['sla'])) class="field-error" @endif>{{$result['data']['sla']}}</td>
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
