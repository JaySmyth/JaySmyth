@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => $title, 'create' => '','url' => 'surchargedetails/'. $surcharges[0]->surcharge_id . '/' . $companyId])
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
                <?php $style="font-regular"; ?>
                <tr">
                    <td class="text-left">
                        @if($surcharge->company_id != 0)
                            <span class="badge badge-secondary" aria-hidden="true" data-placement="right" data-toggle="tooltip" data-original-title="Company specific">S</span>
                        @endif
                        {{$surcharge->name}}
                    </td>
                    <td class="text-left">{{$surcharge->code}}</td>
                    <td class="text-right">{{$surcharge->weight_rate}}</td>
                    <td class="text-right">{{$surcharge->package_rate}}</td>
                    <td class="text-right">{{$surcharge->consignment_rate}}</td>
                    <td class="text-right">{{$surcharge->min}}</td>
                    <td class="text-right">{{$surcharge->from_date}}</td>
                    <td class="text-right">{{$surcharge->to_date}}</td>
                    <td class="text-left text-nowrap">
                        @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/' . $companyId . '/edit/') }}" title="Edit Company Specific SurCharge"><span class="fas fa-edit" aria-hidden="true"></span></a>@endcan
                        @if($surcharge->company_id > 0)
                        @can('view_surcharges')<a href="{{ url('/surchargedetails/' . $surcharge->id . '/' . $companyId . '/delete/') }}" title="Delete Company Specific SurCharge"><span class="fas fa-times ml-sm-2 delete mr-2" data-record-name="entry" aria-hidden="true"></span></a>@endcan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="font-italic">
        Note: Company specific surcharges override default surcharges.
    </p>
</div>

@endsection
