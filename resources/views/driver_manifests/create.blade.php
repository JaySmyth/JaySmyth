@extends('layouts.app')

@section('content')

<h2>Create Driver Manifests</h2>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>Driver</th>
            <th class="align-middle text-center">Vehicle</th>
            <th class="align-middle text-center">Date</th>                
            <th class="align-middle text-center"><input type="checkbox" id="check-all"></th>
        </tr>
    </thead>

    {!! Form::Open(['url' => 'driver-manifests', 'class' => 'form-inline', 'autocomplete' => 'off']) !!}

    <tbody>
        @foreach($drivers as $driver)
        <tr>
            <td class="align-middle">
                <a href="{{ url('/drivers', $driver->id) }}">{{$driver->name}}</a>
            </td>

            <td class="align-middle text-center">

                {!! Form::select('driver['.$driver->id.'][vehicle]', dropDown('vehicles'), old('driver['.$driver->id.'][vehicle]', $driver->vehicle_id), array('class' => 'form-control')) !!}

                @if ($errors->has('vehicle' . $driver->id))        
                <strong class="text-danger">{{ $errors->first('vehicle' . $driver->id) }}</strong>               
                @endif

            </td>

            <td class="align-middle text-center">

                {!! Form::select('driver['.$driver->id.'][date]',  dropDown('datesShort'), old('driver['.$driver->id.'][date]', date('d-m-Y', strtotime('today'))), array('class' => 'form-control')) !!}

                @if ($errors->has('driver' . $driver->id))        
                <strong class="text-danger">{{ $errors->first('driver' . $driver->id) }}</strong>                    
                @endif

            </td>  

            <td class="align-middle text-center">{!! Form::checkbox('drivers['.$driver->id.']', 1, old('drivers['.$driver->id.']')) !!}</td>
        </tr>
        @endforeach
    </tbody>        
</table>


<div class="buttons-main text-center">    
    <a class="back btn btn-secondary" role="button">Cancel</a>
    <button type="submit" class="btn btn-primary">Create Manifests</button>   

    @if ($errors->has('drivers'))
    <span class="form-text text-danger">
        <strong>{{ $errors->first('drivers')}}</strong>
    </span>
    @endif

</div>

{!! Form::Close() !!}

@endsection