@extends('layouts.mail')

@section('content')

<h1 class="mb-3">Babocush Tracking Numbers</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <thead>
        <tr>
            <th>Consignment Number</th>
            <th>Shipment Reference</th>
            <th>Carrier Consignment Number</th>
        </tr>
    </thead>

    @if(count($results['rows']) > 0)
    
    @foreach($results['rows'] as $key => $result)

        @if(count($results['failed']) == 0)
        <tr>
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
        </tr>
        @endif

    @endforeach
    @endif

</table>

@endsection