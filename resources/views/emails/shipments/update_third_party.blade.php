@extends('layouts.mail')

@section('content')

<h1 class="mb-3">{{$results['subject']}}</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>#</th>
            <th>Consignment Number</th>
            <th>Shipment Reference</th>
            <th>Carrier Consignment Number</th>

            @if(count($results['failed']) > 0)
            <th width="20%">Errors</th>
            @endif
        </tr>
    </thead>

    @if(count($results['rows']) > 0)
    @foreach($results['rows'] as $key => $result)

    <tr>
        <td>{{$loop->iteration}}</td>
        <td>
            @if(isset($result['data']['ReferenceNumber']))
            {{$result['data']['ReferenceNumber']}}
            @else
            null
            @endif
        </td>
        <td>
            @if(isset($result['data']['PurchaseOrderNumber']))
            {{$result['data']['PurchaseOrderNumber']}}
            @else
            null
            @endif
        </td>
        <td>
            @if(isset($result['data']['TrackingNum']))
            {{$result['data']['TrackingNum']}}
            @else
            null
            @endif
        </td>

        @if(count($results['failed']) > 0)
        <td class="error-summary">
            @if(isset($results['failed'][$key]['errors']))

            @if(is_array($results['failed'][$key]['errors']))

            @foreach($results['failed'][$key]['errors'] as $error)
            * {{ucfirst($error)}}<br>
            @endforeach


            @else

            * {{ucfirst($results['failed'][$key]['errors'])}}

            @endif


            @endif
        </td>
        @endif
    </tr>

    @endforeach
    @endif

</table>

@endsection