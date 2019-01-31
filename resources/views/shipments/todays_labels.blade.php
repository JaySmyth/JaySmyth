@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => "Download Today's Labels", 'results'=> $shipments])

@foreach ($shipments as $shipment)

@if($shipment->source)
<a href="{{url('/labels/' . $shipment->source . '/' . $shipment->user_id)}}"><h4 class="mb-2">Download labels generated: {{$shipment->created_at->timezone(Auth::user()->time_zone)->format('H:i')}}</h4></a>
@endif

@endforeach

@include('partials.no_results', ['title' => 'shipments', 'results'=> $shipments])

@include('partials.pagination', ['results'=> $shipments])

@endsection