@extends('layouts.app')

@section('navSearchPlaceholder', 'Shipment search...')

@section('advanced_search_form')

<input type="hidden" name="mode" value="{{Input::get('mode')}}">

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Shipment number, reference or destination:
    </label>
    <div class="col-sm-7">
        {!! Form::Text('filter', Input::get('filter'), ['class' => 'form-control', 'maxlength' => '50']) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date from:
    </label>
    <div class="col-sm-7">
        <input type="text" name="date_from" value="{{Input::get('date_from')}}" class="form-control datepicker" placeholder="">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Date To:
    </label>
    <div class="col-sm-7">
        <input type="text" name="date_to" value="{{Input::get('date_to')}}" class="form-control datepicker" placeholder="">
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Shipper:
    </label>
    <div class="col-sm-7">
        {!! Form::select('company', dropDown('sites', 'All Shippers'), Input::get('company'), array('class' => 'form-control')) !!}
    </div>   
</div>

<div class="form-group row">
    <label class="col-sm-4  col-form-label">
        Status:
    </label>
    <div class="col-sm-7">
        {!! Form::select('status', dropDown('seaFreightStatuses', 'All Statuses'), Input::get('status'), array('class' => 'form-control')) !!}
    </div>   
</div>

@endsection

@include('partials.modals.advanced_search')

@section('content')

@include('partials.title', ['title' => 'Shipment History', 'badge' => 'Sea', 'results'=> $shipments])

{!! Form::Open(['url' => 'sea-freight', 'autocomplete' => 'off']) !!}

<table class="table table-striped">
    <thead>
        <tr>
            <th>Shipment</th>

            @if(Auth::user()->hasIfsRole())
            <th>Shipper</th>
            @else
            <th>Reference</th>
            @endif

            <th>Destination</th>
            <th class="text-center">Containers</th>                
            <th class="text-center">Required On Dock</th>                
            <th class="text-center">Transit</th>
            <th class="text-center">Remaining</th>
            <th class="text-center">Status</th>
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>

        @foreach($shipments as $shipment)

        <tr>
            <td><a href="{{ url('/sea-freight', $shipment->id) }}" class="consignment-number">{{$shipment->number}}</a></td>

            @if(Auth::user()->hasIfsRole())
            <td>{{$shipment->company->company_name}}</td>
            @else
            <td>{{$shipment->reference}}</td>
            @endif

            <td><span data-placement="bottom" data-toggle="tooltip" data-original-title="{{getCountry($shipment->final_destination_country_code)}}">{{$shipment->final_destination}}, {{$shipment->final_destination_country_code}}</span></td>

            <td class="text-center">
                @if($shipment->containers->count() != $shipment->number_of_containers)
                <span class="text-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Container detail required">{{$shipment->containers->count()}} of {{$shipment->number_of_containers}}</span>
                @else
                <span class="badge badge-secondary">{{$shipment->number_of_containers}}</span>
                @endif
            </td>

            <td class="text-center">  
                {{$shipment->required_on_dock_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
            </td>
            <td class="text-center">{{$shipment->timeInTransit}} Days</td>
            <td class="text-center">
                @if($shipment->timeRemaining < 0)
                <span class="text-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Shipment behind schedule">{{$shipment->timeRemaining}} Days</span>
                @else
                {{$shipment->timeRemaining}} Days
                @endif
            </td>
            <td class="text-center">
                <span class="status {{$shipment->seaFreightStatus->code}}" data-placement="bottom" data-toggle="tooltip" data-original-title="{{$shipment->seaFreightStatus->description}}">{{$shipment->seaFreightStatus->name}}</span>
            </td>
            <td class="text-center">

                <!-- Cancel shipment - only enable if active shipment and not yet received -->
                @if($shipment->isCancellable())
                <a href="{{ url('/sea-freight/' . $shipment->id . '/cancel') }}" title="Cancel Shipment" class="cancel-sea-freight-shipment"><span class="fas fa-times ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-times ml-sm-2 faded" aria-hidden="true" title="Cancel Shipment"></span>
                @endif

                @if (!$shipment->processed && $shipment->seaFreightStatus->code != 'cancelled')
                <a href="{{ url('/sea-freight/' .  $shipment->id . '/edit') }}" title="Edit Shipment" class="edit-shipment"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-edit ml-sm-2 faded" aria-hidden="true" title="Edit Shipment"></span>
                @endif

                @if(Auth::user()->hasIfsRole())

                @if($shipment->isActive() && ($shipment->containers->count() < $shipment->number_of_containers || !$shipment->processed))
                <a href="{{ url('/sea-freight/' .  $shipment->id . '/process') }}" title="Process Shipment"><span class="fas fa-forward ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-forward ml-sm-2 faded" aria-hidden="true" title="Process Shipment"></span>
                @endif

                @endif

                @if ($shipment->isActive())
                <a href="{{ url('/documents/create/sea-freight-shipment/' . $shipment->id) }}" title="Add Supporting Documents" class="supporting-docs"><span class="fas fa-file ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-file ml-sm-2 faded" aria-hidden="true" title="Add Supporting Documents"></span>
                @endif

                @if(Auth::user()->hasIfsRole())

                @if ($shipment->processed && $shipment->isActive())
                <a href="{{ url('/sea-freight/' .  $shipment->id . '/status') }}" title="Update Status"><span class="fas fa-flag ml-sm-2" aria-hidden="true"></span></a>
                @else
                <span class="fas fa-flag ml-sm-2 faded" aria-hidden="true" title="Update Status"></span>
                @endif

                @endif

            </td>
        </tr>
        @endforeach
    </tbody>

</table>

{!! Form::Close() !!}

@include('partials.no_results', ['title' => 'sea freight shipments', 'results'=> $shipments])
@include('partials.pagination', ['results'=> $shipments])

@endsection