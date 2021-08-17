@extends('layouts.app')

@section('content')

<div class="table table-striped-responsive">
    <h2>IFS Web Client Defined Services</h2>

    @foreach($services as $depotId =>$depotCarrierServices)
    <h3>Depot - {{ App\Models\Depot::find($depotId)->name ?? $depotId}}</h3>

    @foreach($depotCarrierServices as $carrierId => $carrierServices)
    <h4>Carrier - {{ App\Models\Carrier::find($carrierId)->name ?? 'None'}}</h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">id</th>
                <th>Carrier</th>
                <th>Code</th>
                <th>IFS Name</th>
                <th>Carrier Name</th>
                <th class="text-center">Account</th>
                <th class="text-center">Divisor</th>
                <th class="text-center">Min kg</th>
                <th class="text-center">Max Kg</th>
                <th class="text-center">Max Dim</th>
                <th class="text-center">Max Girth</th>
                <th class="text-center">Max Customs</th>
                <th class="text-center">Packaging</th>
                <th class="text-center">Depot</th>
                <th class="text-center">From Countries</th>
                <th class="text-center">To Countries</th>
                <th class="text-center">From PostCodes</th>
                <th class="text-center">To PostCodes</th>
                <th class="text-center">Cost Rate</th>
                <th class="text-center">Cost Surch</th>
                <th class="text-center">Def Sales</th>
                <th class="text-center">Sales Surch</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carrierServices as $service)
            <tr>
                <td class="text-center">
                    @if(Auth::user()->hasRole('ifsa'))
                    <a class="nav-link" href="{{url('/services')}}/{{$service->id.'/edit'}}" class="right-border">{{ $service->id }}</a>
                    @else
                    {{ $service->id }}
                    @endif

                </td>
                <td>{{ App\Models\Carrier::find($service->carrier_id)->code ?? 'None'}}</td>
                <td>{{ $service->code }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->carrier_name }}</td>
                <td class="text-center">{{ $service->account }}</td>
                <td class="text-center">{{ $service->volumetric_divisor }}</td>
                <td class="text-center">{{ $service->min_weight }}</td>
                <td class="text-center">{{ $service->max_weight }}</td>
                <td class="text-center">{{ $service->max_dimension }}</td>
                <td class="text-center">{{ $service->max_girth }}</td>
                <td class="text-center">{{ $service->max_customs_value }}</td>
                <td class="text-center">{{ $service->packaging_types }}</td>
                <td class="text-center">{{ $service->depot_id }}</td>
                <td class="text-center">{{ cleanRegex($service->sender_country_codes) }}</td>
                <td class="text-center">{{ cleanRegex($service->recipient_country_codes) }}</td>
                <td class="text-center">{{ cleanRegex($service->sender_postcode_regex) }}</td>
                <td class="text-center">{{ cleanRegex($service->recipient_postcode_regex) }}</td>
                <td class="text-center">
                    @if($service->cost_rate_id==0)
                        None
                    @else
                        <a class="nav-link" href="{{url('/rates')}}/{{$service->cost_rate_id}}" class="right-border">{{ $service->cost_rate_id }}</a>
                    @endif
                </td>
                <td class="text-center">
                    @if($service->costs_surcharge_id==0)
                        None
                    @else
                        <a class="nav-link" href="{{url('/surchargedetails')}}/{{$service->costs_surcharge_id}}/0/index" class="right-border">{{ $service->costs_surcharge_id }}</a>
                    @endif
                </td>
                <td class="text-center">
                    @if($service->sales_rate_id==0)
                        None
                    @else
                        <a class="nav-link" href="{{url('/rates')}}/{{$service->sales_rate_id}}" class="right-border">{{ $service->sales_rate_id }}</a>
                    @endif
                </td>
                <td class="text-center">
                    @if($service->sales_surcharge_id==0)
                        None
                    @else
                        <a class="nav-link" href="{{url('/surchargedetails')}}/{{$service->sales_surcharge_id}}/0/index" class="right-border">{{ $service->sales_surcharge_id }}</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    @endforeach
</div>


@endsection
