@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'General Pricing Zones (Mostly Intl)', 'results'=> null, 'create' => 'Create Model'])

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">Pricing Model</th>
                <th class="text-center">Service Code</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td class="text-center">{{ $models[$service['model_id']] }}</td>
                <td class="text-center">{{ $service['service_code'] }}</td>
                <td class="text-center">
                    <a href="{{ url('/pricing-zones/' . $service->model_id . '/' . $service->service_code . '/download') }}" title="Download Zones"><span class="fas fa-cloud-download-alt ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/pricing-zones/' . $service->model_id . '/' . $service->service_code . '/upload') }}" title="Upload Zones"><span class="fas fa-cloud-upload-alt ml-sm-2" aria-hidden="true"></span></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
