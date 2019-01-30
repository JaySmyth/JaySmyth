@extends('layouts.app')

@section('content')

@section('toolbar')
<a href="{{url('download/shipment_upload.pdf')}}" title="Download User Guide"><i class="far fa-question-circle fa-lg"></i></a>
@endsection  

@include('partials.title', ['title' => 'Import Configs', 'results'=> $importConfigs, 'create' => 'configuration'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Config Name</th> 
            <th>Company</th>
            <th>Created</th>
            <th>Updated</th>
            <th class="text-center">Test Mode</th>
            <th class="text-center">Enabled</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($importConfigs as $importConfig)
        <tr>            
            <td><a href="{{ url('/import-configs', $importConfig->id) }}">{{$importConfig->company_name}}</a></td>                        
            <td><a href="{{ url('/companies', $importConfig->company->id) }}">{{$importConfig->company->company_name}}</a></td>
            <td>{{$importConfig->created_at}}</td>
            <td>{{$importConfig->updated_at}}</td>
            <td class="text-center">
                @if($importConfig->test_mode)
                <span class="text-danger">Yes</span>
                @else
                <span class="text-success">No</span>
                @endif
            </td>
            <td class="text-center">
                @if($importConfig->enabled)
                <span class="text-success">Yes</span>
                @else
                <span class="text-danger">No</span>
                @endif
            </td>
            <td class="text-center">
                <a href="{{ url('/import-configs/' . $importConfig->id) }}" title="Delete {{$importConfig->company_name}}" class="delete mr-2" data-record-name="config"><i class="fas fa-times"></i></a>
                <a href="{{ url('/import-configs/' . $importConfig->id . '/download-example') }}" title="Download Example" class="mr-2"><i class="fas fa-download" aria-hidden="true"></i></a>                
                <a href="{{ url('/import-configs/' . $importConfig->id . '/edit') }}" title="Edit Config"><i class="fas fa-edit" aria-hidden="true"></i></a>                                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'Import Configs', 'results'=> $importConfigs])
@include('partials.pagination', ['results'=> $importConfigs])

@endsection