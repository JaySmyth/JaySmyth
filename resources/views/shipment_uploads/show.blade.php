@extends('layouts.app')

@section('content')

<h2>{{$shipmentUpload->importConfig->company_name}} Shipment Upload (SFTP)</h2>

<hr class="mb-0">

<div class="row">
    <div class="col-4">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Directory
                <span class="font-weight-bold">{{$shipmentUpload->directory}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Last Upload
                <span>
                    @if($shipmentUpload->last_upload)
                    {{$shipmentUpload->last_upload->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                    @else
                    <i>Never</i>
                    @endif
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Total Files Processed
                <span class="badge badge-primary">{{$shipmentUpload->total_processed}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Enabled
                @if($shipmentUpload->enabled)
                <span class="badge badge-success">Yes</span>
                @else
                <span class="badge badge-danger">No</span>
                @endif
            </li>
        </ul>
    </div>
</div>

@include('partials.log', ['logs' => $shipmentUpload->logs])

@endsection