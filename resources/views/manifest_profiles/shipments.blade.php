@extends('layouts.app')

@section('content')

<div id="shipments">

    <h2>Run Manifest - {{$manifestProfile->name}}

        <div class="float-right">
            {!! Form::Open(['id' => 'manifest_profile', 'url' => Request::path(), 'method' => 'get', 'class' => '', 'autocomplete' => 'off']) !!}
            {!! Form::select('id', dropDown('manifestProfiles'), Request::get('id'), array('id' => 'id', 'class' => 'form-control')) !!}
            {!! Form::Close() !!}
        </div>
    </h2>

    <table class="table table-striped table-bordered mb-5">
        <thead>
            <tr class="active">               
                <th class="text-center">Depot</th> 
                <th>Carrier</th>                                                                
                <th>Route</th>           
                <th class="text-right">On Hold</th>     
                <th class="text-right">Available</th>     
                <th class="text-right">Weight (kg)</th> 
            </tr>
        </thead>
        <tbody>
            <tr>
            <tr>                              
                <td class="text-center"><span class="badge badge-secondary">{{$manifestProfile->depot->code}}</span></td> 
                <td>{{$manifestProfile->carrier->name ?? 'Not defined'}}</td>                                
                <td>{{$manifestProfile->route->name ?? 'Not defined'}}</td>                             
                <td class="text-right"><span class="custom-badge label-danger" data-placement="bottom" data-toggle="tooltip" data-original-title="Shipments placed on hold">{{$manifestProfile->onHold}}</span></td>
                <td class="text-right"><span class="custom-badge label-success" data-placement="bottom" data-toggle="tooltip" data-original-title="Shipments available for manifesting">{{$manifestProfile->available}}</span></td>
                <td class="text-right">{{$manifestProfile->weight_available}}</td>                 
            </tr>
            </tr>
        </tbody>
    </table>


    <div class="row mb-5">
        <div class="col-6">                  
            {!! Form::Open(['url' => 'bulk-hold']) !!}

            {!! Form::hidden('id', $manifestProfile->id) !!}
            {!! Form::hidden('hold', 0) !!}

            <div class="form-row align-items-center">                    
                <div class="col-auto">
                    <h4>Bulk Hold:</h4>
                </div>
                <div class="col-auto">
                    {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), null, array('class' => 'form-control')) !!}
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::Close() !!}
        </div>
        <div class="col-6">
            {!! Form::Open(['url' => 'bulk-hold']) !!}

            {!! Form::hidden('id', $manifestProfile->id) !!}
            {!! Form::hidden('hold', 1) !!}

            <div class="form-row align-items-center">
                <div class="col-auto">
                    <h4>Bulk Release:</h4>
                </div>
                <div class="col-auto">
                    {!! Form::select('company_id', dropDown('enabledSites', 'Please select'), null, array('class' => 'form-control')) !!}
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::Close() !!}
        </div>
    </div>



    @if($manifestProfile->on_hold > 0)

    @include('manifest_profiles.partials.shipments', ['shipments' => $shipmentsOnHold, 'title' => 'Shipments On Hold', 'class' => 'danger'])

    @endif

    @if($manifestProfile->available > 0)

    @include('manifest_profiles.partials.shipments', ['shipments' => $shipmentsAvailable, 'title' => 'Shipments Available', 'class' => 'success'])

    @can('run_manifest')

    {!! Form::Open(['method' => 'POST', 'url' => ['manifest-profiles/run', $manifestProfile->id], 'class' => '', 'autocomplete' => 'off']) !!}

    @if($previousManifest && $withinTimePeriod)

    <input type="hidden" name="manifest_id" value="{{$previousManifest->id}}">

    <div class="form-check text-center mt-5 mb-3 text-large">
        <input class="form-check-input" type="checkbox" name="append" value="1">
        <label class="form-check-label">Add to previous manifest - {{$previousManifest->number}} ({{$previousManifest->created_at->timezone(Auth::user()->time_zone)->format('l jS F H:i')}})</label>
    </div>

    @endif

    <div class="buttons-main text-center">    
        <a class="back btn btn-secondary" role="button">Cancel</a>
        <button id="run-manifest" type="submit" class="btn btn-primary">Run Manifest</button>   
    </div>

    {!! Form::Close() !!}

    @endcan

    @else

    <div class="no-results">No shipments available for manifesting.</div>

    @endif

</div>

@endsection