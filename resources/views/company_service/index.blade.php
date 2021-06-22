@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'Company Services', 'results'=> $companyServices])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Service Name Override</th>
            <th>Carrier Account</th>
            <th>SCS Account</th>
            <th>Country Filter</th>
            <th>Monthly Limit</th>
            <th>Max Weight</th>
            <th>Company</th>
            <th>Service</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companyServices as $companyService)
        <tr>
            <td>{{$companyService->name}}</td>
            <td>{{$companyService->account}}</td>
            <td>{{$companyService->scs_account}}</td>
            <td>{{$companyService->country_filter}}</td>
            <td>{{$companyService->monthly_limit}}</td>
            <td>{{$companyService->max_weight_limit}}</td>
            <td>{{ App\Models\Company::find($companyService->company_id)->company_name }}</td>
            <td>{{ App\Models\Service::find($companyService->service_id)->code }}</td>
            <td><a href="{{ url('/company-services/' . $companyService->company_id . '/'.$companyService->service_id) . '/edit' }}" title="Edit Country Filter"><span class="fas fa-filter ml-sm-2" aria-hidden="true"></span></a></td>

        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'Company Services', 'results'=> $companyServices])
@include('partials.pagination', ['results'=> $companyServices])

@endsection
