@extends('layouts.app')

@section('content')

<h2>Services: {{$company->company_name}}</h2>

{!! Form::model($company, ['method' => 'POST', 'url' => 'companies/' . $company->id . '/services', 'class' => '', 'autocomplete' => 'off']) !!}


<div class="row">
    <div class="col">
        <div class="radio">
            <label>
                {!! Form::radio('use_default', 1, $company->uses_default_services) !!}
                Use default services
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('use_default', 0, $company->uses_defined_services) !!}
                Define services
            </label>
        </div>

        <div class="mx-auto mt-3">
            <a class="back btn btn-secondary" role="button">Cancel</a>
            <button type="submit" class="btn btn-primary ml-sm-4">Set Services</button>
        </div>
    </div>
    <div class="col">
        @if($company->services->count() > 0)
        <div class="card">
            <div class="card-header">SERVICES DEFINED</div>
            <div class="card-body">
                @foreach ($company->services as $service)
                <p>{{$service->name}} - ({{$service->carrier_name}})</p>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>



<table class="table  table-hover table-bordered table-sm mt-5 mb-5">
    <thead class="thead-light">
        <tr>
            <th></th>
            <th>Name</th>
            <th>Code</th>
            <th>Carrier</th>
            <th>Carrier Code</th>
            <th>Carrier Name</th>
            <th>Account</th>
            <th class="text-center">Default</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($services as $service)
        <tr>
            <td class="text-center">{!! Form::checkbox('services[]', $service->id, null, null, ['class' => 'form-check-input']) !!}</td>
            <td>{{$service->name}}</td>
            <td class="text-uppercase">{{$service->code}}</td>
            <td>
                @if($service->carrier)
                {{$service->carrier->name}}
                @else

                @endif
            </td>
            <td class="text-uppercase">{{$service->carrier_code}}</td>
            <td>{{$service->carrier_name}}</td>
            <td class="text-uppercase">{{$service->account}}</td>     
            <td class="text-center">
                @if($service->default)
                <span class="text-success font-weight-bold">Yes</span>
                @else
                No
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! Form::Close() !!}


@endsection