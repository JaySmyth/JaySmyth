@extends('layouts.app')

@section('content')

    @include('partials.title', ['title' => 'Invalid Commodity Descriptions', 'results'=> $descriptions, 'create' => 'invalid_commodity_description'])

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Commodity Description</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($descriptions as $description)
            <tr>
                <td>
                    {{$description->description}}
                </td>
                <td class="text-right">
                    <a href="{{ url('/invalid-commodity-descriptions/' . $description->id) }}" title="Delete Description {{$description->description}}" class="mr-2 delete" data-record-name="description"><i class="fas fa-times"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection