@extends('layouts.app')

@section('content')

<div class="row justify-content-between">
    <div class="col">
        <h2>Postcodes</h2>
    </div>
    <div class="col text-right">
        <a href="/postcodes/create" class="btn btn-success btn-sm btn-xs text-white" title="New Postcode" role="button"><span class="fas fa-plus-circle text-white mr-sm-1" aria-hidden="true"></span>New</a>
    </div>
</div>

<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>Postcode</th>
            <th>Default Collection Time</th>                                
            <th class="text-center">Collection Route</th>
            <th class="text-center">Delivery Route</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($postcodes as $postcode)
        <tr>
            <td>
                @can('create_postcode')<a href="{{ url('/postcodes/' . $postcode->id . '/edit') }}">{{$postcode->postcode}}</a>@endcan
            </td>
            <td>{{$postcode->pickup_time}}</td>
            <td class="text-center">
                @if($postcode->collection_route)
                <span class="badge badge-secondary">{{$postcode->collection_route}}</span>
                @else
                <span class="text-muted">Not Defined</span>
                @endif
            </td>
            <td class="text-center">
                @if($postcode->delivery_route)
                <span class="badge badge-secondary">{{$postcode->delivery_route}}</span>
                @else
                <span class="text-muted">Not Defined</span>
                @endif
            </td>
            <td>
                @can('create_postcode')<a href="{{ url('/postcodes/' . $postcode->id . '/edit') }}" title="Edit Postcode"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>



@endsection