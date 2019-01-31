<h4 class="mb-2">{{$title}}</h4>
<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr class="{{$class}}">
            <th width="2%">#</th>
            <th width="12%">Consignment No.</th>
            <th width="8%">Carrier</th>
            <th width="5%" class="text-center">Route</th>
            <th width="12%">Carrier Consignment</th>
            <th width="15%">Shipper</th>
            <th width="15%">Destination</th>
            <th width="8%">Ship Date </th>
            <th width="6%" class="text-center">Service</th>
            <th width="8%" class="text-right">Pieces</th>
            <th width="8%" class="text-right">Weight</th>
            @can('run_manifest')<th class="text-center">Hold</th>@endcan
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $i => $shipment)
        <tr>
            <td>{{$i + 1}}</td>
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            <td>{{$shipment->carrier->name}}</td>
            <td class="text-center">{{$shipment->route->code ?? ''}}</td>
            <td>{{$shipment->carrier_consignment_number}}</td>
            <td>{{$shipment->company->company_name ?? ''}}</td>
            <td>{{$shipment->recipient_city}}, {{$shipment->recipient_country_code}}</td>
            <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-center"><span class="text-uppercase badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
            <td class="text-right">{{$shipment->pieces}}</td>
            <td class="text-right">{{$shipment->weight}}</td>
            @can('run_manifest')
            <td class="text-center">
                @if($shipment->on_hold)
                <a href="{{ url('/shipments/' . $shipment->id . '/hold') }}" title="Release Shipment" class="hold-shipment"><span class="far fa-check-circle" aria-hidden="true"></span></a>
                @else
                <a href="{{ url('/shipments/' . $shipment->id . '/hold') }}" title="Hold Shipment" class="hold-shipment"><span class="fas fa-stop-circle" aria-hidden="true"></span></a>
                @endif
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
