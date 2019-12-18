@extends('layouts.app')

@section('content')

    @include('partials.title', ['title' => 'IFS Non Delivery Postcodes', 'results'=> $postcodes, 'create' => 'ifs_nd_postcode'])

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Postcode</th>
            <th>Date Added</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($postcodes as $postcode)
            <tr>
                <td>
                    {{$postcode->postcode}}
                </td>
                <td>
                    @if($postcode->created_at)
                        {{$postcode->created_at->format('d/m/Y')}}
                    @else
                        Unknown
                    @endif
                </td>
                <td class="text-right">
                    <a href="{{ url('/ifs-nd-postcodes/' . $postcode->id) }}" title="Delete Postcode {{$postcode->postcode}}" class="mr-2 delete" data-record-name="postcode"><i class="fas fa-times"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection