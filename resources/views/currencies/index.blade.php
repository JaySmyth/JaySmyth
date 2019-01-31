@extends('layouts.app')

@section('content')

@include('partials.title', ['title' => 'currencies', 'results'=> $currencies, 'create' => 'currency'])

<table class="table table-striped">
    <thead>
        <tr>
            <th>Currency</th>
            <th>Code</th>
            <th>Display Order</th>                                
            <th>Exchange Rate</th>
            <th>Date Set</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($currencies as $currency)
        <tr>
            <td>
                @can('currency_admin')<a href="{{ url('/currencies/' . $currency->id . '/edit') }}">{{$currency->currency}}</a>@endcan
            </td>
            <td><span class="badge badge-secondary">{{$currency->code}}</span></td>
            <td>{{$currency->display_order}}</td>
            <td>
                @if($currency->rate)
                {{$currency->rate}}
                @else
                <i>Rate not set</i>
                @endif
            </td>
            <td>
                @if($currency->updated_at)
                {{$currency->updated_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format)}}
                @else
                <i>Unknown</i>
                @endif
            </td>
            <td>
                @can('currency_admin')<a href="{{ url('/currencies/' . $currency->id . '/edit') }}" title="Edit Currency"><span class="fas fa-edit ml-sm-2" aria-hidden="true"></span></a>@endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials.no_results', ['title' => 'currencies', 'results'=> $currencies])
@include('partials.pagination', ['results'=> $currencies])

@endsection