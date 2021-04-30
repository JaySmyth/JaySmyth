@extends('layouts.mail')

@section('content')

    <h1>{{$subject}}</h1>

    @if(count($data) > 0)

        @foreach($data as $carrierId => $rows)
            <h2 class="info">Carrier: {{ App\Models\Carrier::find($carrierId)->name }}</h2>

            <table border="0" cellspacing="0" width="100%" class="table">

                <thead>
                <tr>
                    <th>#</th>
                    <th>Consignment</th>
                    <th>Carrier</th>
                    <th>Sender</th>
                    <th>Recipient</th>
                    <th>Pieces</th>
                    <th>Weight</th>
                    <th>Service</th>
                    <th>Shipment Date</th>
                </tr>
                </thead>

                <tbody>
                @foreach($rows as $shipment)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><a href="{{url('/shipments', $shipment->id)}}" title="View Shipment">{{$shipment->consignment_number}}</a></td>
                        <td>{{$shipment->carrier_consignment_number}}</td>
                        <td>{{$shipment->sender_company_name}}</td>
                        <td>{{$shipment->recipient_name
                            .', '.$shipment->recipient_company_name
                            .', '.$shipment->recipient_city
                            .', '.$shipment->recipient_postcode
                            .', '.$shipment->recipient_country_code
                        }}</td>
                        <td>{{$shipment->pieces}}</td>
                        <td>{{$shipment->weight}}</td>
                        <td>{{App\Models\Service::find($shipment->service_id)->code}}</td>
                        <td>{{$shipment->ship_date->format('Y-m-d')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach

    @endif

@endsection
