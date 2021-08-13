@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'Domestic Pricing Zones', 'results'=> null, 'create' => 'Create Model'])

<div class="table table-striped-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">Model</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($models as $model)
            <tr>
                <td class="text-center">{{ $model->model }}</td>
                <td class="text-center">
                    <a href="{{ url('/domestic-zones/' . $model->model . '/download') }}" title="Download Zones"><span class="fas fa-cloud-download-alt ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/domestic-zones/' . $model->model . '/upload') }}" title="Upload Zones"><span class="fas fa-cloud-upload-alt ml-sm-2" aria-hidden="true"></span></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
