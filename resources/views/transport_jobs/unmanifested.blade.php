@extends('layouts.app')

@section('content')

<h2>Unmanifested Jobs <span class="badge badge-primary float-right">{{$totalJobs}}</span></h2>

@if($totalJobs == 0)

@include('partials.alert', ['class' => 'alert-success mt-5', 'heading' => 'All Clear!', 'body' => 'There are currently no jobs that need to be manifested to a driver.'])

@else


@if($totalJobs > 25)

@include('partials.alert', ['class' => 'alert-danger', 'heading' => 'Manifesting Backlog', 'body' => 'A high number of jobs need manifested to drivers. Please action as soon as possible. This board should be kept clear as best practice.'])

@endif

@if($driverManifests->count() == 0)

@include('partials.alert', ['class' => 'alert-warning', 'heading' => 'Driver Manifests', 'body' => 'There are currently no driver manifests open!'])

@endif


{!! Form::Open(['method' => 'POST', 'url' => 'transport-jobs/unmanifested', 'class' => '', 'autocomplete' => 'off']) !!}

@if(isset($transportJobs['collections']))
@include('transport_jobs.partials.unmanifested', ['title' => 'Collections', 'transportJobs' => $transportJobs['collections']])
@endif

@if(isset($transportJobs['deliveries']))
@include('transport_jobs.partials.unmanifested', ['title' => 'Deliveries', 'transportJobs' => $transportJobs['deliveries']])
@endif

@can('manifest_transport_jobs')

@if($driverManifests->count() > 0)

<br>
<div class="form-row align-items-center">
    <div class="col-auto">
        {!! Form::select('driver_manifest_id',$driverManifests->pluck('manifest', 'id'), old('driver_manifest_id'), array('class' => 'form-control')) !!}
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Manifest Selected Jobs</button>
    </div>

    @if ($errors->has('jobs'))
    <span class="form-text text-danger">
        <strong>{{ $errors->first('jobs')}}</strong>
    </span>
    @endif

</div>


@endif
<br>
@endcan

{!! Form::Close() !!}

@endif

@endsection