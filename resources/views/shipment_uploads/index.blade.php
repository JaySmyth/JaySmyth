@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'Shipment Uploads (SFTP)', 'results'=> $shipmentUploads, 'create' => 'shipment upload'])

<table class="table table-striped">
    <thead>
        <tr>                              
            <th>Import Config</th>                        
            <th>Directory</th>            
            <th>Last Upload</th>
            <th class="text-center">Total Processed</th>
            <th class="text-center">Enabled</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipmentUploads as $upload)
        <tr>
            <td><a href="{{ url('/shipment-uploads', $upload->id)}}">{{$upload->importConfig->company_name}}</a></td>   
            <td class="font-weight-bold">       
                {{$upload->directory}}          
            </td>

            <td>
                @if($upload->last_upload)
                {{$upload->last_upload->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}
                @else
                <i>Never</i>
                @endif
            </td>
            <td class="text-center"><span class="badge badge-primary">{{$upload->total_processed}}</span></td>
            <td class="text-center">
                @if($upload->enabled)
                <span class="badge badge-success">Yes</span>
                @else
                <span class="badge badge-danger">No</span>
                @endif
            </td>
            <td class="text-center">
                <a href="{{ url('/shipment-uploads/' . $upload->id) }}" title="Delete {{$upload->importConfig->company_name}}" class="delete mr-2" data-record-name="upload config"><i class="fas fa-times"></i></a>
                <a href="{{ url('/shipment-uploads/' . $upload->id . '/edit') }}" title="Edit Upload"><span class="fas fa-edit" aria-hidden="true"></span></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'file uploads', 'results'=> $shipmentUploads])
@include('partials.pagination', ['results'=> $shipmentUploads])

@endsection