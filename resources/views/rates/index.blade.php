@extends('layouts.app')

@section('content')

<div class="table table-striped-responsive">
    <h2>Master Cost Rate Sheets</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">Type</th>
                <th class="text-center">Model</th>
                <th>Description</th>
                <th class="text-center">Currency</th>
                <th class="text-center">Units</th>
                <th class="text-center">Divisor</th>
                <th class="text-center">Rates</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rates as $rate)
            @if($rate->rate_type == "c")
            <tr>
                <td class="text-center">
                    <span>Cost</span>
                </td>
                <td class="text-center">{{ $rate->model }}</td>
                @if($rate->details()->count() == "0")
                <td>{{ $rate->description }} ({{ ($rate->id) }})</td>
                @else
                <td><a href="{{url('/rates/' . $rate->id)}}" title="View Master Rate">{{$rate->description}}</a></td>
                @endif
                <td class="text-center">{{ $rate->currency_code }}</td>
                <td class="text-center">{{ $rate->weight_units }}</td>
                <td class="text-center">{{ $rate->volumetric_divisor }}</td>
                <td class="text-center">
                    <a href="{{ url('/rates/' . $rate->id . '/download') }}" title="Download Rate"><span class="fas fa-cloud-download-alt ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/rates/' . $rate->id . '/upload') }}" title="Upload Rate"><span class="fas fa-cloud-upload-alt ml-sm-2" aria-hidden="true"></span></a>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    <h2>Master Sales Rate Sheets</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">Type</th>
                <th class="text-center">Model</th>
                <th>Description</th>
                <th class="text-center">Currency</th>
                <th class="text-center">Units</th>
                <th class="text-center">Divisor</th>
                <th class="text-center">Rates</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rates as $rate)
            @if($rate->rate_type == "s")
            <tr>
                <td class="text-center">
                    Sales
                </td>
                <td class="text-center">{{ $rate->model }}</td>
                @if($rate->details()->count() == "0")
                <td>{{ $rate->description }} ({{ ($rate->id) }})</td>
                @else
                <td><a href="{{url('/rates/' . $rate->id)}}" title="View Master Rate">{{$rate->description}}</a></td>
                @endif
                <td class="text-center">{{ $rate->currency_code }}</td>
                <td class="text-center">{{ $rate->weight_units }}</td>
                <td class="text-center">{{ $rate->volumetric_divisor }}</td>
                <td class="text-center">
                    <a href="{{ url('/rates/' . $rate->id . '/download') }}" title="Download Rate"><span class="fas fa-cloud-download-alt ml-sm-2" aria-hidden="true"></span></a>
                    <a href="{{ url('/rates/' . $rate->id . '/upload') }}" title="Upload Rate"><span class="fas fa-cloud-upload-alt ml-sm-2" aria-hidden="true"></span></a>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>


@endsection
