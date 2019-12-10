@extends('layouts.app')

@section('content')

    <div class="row justify-content-between">
        <div class="col">
            <h2>IFS Non Delivery Postcodes</h2>
        </div>
        <div class="col text-right">

        </div>
    </div>

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Postcode</th>
        </tr>
        </thead>
        <tbody>
        @foreach($postcodes as $postcode)
            <tr>
                <td>
                    {{$postcode->postcode}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection