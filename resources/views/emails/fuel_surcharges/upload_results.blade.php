@extends('layouts.mail')

@section('content')

<h1>{{$results['subject']}}</h1>

@if(count($results['failed']) > 0)
<h2>Failed records are detailed with in the <u>attached "failed.csv"</u> and also highlighted below. Please use the attached <span class="error">"failed.csv"</span> for your corrected upload.</h2>
@endif

<p><i>Upload performed by {{$results['user']['name']}}</i>.</p>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Carrier</th>
            <th>Service Code</th>
            <th>Fuel %</th>
            <th>From Date</th>
            <th>To Date</th>

            @if(count($results['failed']) > 0)
            <th width="20%">Errors</th>
            @endif
        </tr>
    </thead>

    @if(count($results['rows']) > 0)
    @foreach($results['rows'] as $key => $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$result['data']['carrier_code']}}</td>
        <td>{{$result['data']['service_code']}}</td>
        <td>{{$result['data']['fuel_percent']}}</td>
        <td>{{$result['data']['from_date']}}</td>
        <td>{{$result['data']['to_date']}}</td>

        @if(count($results['failed']) > 0)
        <td class="error-summary">
            @if(isset($results['failed'][$key]['errors']))
            @foreach($results['failed'][$key]['errors'] as $error)
            * {{ucfirst($error)}}<br>
            @endforeach
            @endif
        </td>
        @endif
    </tr>

    @endforeach
    @endif

</table>

@endsection