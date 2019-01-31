@extends('layouts.mail')

@section('content')

<h1 class="mb-3">{{$results['subject']}}</h1>

@if(count($results['failed']) > 0)
<h2>Failed records are detailed with in the <u>attached "failed.csv"</u> and also highlighted below. Please use the attached <span class="error">"failed.csv"</span> for your corrected upload.</h2>
@endif

<p><i>Upload performed by {{$results['user']['name']}}</i>.</p>

@if(count($results['success']) > 0)
<a href="{{url('/labels/' . $results['source'] . '/' . $results['user']['id'])}}"><h2>Download Labels Here</h2></a>

@if($results['commercial_invoice_count'] > 0)
<a href="{{url('/commercial-invoices/' . $results['source'])}}"><h2>Download Commercial Invoices Here</h2></a>
@endif

@endif

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Shipment Reference</th>
            <th>Recipient</th>
            <th>Service</th>
            <th>Status</th>

            @if(count($results['success']) > 0)
            <th>Carrier</th>
            <th>Carrier Reference</th>
            @endif

            @if(count($results['failed']) > 0)
            <th width="20%">Errors</th>
            @endif
        </tr>
    </thead>

    @if(count($results['rows']) > 0)
    @foreach($results['rows'] as $key => $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$result['data']['shipment_reference']}}</td>
        <td>{{$result['data']['recipient_name']}}, {{$result['data']['recipient_city']}} {{$result['data']['recipient_country_code']}}</td>
        <td>@if(isset($results['failed'][$key]['errors']))
                @if(isset($result['data']['service_code']))
                    {{$result['data']['service_code']}}    
                @else
                    &nbsp;
                @endif
            @else
            {{$results['success'][$key]['service_code']}}
            @endif
        </td>
        <td>
            @if(isset($results['failed'][$key]['errors']))
            <span class="error">Failed</span>
            @else
            <span class="inserted">Created</span> <a href="{{$results['success'][$key]['tracking_url']}}">{{$results['success'][$key]['consignment_number']}}</a>
            @endif
        </td>

        @if(count($results['success']) > 0)
        <td>
            @if(isset($results['success'][$key]))
            {{$results['success'][$key]['carrier']}}
            @else
            n/a
            @endif
        </td>
        <td>
            @if(isset($results['success'][$key]))
            {{$results['success'][$key]['carrier_consignment_number']}}
            @else
            n/a
            @endif
        </td>
        @endif

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