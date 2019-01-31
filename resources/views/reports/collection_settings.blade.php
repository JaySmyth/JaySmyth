@extends('layouts.app')

@section('content')

<h2>{{$report->name}}</h2>

@php
$last = null;
$class = null;
@endphp

<table class="table table-bordered table-striped table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Postcode</th>
            <th>Day</th>
            <th>Collection Time</th>
            <th>Delivery Time</th>
            <th class="text-center">Collection Route</th>
            <th class="text-center">Delivery Route</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($collectionSettings as $collectionSetting)

        @php
        if(substr($collectionSetting['postcode'], 0, 4) != $last && $last != null){
        $class = ($class) ? false : 'table-secondary';
        }
        @endphp

        <tr class="{{ $class }}">
            <td>{{$loop->iteration}}</td>
            <td><a href="{{ url('/companies', $collectionSetting['id']) }}">{{$collectionSetting['company_name']}}</a></td>
            <td>
                @if(!stristr($collectionSetting['postcode'], ' '))<span class="font-weight-bold text-danger">@endif
                    {{$collectionSetting['postcode']}}
                    @if(!stristr($collectionSetting['postcode'], ' '))</span>@endif
            </td>
            <td>{{intToDay($collectionSetting['day'])}}</td>
            <td>{{$collectionSetting['collection_time']}}</td>
            <td>{{$collectionSetting['delivery_time']}}</td>
            <td class="text-center">
                @if($collectionSetting['collection_route'])
                <span class="badge @if($collectionSetting['collection_route'] == 'ADHOC') badge-danger @else badge-secondary @endif">{{$collectionSetting['collection_route']}}</span>
                @else
                <span class="text-muted">Not Defined</span>
                @endif
            </td>
            <td class="text-center">
                @if($collectionSetting['delivery_route'])
                <span class="badge @if($collectionSetting['delivery_route'] == 'ADHOC') badge-danger @else badge-secondary @endif">{{$collectionSetting['delivery_route']}}</span>
                @else
                <span class="text-muted">Not Defined</span>
                @endif
            </td>
        </tr>

        @php $last = substr($collectionSetting['postcode'], 0, 4); @endphp

        @endforeach

    </tbody>
</table>

@endsection