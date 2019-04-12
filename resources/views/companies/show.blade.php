@extends('layouts.app')

@section('content')

<div class="clearfix">
    <h2 class="float-left">
        {{$company->company_name}}           
    </h2>
    <h2 class="float-right">
        @if($company->enabled)
        @if($company->testing)
        <span class="badge badge-disabled float-right">Test Mode</span>
        @else
        <span class="badge badge-enabled float-right">Live</span>
        @endif
        @else
        <span class="badge badge-disabled float-right">Disabled</span>
        @endif
    </h2>
</div>

<div class="row mb-4">
    <div class="col-sm-8">
        <div class="card h-100">
            <div class="card-header">Company Info</div>
            <div class="card-body text-large">
                <div class="row">
                    <div class="col-sm-5">
                        <strong>{{$company->company_name}}</strong><br>
                        {{$company->address1}}<br>
                        @if($company->address2){{$company->address2}}<br>@endif
                        @if($company->address3){{$company->address3}}<br>@endif
                        {{$company->city}}<br>
                        {{$company->state}} {{$company->postcode}}<br>
                        {{$company->country}}<br><br>
                    </div>

                    <div class="col-sm-7">                        
                        <div class="row mb-2">
                            <div class="col-sm-5 text-truncate"><span class="fas fa-fw fa-tag" aria-hidden="true"></span> <strong class="ml-sm-3">Site Name</strong></div>
                            <div class="col-sm-7">{{$company->site_name}}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-truncate"><span class="fas fa-fw fa-phone" aria-hidden="true"></span> <strong class="ml-sm-3">Telephone</strong></div>
                            <div class="col-sm-7">{{$company->telephone}}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-truncate"><span class="fas fa-fw fa-building-o" aria-hidden="true"></span> <strong class="ml-sm-3">Company Code</strong></div>
                            <div class="col-sm-7"><code>{{$company->company_code}}</code></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-truncate"><span class="fas fa-fw fa-map-marker" aria-hidden="true"></span> <strong class="ml-sm-3">Depot</strong></div>
                            <div class="col-sm-7"><span class="badge badge-secondary" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$company->depot->name}}">{{$company->depot->code}}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">Account Details
                <div class="float-right">
                    @include('companies.partials.actions', ['company' => $company])                    
                </div>              
            </div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-tasks" aria-hidden="true"></span> <strong class="ml-sm-3">Services</strong></div>
                    <div class="col-sm-6">
                        @if($company->uses_default_services)
                        <span class="badge badge-dark">Default</span>
                        @else
                        <span class="badge badge-primary">Defined</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-print fa-fw" aria-hidden="true"></span> <strong class="ml-sm-3">Label Size</strong></div>
                    <div class="col-sm-6">{{$company->label_size}}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-cog" aria-hidden="true"></span> <strong class="ml-sm-3">Localisation</strong></div>
                    <div class="col-sm-6 text-truncate">{{$company->localisation->time_zone}}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-folder-open" aria-hidden="true"></span> <strong class="ml-sm-3">SCS Code</strong></div>
                    <div class="col-sm-6">
                        {{$company->scs_code}}                        
                        @if($company->group_account)
                            <span class="badge badge-warning ml-2 text-uppercase">{{$company->group_account}}</span>
                        @endif                        
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-file-invoice " aria-hidden="true"></span> <strong class="ml-sm-3">EORI</strong></div>
                    <div class="col-sm-6">{{$company->eori}}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-credit-card" aria-hidden="true"></span> <strong class="ml-sm-3">VAT Exempt</strong></div>
                    <div class="col-sm-6">
                        @if($company->vat_exempt)
                        Yes
                        @else
                        No
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-user" aria-hidden="true"></span> <strong class="ml-sm-3">Salesperson</strong></div>
                    <div class="col-sm-6">{{$company->salesperson ?? 'House'}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6 text-truncate"><span class="fas fa-fw fa-calendar" aria-hidden="true"></span> <strong class="ml-sm-3">Date Created</strong></div>
                    <div class="col-sm-6">{{$company->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</div>
                </div>
            </div>
        </div>
    </div>
</div>




<h4 class="mb-2">Users <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$company->users->count()}}</span></h4>

@include('users.partials.users', ['users' => $company->users, 'class' => 'bordered', 'iteration' => true])


<h4 class="mb-2">
    @if($company->uses_defined_services)
    Services (Defined)
    @else
    Services (Defaulted)
    @endif

    <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{count($company->getServices())}}</span>
</h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>IFS Service</th>
            <th class="text-center">Code</th>
            <th>Carrier</th>
            <th>Carrier Service</th>
            <th class="text-center">Carrier Code</th>
            <th>Rate Loaded</th>
            <th class="text-center">Disc</th>
            <th class="text-center">Fuel Cap</th>
            @can('set_company_rates')
            <th class="text-center"></th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($company->getServices() as $key => $service)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{(isset($service->pivot->name)) ?: $service->name}}</td>
            <td class="text-center"><span class="badge badge-secondary text-uppercase">{{$service->code}}</span></td>
            <td>{{isset($service->carrier->name) ? $service->carrier->name : "Unknown"}}</td>
            <td>{{isset($service->carrier_name) ? $service->carrier_name : "Unknown"}}</td>
            <td class="text-center"><span class="text-uppercase">{{$service->carrier_code}}</span></td>
            @if(!$company->legacy_pricing || Auth::user()->hasRole('ifsa'))

            <td @if($company->legacy_pricing) class="bg-warning" @endif>
                 <a href="{{ url('/company-rate/' . $company->id . '/'. $service->id) . "/" . $company->salesRateForService($service->id)['discount'] }}">{{$company->salesRateForService($service->id)['description']}}</a>
            </td>

            @if($company->salesRateForService($service->id)['special_discount'])
            <td class="text-right">Special</td>
            @else
            <td class="text-right">{{number_format($company->salesRateForService($service->id)['discount'],2)}}</td>
            @endif
            <td class="text-right">{{number_format($company->salesRateForService($service->id)['fuel_cap'],2)}}</td>
            @else
            <td class="text-left">See Legacy System</td>
            <td class="text-right">See Legacy System</td>
            <td class="text-right">See Legacy System</td>
            @endif
            @can('set_company_rates')
            <td class="text-center text-nowrap">
                <a href="{{ url('/company-service-rate/' . $company->id . '/'.$service->id) }}" title="Set Rate"><span class="fas fa-fw fa-edit" aria-hidden="true"></span></a>
                <a href="{{ url('/company-rate/' . $company->id . '/'.$service->id) . '/download' }}" title="Download Rate"><span class="fas fa-cloud-download-alt ml-sm-2" aria-hidden="true"></span></a>                
                <a href="{{ url('/company-rate/' . $company->id . '/'.$service->id) . '/upload' }}" title="Upload Rate"><span class="fas fa-cloud-upload-alt ml-sm-2" aria-hidden="true"></span></a>
                <a href="{{ url('/company-service-rate/' . $company->id . '/'.$service->id) . "/delete" }}" title="Reset to Service Default Rate"><span class="fas fa-fw fa-times ml-sm-2" aria-hidden="true"></span></a>                    
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>

<h4 class="mb-2">Latest Shipments <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$latestShipments->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Consignment No.</th>
            <th>Recipient</th>
            <th>Reference</th>
            <th>Ship Date</th>
            <th class="text-center">Service</th>
            <th class="text-center">Pieces</th>
            <th class="text-center">Transit</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($latestShipments as $shipment)
        <tr>
            <td>{{$loop->iteration}}</td>
            @if($shipment->status->code == 'saved')
            <td><a href="{{url('/shipments/' . $shipment->id . '/edit') }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            @else
            <td><a href="{{ url('/shipments', $shipment->id) }}" class="consignment-number">{{$shipment->consignment_number}}</a></td>
            @endif
            <td>{{$shipment->recipient_company_name ?: $shipment->recipient_name}}, {{$shipment->recipient_city}}, {{$shipment->recipient_country_code}}</td>
            <td>{{$shipment->shipment_reference}}</td>
            <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
            <td class="text-center"><span class="badge badge-secondary text-uppercase" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->service->name ?? 'Unknown'}}">{{$shipment->service->code ?? ''}}</span></td>
            <td class="text-center">{{$shipment->pieces}}</td>
            <td class="text-center">{{$shipment->timeInTransit}}<span class="hours">hrs</span></td>
            <td class="text-center"><span class="status {{$shipment->status->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->status->description}}">{{$shipment->status->name}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.log', ['logs' => $company->logs])

@endsection