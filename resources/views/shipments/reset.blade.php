@extends('layouts.app')

@section('content')

    <div class="clearfix">
        <h2 class="float-left">Reset Shipment - {{$shipment->consignment_number}}
            <small class="text-muted ml-sm-3">
                {{ $shipment->service->name }} ({{strtoupper($shipment->carrier->code)}})
            </small>
        </h2>
        <h2 class="float-right"><span class="badge badge-{{$shipment->status->code}}">{{$shipment->status->name}}</span>
        </h2>
    </div>

    <hr>

    <form action="{{ url('shipments/' . $shipment->id . '/reset') }}" method="post" autocomplete="off">

        @csrf

        <div class="row justify-content-between">

            <div class="col-5">

                <div class="form-group row{{ $errors->has('service') ? ' has-danger' : '' }}">

                    <label class="col-sm-4  col-form-label">
                        Service: <abbr title="This information is required.">*</abbr>
                    </label>

                    <div class="col-sm-6">
                        {!! Form::select('service',  [76 => 'XDP UK48'], old('service'), array('class' => 'form-control')) !!}

                        @if ($errors->has('service'))
                            <span class="form-text"><strong>{{ $errors->first('service') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('reprice') ? ' has-danger' : '' }} mb-4">

                    <label class="col-sm-4  col-form-label">
                        Reprice Shipment: <abbr title="This information is required.">*</abbr>
                    </label>

                    <div class="col-sm-6">
                        {!! Form::select('reprice',  ['' => 'Please select', 0 => 'Keep original sales', 1 => 'Recalculate sales'], old('reprice'), array('class' => 'form-control')) !!}

                        @if ($errors->has('reprice'))
                            <span class="form-text"><strong>{{ $errors->first('reprice') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-6">
                        <a class="back btn btn-outline-secondary" role="button">Cancel</a>
                        <button type="submit" class="btn btn-primary ml-sm-4">Reset Shipment</button>
                    </div>
                </div>

            </div>

            <div class="col-5">
                <div class="card text-large">
                    <div class="card-header"><span class="fas fa-info-circle" aria-hidden="true"></span> <strong
                                class="ml-sm-1">Info</strong> <span class="ml-sm-4">Shipment Reset</span></div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection
