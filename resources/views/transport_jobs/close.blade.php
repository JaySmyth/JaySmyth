@extends('layouts.app')

@section('content')

{!! Form::Open(['url' => 'transport-jobs/close', 'class' => '']) !!}

<div class="row">

    <div class="col-sm-4">

        <h2>Close Job</h2>


        <div class="form-group row{{ $errors->has('number') ? ' has-danger' : '' }}">

            <label class="col-sm-5  col-form-label">
                Job / Consignment: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('number', old('number'), ['class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('number'))
                <span class="form-text">
                    <strong>{{ $errors->first('number') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('signature') ? ' has-danger' : '' }}">
            <label class="col-sm-5  col-form-label">
                Signature: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-7">
                {!! Form::Text('signature', old('signature'), ['class' => 'form-control', 'maxlength' => '30']) !!}

                @if ($errors->has('signature'))
                <span class="form-text">
                    <strong>{{ $errors->first('signature') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-5  col-form-label">
                Date / Time: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-4">
                {!! Form::select('date', dropDown('dates'), old('date', date('d-m-Y', strtotime('today'))), array('id' => 'date', 'class' => 'form-control')) !!}

                @if ($errors->has('date'))
                <span class="form-text">
                    <strong>{{ $errors->first('date') }}</strong>
                </span>
                @endif

            </div>

            <div class="col-sm-3">
                {!! Form::select('time', dropDown('times'), old('time', date('H:i')), array('id' => 'time', 'class' => 'form-control')) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-5">&nbsp;</div>
            <div class="col-sm-7">
                <a class="back btn btn-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">POD Shipment</button>
            </div>
        </div>

    </div>
    <div class="col-sm-1"></div>

    <div class="col-sm-7">

        <br>

        @if($deliveries->count() > 30)

        @include('partials.alert', ['class' => 'alert-danger', 'heading' => 'Deliveries Backlog', 'body' => 'A high number of delivery requests need POD information. Please action as soon as possible. This board should be kept clear as best practice.'])

        @endif

        <h4 class="mb-2">Deliveries - Awaiting Proof Of Delivery <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $deliveries->count()}}</span></h4>

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Job Number</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Deliver To</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveries as $transportJob)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><a href="{{ url('/transport-jobs', $transportJob->id) }}">{{$transportJob->number}}</a></td>
                    <td>
                        @if($transportJob->shipment)
                        <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                        @else
                        {{$transportJob->reference}}
                        @endif
                    </td>
                    <td>
                        @if($transportJob->date_requested)
                        {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        @else
                        Not defined
                        @endif
                    </td>
                    <td>
                        @if($transportJob->type == 'c')
                        {{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}}
                        @else
                        {{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}}
                        @endif
                    </td>

                    <td class="text-center">                
                        <a href="{{ url('/transport-jobs/' . $transportJob->id . '/unmanifest') }}" title="Unmanifest Job"><span class="fas fa-undo" aria-hidden="true"></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        @if($collections->count() > 15)

        @include('partials.alert', ['class' => 'alert-danger', 'heading' => 'Collections Backlog', 'body' => 'A high number of collection requests need proof of collection. Use the "TP" job number in the form above when providing date/signature information. Please action as soon as possible. This board should be kept clear as best practice.'])

        @endif

        <h4 class="mb-2">Collections - Awaiting Proof Of Collection <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{ $collections->count()}}</span></h4>

        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Job Number</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Collect From</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($collections as $transportJob)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><a href="{{ url('/transport-jobs', $transportJob->id) }}">{{$transportJob->number}}</a></td>
                    <td>
                        @if($transportJob->shipment)
                        <a href="{{url('/shipments', $transportJob->shipment_id)}}" title="View Shipment">{{$transportJob->shipment->carrier_consignment_number}}</a>
                        @else
                        {{$transportJob->reference}}
                        @endif
                    </td>
                    <td>
                        @if($transportJob->date_requested)
                        {{$transportJob->date_requested->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                        @else
                        Not defined
                        @endif
                    </td>

                    <td>
                        @if($transportJob->type == 'c')
                        {{$transportJob->from_company_name ?: $transportJob->from_name}}, {{$transportJob->from_city}}
                        @else
                        {{$transportJob->to_company_name ?: $transportJob->to_name}}, {{$transportJob->to_city}}
                        @endif
                    </td>


                    <td class="text-center">                
                        <a href="{{ url('/transport-jobs/' . $transportJob->id . '/unmanifest') }}" title="Unmanifest Job"><span class="fas fa-undo" aria-hidden="true"></span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

{!! Form::Close() !!}

@endsection