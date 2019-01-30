@extends('layouts.app')

@section('content')

{!! Form::Open(['url' => 'shipments/pod', 'class' => '', 'autocomplete' => 'off']) !!}

<div class="row">

    <div class="col-sm-5">

        <h2>POD Shipment</h2>
        

        <div class="form-group row{{ $errors->has('consignment_number') ? ' has-danger' : '' }}">

            <label class="col-sm-4  col-form-label">Consignment Number:</label>

            <div class="col-sm-8">
                {!! Form::Text('consignment_number', old('consignment_number'), ['class' => 'form-control', 'maxlength' => '15']) !!}

                @if ($errors->has('consignment_number'))
                <span class="form-text">
                    <strong>{{ $errors->first('consignment_number') }}</strong>
                </span>
                @endif
            </div>

        </div>

        <div class="form-group row{{ $errors->has('signature') ? ' has-danger' : '' }}">
            <label class="col-sm-4  col-form-label">
                Signature: <abbr title="This information is required.">*</abbr>
            </label>

            <div class="col-sm-8">
                {!! Form::Text('signature', old('signature'), ['class' => 'form-control', 'maxlength' => '30']) !!}

                @if ($errors->has('signature'))
                <span class="form-text">
                    <strong>{{ $errors->first('signature') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4  col-form-label">
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

            <div class="col-sm-4">
                {!! Form::select('time', dropDown('times'), old('time', date('H:i')), array('id' => 'time', 'class' => 'form-control')) !!}

                @if ($errors->has('time'))
                <span class="form-text">
                    <strong>{{ $errors->first('time') }}</strong>
                </span>
                @endif

            </div>
        </div>

        <div class="form-group row buttons-main">
            <div class="col-sm-4">&nbsp;</div>
            <div class="col-sm-6">
                <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">POD Shipment</button>
            </div>
        </div>

    </div>

    <div class="col-sm-1"></div>

    <div class="col-sm-6">

        <br>
        <h4 class="mb-2">Awaiting POD <span class="badge badge-pill badge-secondary">{{ $shipments->count()}}</span></h4>

        <div class="table table-striped-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Consignment</th>
                        <th>Ship Date</th>
                        <th>Recipient</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $shipment)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><a href="{{ url('/shipments', $shipment->id) }}" tabindex="-1">{{$shipment->consignment_number}}</a></td>
                        <td>{{$shipment->ship_date->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}</td>
                        <td>{{$shipment->recipient_name}}, {{$shipment->recipient_address1}}, {{$shipment->recipient_city}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

{!! Form::Close() !!}

@endsection