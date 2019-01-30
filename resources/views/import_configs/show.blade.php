@extends('layouts.app')

@section('content')

<h2>Import Config: {{$importConfig->company_name}}</h2>

<div class="row pt-2 pb-5">
    <div class="col-4">

        <div class="card">
            <div class="card-header font-weight-bold"><i class="fas fa-cog mr-1"></i> Settings</div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">Company <span class="font-weight-bold">{{$importConfig->company->company_name}}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Delimeter <span class="font-weight-bold text-capitalize">{{$importConfig->delim}}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Enabled
                    @if($importConfig->enabled)
                    <span class="badge badge-success">Yes</span>
                    @else
                    <span class="badge badge-danger">No</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Test Mode
                    @if($importConfig->test_mode)
                    <span class="badge badge-danger">Yes</span>
                    @else
                    <span class="badge badge-success">No</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Start Row <span class="font-weight-bold">{{$importConfig->start_row}}</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Default User For SFTP Uploads
                    @if($importConfig->user)
                    <span class="font-weight-bold">{{$importConfig->user->name}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Service
                    @if($importConfig->default_service)
                    <span class="badge badge-secondary text-uppercase">{{$importConfig->default_service}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Terms
                    @if($importConfig->default_terms)
                    <span class="font-weight-bold">{{$importConfig->default_terms}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Pieces
                    @if($importConfig->default_pieces)
                    <span class="font-weight-bold">{{$importConfig->default_pieces}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Weight
                    @if($importConfig->default_weight)
                    <span class="font-weight-bold">{{$importConfig->default_weight}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Goods Description
                    @if($importConfig->default_goods_description)
                    <span class="font-weight-bold">{{$importConfig->default_goods_description}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Customs Value
                    @if($importConfig->default_customs_value)
                    <span class="font-weight-bold">{{$importConfig->default_customs_value}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Recipient Name
                    @if($importConfig->default_recipient_name)
                    <span class="font-weight-bold">{{$importConfig->default_recipient_name}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Recipient Telephone
                    @if($importConfig->default_recipient_telephone)
                    <span class="font-weight-bold">{{$importConfig->default_recipient_telephone}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Default Recipient Email
                    @if($importConfig->default_recipient_email)
                    <span class="font-weight-bold">{{$importConfig->default_recipient_email}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    CC Import Results Email To

                    @if($importConfig->cc_import_results_email)
                    <span class="font-weight-bold">{{$importConfig->cc_import_results_email}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif

                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Shipment Reference Separator

                    @if($importConfig->ship_ref_separator)
                    <span class="font-weight-bold">{{$importConfig->ship_ref_separator}}</span>
                    @else
                    <span class="text-muted">Not defined</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>

    <div class="col-3">

        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between">
                <span><i class="far fa-file-excel mr-1"></i> Columns</span>
                <a class="btn btn-primary btn-xs" href="{{ url('/import-configs/' . $importConfig->id . '/download-example') }}" role="button"><i class="fas fa-download mr-3 text-white"></i>Download Example</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($importConfig->getColumns() as $key => $column)
                <li class="list-group-item font-weight-bold"><span class="badge badge-secondary text-uppercase mr-4">{{$key}}</span>{{snakeCaseToWords($column)}}</li>
                @endforeach
            </ul>
        </div>

    </div>

</div>


@endsection