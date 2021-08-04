@extends('layouts.mail')

@section('content')

<h1>International Master Rate Import</h1>

<p><b>Records Inserted:</b> {{$results['summary']['inserted']}}</p>
<p><b>Records Failed:</b> {{$results['summary']['failed']}}</p>

@if($results['summary']['failed'] > 0)
<p>Please correct the errors highlighted below and try again. No rates have been changed/ imported.</p>
@endif

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Rate id</th>
            <th>Residential</th>
            <th>Piece_limit</th>
            <th>Package Type</th>
            <th>Zone</th>
            <th>Break Point</th>
            <th>Weight Rate</th>
            <th>Weight Increment</th>
            <th>Package Rate</th>
            <th>Consignment Rate</th>
            <th>Weight Units</th>
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
        <td @if(isset($result['fields_in_error']['residential'])) class="field-error" @endif>{{$result['data']['residential']}}</td>
        <td @if(isset($result['fields_in_error']['piece_limit'])) class="field-error" @endif>{{$result['data']['piece_limit']}}</td>
        <td @if(isset($result['fields_in_error']['package_type'])) class="field-error" @endif>{{$result['data']['package_type']}}</td>
        <td @if(isset($result['fields_in_error']['zone'])) class="field-error" @endif>{{$result['data']['zone']}}</td>
        <td @if(isset($result['fields_in_error']['break_point'])) class="field-error" @endif>{{$result['data']['break_point']}}</td>
        <td @if(isset($result['fields_in_error']['weight_rate'])) class="field-error" @endif>{{$result['data']['weight_rate']}}</td>
        <td @if(isset($result['fields_in_error']['weight_increment'])) class="field-error" @endif>{{$result['data']['weight_increment']}}</td>
        <td @if(isset($result['fields_in_error']['package_rate'])) class="field-error" @endif>{{$result['data']['package_rate']}}</td>
        <td @if(isset($result['fields_in_error']['consignment_rate'])) class="field-error" @endif>{{$result['data']['consignment_rate']}}</td>
        <td @if(isset($result['fields_in_error']['weight_units'])) class="field-error" @endif>{{$result['data']['weight_units']}}</td>
        <td @if(isset($result['fields_in_error']['from_date'])) class="field-error" @endif>{{$result['data']['from_date']}}</td>
        <td @if(isset($result['fields_in_error']['to_date'])) class="field-error" @endif>{{$result['data']['to_date']}}</td>
        <td>
            @if(isset($result['errors']))
            <span class="error">Failed</span>
            @else
                @if($results['summary']['inserted']>0)
                <span class="inserted">Inserted</span>
                @endif
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
