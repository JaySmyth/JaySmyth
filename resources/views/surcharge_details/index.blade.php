@extends('layouts.app')

@section('content')
@include('partials.title', ['title' => $title, 'create' => 'surchargedetails', 'url' => 'surchargedetails'])

<div class="table table-striped-responsive">
    <table class="table table-striped">        
        <thead>
            <tr>
                <th>Description</th> 
                <th>Code</th> 
                <th class="text-right">Weight Rate</th> 
                <th class="text-right">Package Rate</th> 
                <th class="text-right">Consignment Rate</th> 
                <th class="text-right">Min</th> 
                <th class="text-right">From Date</th> 
                <th class="text-right">To Date</th> 
                <th>&nbsp;</th>
            </tr>
        </thead>        
        <tbody>
            @foreach($surcharges as $surcharge)
            <tr>
                <td class="text-left">{{$surcharge->name}}</td>
                <td class="text-left">{{$surcharge->code}}</td>
                <td class="text-right">{{$surcharge->weight_rate}}</td>
                <td class="text-right">{{$surcharge->package_rate}}</td>
                <td class="text-right">{{$surcharge->consignment_rate}}</td>
                <td class="text-right">{{$surcharge->min}}</td>
                <td class="text-right">{{$surcharge->from_date}}</td>
                <td class="text-right">{{$surcharge->to_date}}</td>
                <td class="text-left text-nowrap">
                    @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/edit/' . $companyId) }}" title="Edit Company Specific SurCharge"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan
                    @if($surcharge->company_id > 0)
                    @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/delete/' . $companyId) }}" title="Delete Company Specific SurCharge"><span class="fas fa-times ml-sm-2" aria-hidden="true"></span></a>@endcan
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection