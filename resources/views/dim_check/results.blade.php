@extends('layouts.app')

@section('content')

    <h4>Dim Check Results <span class="badge badge-pill badge-sm badge-secondary">{{ count($results) }}</span></h4>
    <table class="table table-sm table-striped table-bordered mb-5">
        <thead>
        <tr class="active">
            <th>#</th>
            <th>Consignment</th>
            <th>Carrier Number</th>
            <th>Ship Date</th>
            <th>Consignor</th>
            <th class="text-right">Weight</th>
            <th class="text-right">Carrier Weight</th>
            <th class="text-right">Volumetric Weight</th>
            <th class="text-right">Carrier Volumetric Weight</th>
            <th class="text-center">DIMS Updated</th>
            <th class="text-center">Flag</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{$loop->iteration}}</td>

                @if($result['consignment_number'])
                    <td>
                        <a href="{{ url('/shipments', $results['shipment_id']) }}" class="consignment-number">{{ $result['consignment_number'] }}</a>
                    </td>
                    <td>{{ $result['carrier_consignment_number'] }}</td>
                    <td>{{ $result['ship_date'] }}</td>
                    <td>{{ $result['sender_company_name'] }}</td>
                    <td class="text-right">{{ $result['weight'] }}</td>
                    <td class="text-right">{{ $result['carrier_weight'] }}</td>
                    <td class="text-right">{{ $result['volumetric_weight'] }}</td>
                    <td class="text-right">{{ $result['carrier_volumetric_weight'] }}</td>
                    <td class="text-center">{{ $result['dims_updated'] }}</td>
                    <td class="text-center">{{ $result['flag'] }}</td>
                @else
                    <td class="text-danger text-center font-weight-bold" colspan="10">{{ $result['carrier_consignment_number'] }} not found!</td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>

@endsection